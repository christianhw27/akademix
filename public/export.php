<?php
/**
 * Standalone CSV/Excel export - no framework overhead
 */
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/../app/core/helpers.php';
require __DIR__ . '/../app/core/Database.php';
require __DIR__ . '/../app/core/Model.php';
require __DIR__ . '/../app/core/Session.php';
require __DIR__ . '/../app/core/Auth.php';

Session::start();

// Redirect to login if not authenticated
if (!Auth::check()) {
    header('Location: ' . route_url('login'));
    exit;
}
if (Auth::role() !== 'teacher') {
    header('Location: ' . route_url('dashboard'));
    exit;
}

$type = $_GET['export_type'] ?? '';
$db   = Database::instance();

// Helper: output a CSV download
function sendCsv(string $filename, array $rows): void
{
    // Kill all buffers
    while (ob_get_level()) ob_end_clean();

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: public');
    header('Expires: 0');

    // UTF-8 BOM so Excel opens it correctly
    echo "\xEF\xBB\xBF";

    $fp = fopen('php://output', 'w');
    foreach ($rows as $row) {
        fputcsv($fp, $row, ';');
    }
    fclose($fp);
    exit;
}

// ── ATTENDANCE EXPORT ────────────────────────────────────────────────────────
if ($type === 'attendance') {
    $sessionId = (int)($_GET['session_id'] ?? 0);
    if (!$sessionId) exit('Parameter tidak lengkap.');

    $userId = Auth::id();

    // Get session (verify ownership via teacher)
    $stmt = $db->prepare("
        SELECT ats.id, ats.attendance_date, ats.notes,
               cl.name  AS classroom_name,
               s.name   AS subject_name,
               COUNT(ar.id)                                          AS total_records,
               SUM(ar.status = 'hadir')                             AS count_hadir,
               SUM(ar.status = 'izin')                              AS count_izin,
               SUM(ar.status = 'sakit')                             AS count_sakit,
               SUM(ar.status = 'alpha')                             AS count_alpha
        FROM attendance_sessions ats
        INNER JOIN classrooms c  ON c.id  = ats.classroom_id
        INNER JOIN classes    cl ON cl.id = c.class_id
        INNER JOIN subjects   s  ON s.id  = ats.subject_id
        INNER JOIN teachers   t  ON t.id  = ats.teacher_id
        LEFT  JOIN attendance_records ar ON ar.attendance_session_id = ats.id
        WHERE ats.id = :id AND t.user_id = :uid
        GROUP BY ats.id
        LIMIT 1
    ");
    $stmt->execute(['id' => $sessionId, 'uid' => $userId]);
    $sess = $stmt->fetch();
    if (!$sess) exit('Sesi absensi tidak ditemukan atau Anda tidak punya akses.');

    // Get detail records
    $stmt2 = $db->prepare("
        SELECT u.full_name AS nama_siswa, st.nis,
               CASE ar.status
                   WHEN 'hadir' THEN 'Hadir'
                   WHEN 'izin'  THEN 'Izin'
                   WHEN 'sakit' THEN 'Sakit'
                   WHEN 'alpha' THEN 'Alfa'
                   ELSE ar.status
               END AS status,
               COALESCE(ar.notes, '') AS keterangan
        FROM attendance_records ar
        INNER JOIN students st ON st.id = ar.student_id
        INNER JOIN users    u  ON u.id  = st.user_id
        WHERE ar.attendance_session_id = :id
        ORDER BY u.full_name ASC
    ");
    $stmt2->execute(['id' => $sessionId]);
    $records = $stmt2->fetchAll();

    // Build filename: Absensi_IPA_1_Fisika_2026-05-18.csv
    $raw      = 'Absensi_' . $sess['classroom_name'] . '_' . $sess['subject_name'] . '_' . $sess['attendance_date'];
    $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $raw) . '.csv';

    // Build rows
    $rows = [];
    $rows[] = ['REKAP ABSENSI'];
    $rows[] = ['Kelas',        $sess['classroom_name']];
    $rows[] = ['Mata Pelajaran', $sess['subject_name']];
    $rows[] = ['Tanggal',      $sess['attendance_date']];
    $rows[] = ['Catatan',      $sess['notes'] ?? ''];
    $rows[] = [];
    $rows[] = ['No', 'Nama Siswa', 'NIS', 'Status', 'Keterangan'];

    $no = 1;
    foreach ($records as $r) {
        $rows[] = [$no++, $r['nama_siswa'], $r['nis'], $r['status'], $r['keterangan']];
    }

    $rows[] = [];
    $rows[] = ['Ringkasan', '', '', '', ''];
    $rows[] = ['Hadir',  $sess['count_hadir']];
    $rows[] = ['Izin',   $sess['count_izin']];
    $rows[] = ['Sakit',  $sess['count_sakit']];
    $rows[] = ['Alfa',   $sess['count_alpha']];
    $rows[] = ['Total',  $sess['total_records']];

    sendCsv($filename, $rows);
}

// ── GRADES EXPORT ────────────────────────────────────────────────────────────
if ($type === 'grades') {
    $classroomId = (int)($_GET['classroom_id'] ?? 0);
    $gradeType   = $_GET['grade_type']   ?? '';
    $title       = $_GET['title']        ?? '';
    $subjectId   = (int)($_GET['subject_id'] ?? 0);

    if (!$classroomId || !$gradeType || !$title || !$subjectId) {
        exit('Parameter ekspor tidak lengkap.');
    }

    $userId = Auth::id();

    // Verify teacher owns this classroom (via schedules)
    $check = $db->prepare("
        SELECT COUNT(*) FROM schedules sc
        INNER JOIN teachers t ON t.id = sc.teacher_id
        WHERE t.user_id = :uid AND sc.classroom_id = :cid
    ");
    $check->execute(['uid' => $userId, 'cid' => $classroomId]);
    if ((int)$check->fetchColumn() === 0) exit('Akses ditolak.');

    // Get classroom name
    $clStmt = $db->prepare("SELECT cl.name FROM classrooms c INNER JOIN classes cl ON cl.id = c.class_id WHERE c.id = :id LIMIT 1");
    $clStmt->execute(['id' => $classroomId]);
    $className = $clStmt->fetchColumn() ?: 'Kelas';

    // Get all students in classroom with their grade (LEFT JOIN)
    $stmt = $db->prepare("
        SELECT u.full_name AS nama_siswa, st.nis,
               COALESCE(g.score, '') AS nilai,
               COALESCE(g.notes, '') AS catatan
        FROM classroom_students cs
        INNER JOIN students st ON st.id = cs.student_id
        INNER JOIN users    u  ON u.id  = st.user_id
        LEFT JOIN grades g ON g.student_id = cs.student_id
            AND g.classroom_id = :cid
            AND g.grade_type   = :gtype
            AND g.title        = :title
            AND g.subject_id   = :sid
        WHERE cs.classroom_id = :cid2
        ORDER BY u.full_name ASC
    ");
    $stmt->execute([
        'cid'   => $classroomId,
        'gtype' => $gradeType,
        'title' => $title,
        'sid'   => $subjectId,
        'cid2'  => $classroomId,
    ]);
    $grades = $stmt->fetchAll();

    $raw      = 'Nilai_' . $className . '_' . $title . '_' . date('Ymd_His');
    $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $raw) . '.csv';

    $rows   = [];
    $rows[] = ['No', 'Nama Siswa', 'NIS', 'Nilai', 'Catatan'];
    $no     = 1;
    foreach ($grades as $g) {
        $rows[] = [$no++, $g['nama_siswa'], $g['nis'], $g['nilai'], $g['catatan']];
    }

    sendCsv($filename, $rows);
}

exit('Tipe ekspor tidak dikenal.');
