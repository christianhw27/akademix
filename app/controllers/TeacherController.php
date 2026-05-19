<?php

class TeacherController extends Controller
{
    private TeacherModel $teacherModel;

    public function __construct()
    {
        $this->teacherModel = $this->model('TeacherModel');
    }

    private function teacherId(): int
    {
        $profile = $this->teacherModel->getProfileByUserId((int) Auth::id());

        if (!$profile) {
            throw new RuntimeException('Profil guru tidak ditemukan.');
        }

        return (int) $profile['id'];
    }

    public function index(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $allSchedules = $this->teacherModel->getSchedulesByTeacher($teacherId);

        // Group by day
        $schedulesByDay = [];
        foreach ($allSchedules as $s) {
            $schedulesByDay[$s['day_of_week']][] = $s;
        }

        // Today's schedule
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $todayName = $days[date('w')];
        $todaySchedule = $schedulesByDay[$todayName] ?? [];

        // Preload students per classroom
        $studentsByClassroom = [];
        foreach ($todaySchedule as $s) {
            $cid = $s['classroom_id'];
            if (!isset($studentsByClassroom[$cid])) {
                $studentsByClassroom[$cid] = $this->teacherModel->getStudentsByClassroom($teacherId, $cid);
            }
        }

        // Today's attendance sessions already taken
        $todaySessions = $this->teacherModel->getAttendanceSessionsByDate($teacherId, date('Y-m-d'));

        $this->view('teacher/index', [
            'title' => 'Beranda Guru',
            'stats' => $this->teacherModel->getStats($teacherId),
            'submissions' => $this->teacherModel->getSubmissions($teacherId),
            'schedulesByDay' => $schedulesByDay,
            'todaySchedule' => $todaySchedule,
            'todayName' => $todayName,
            'studentsByClassroom' => $studentsByClassroom,
            'todaySessions' => $todaySessions,
        ]);
    }

    public function materials(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $this->view('teacher/materials', [
            'title' => 'Materi',
            'materials' => $this->teacherModel->getMaterials($teacherId),
            'subjects' => $this->teacherModel->getSubjectsByTeacher($teacherId),
            'classrooms' => $this->teacherModel->getScheduleMap($teacherId),
        ]);
    }

    public function saveMaterial(): void
    {
        Auth::requireRole('teacher');

        try {
            $file = $_FILES['attachment'] ?? null;
            $this->teacherModel->saveMaterial($this->teacherId(), $_POST, $file);
            flash('success', 'Materi berhasil disimpan.');
        } catch (Throwable $e) {
            flash('error', 'Gagal menyimpan materi: ' . $e->getMessage());
        }

        $this->redirect('teacher/materials');
    }

    public function editMaterial(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $id = (int) ($_GET['id'] ?? 0);

        if (!$id) {
            $this->redirect('teacher/materials');
        }

        $material = $this->teacherModel->getMaterialById($teacherId, $id);
        if (!$material) {
            flash('error', 'Materi tidak ditemukan atau Anda tidak memiliki akses.');
            $this->redirect('teacher/materials');
        }

        $this->view('teacher/edit_material', [
            'title' => 'Edit Materi',
            'material' => $material,
            'subjects' => $this->teacherModel->getSubjectsByTeacher($teacherId),
            'classrooms' => $this->teacherModel->getScheduleMap($teacherId),
        ]);
    }

    public function deleteMaterial(): void
    {
        Auth::requireRole('teacher');

        try {
            $this->teacherModel->deleteMaterial($this->teacherId(), (int) $this->request('id'));
            flash('success', 'Materi berhasil dihapus.');
        } catch (Throwable $e) {
            flash('error', 'Gagal menghapus materi: ' . $e->getMessage());
        }

        $this->redirect('teacher/materials');
    }

    public function assignments(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $this->view('teacher/assignments', [
            'title' => 'Tugas',
            'assignments' => $this->teacherModel->getAssignments($teacherId),
            'subjects' => $this->teacherModel->getSubjectsByTeacher($teacherId),
            'classrooms' => $this->teacherModel->getScheduleMap($teacherId),
            'submissions' => $this->teacherModel->getSubmissions($teacherId),
        ]);
    }

    public function saveAssignment(): void
    {
        Auth::requireRole('teacher');

        try {
            $file = $_FILES['attachment'] ?? null;
            $this->teacherModel->saveAssignment($this->teacherId(), $_POST, $file);
            flash('success', 'Tugas berhasil disimpan.');
        } catch (Throwable $e) {
            flash('error', 'Gagal menyimpan tugas: ' . $e->getMessage());
        }

        $this->redirect('teacher/assignments');
    }

    public function assignmentDetail(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $assignmentId = (int) ($_GET['id'] ?? 0);

        if (!$assignmentId) {
            $this->redirect('teacher/assignments');
        }

        $assignment = $this->teacherModel->getAssignmentById($teacherId, $assignmentId);
        if (!$assignment) {
            flash('error', 'Tugas tidak ditemukan atau Anda tidak memiliki akses.');
            $this->redirect('teacher/assignments');
        }

        $this->view('teacher/assignment_detail', [
            'title' => 'Detail Tugas',
            'assignment' => $assignment,
            'submissions' => $this->teacherModel->getSubmissionsByAssignment($assignmentId),
        ]);
    }

    public function attendance(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $classrooms = $this->teacherModel->getScheduleMap($teacherId);
        $selectedClassroomId = (int) ($this->request('classroom_id', $classrooms[0]['id'] ?? 0));

        $this->view('teacher/attendance', [
            'title' => 'Absensi',
            'sessions' => $this->teacherModel->getAttendanceSessions($teacherId),
            'subjects' => $this->teacherModel->getSubjectsByTeacher($teacherId),
            'classrooms' => $classrooms,
            'selectedClassroomId' => $selectedClassroomId,
            'students' => $selectedClassroomId ? $this->teacherModel->getStudentsByClassroom($teacherId, $selectedClassroomId) : [],
        ]);
    }

    public function saveAttendance(): void
    {
        Auth::requireRole('teacher');

        try {
            $this->teacherModel->saveAttendance($this->teacherId(), $_POST);
            flash('success', 'Absensi berhasil disimpan.');
        } catch (Throwable $e) {
            flash('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }

        $this->redirect('teacher/attendance');
    }

    public function grades(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $classrooms = $this->teacherModel->getScheduleMap($teacherId);
        $selectedClassroomId = (int) ($this->request('classroom_id', 0));
        $gradeType = $this->request('type', 'harian');

        $gradeSessions = $selectedClassroomId ? $this->teacherModel->getGradeSessions($teacherId, $selectedClassroomId, $gradeType) : [];

        $this->view('teacher/grades', [
            'title' => 'Nilai',
            'subjects' => $this->teacherModel->getSubjectsByTeacher($teacherId),
            'classrooms' => $classrooms,
            'selectedClassroomId' => $selectedClassroomId,
            'gradeType' => $gradeType,
            'students' => $selectedClassroomId ? $this->teacherModel->getStudentsByClassroom($teacherId, $selectedClassroomId) : [],
            'gradeSessions' => $gradeSessions,
        ]);
    }

    public function saveGrade(): void
    {
        Auth::requireRole('teacher');

        try {
            $this->teacherModel->saveGrade($this->teacherId(), $_POST);
            flash('success', 'Nilai berhasil disimpan.');
        } catch (Throwable $e) {
            flash('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }

        $this->redirect('teacher/grades');
    }

    public function saveMassGrades(): void
    {
        Auth::requireRole('teacher');

        try {
            $this->teacherModel->saveMassGrades($this->teacherId(), $_POST);
            flash('success', 'Nilai berhasil disimpan.');
        } catch (Throwable $e) {
            flash('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }

        $this->redirect('teacher/grades&type=' . urlencode($_POST['grade_type'] ?? 'harian') . '&classroom_id=' . (int)($_POST['classroom_id'] ?? 0));
    }

    public function exportGrades(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        
        $classroomId = (int)$this->request('classroom_id', 0);
        $gradeType = $this->request('type', '');
        $title = $this->request('title', '');
        $subjectId = (int)$this->request('subject_id', 0);
        
        if (!$classroomId || !$gradeType || !$title || !$subjectId) {
            flash('error', 'Parameter ekspor tidak lengkap.');
            $this->redirect('teacher/grades');
        }

        $grades = $this->teacherModel->getGradesBySession($teacherId, $classroomId, $gradeType, $title, $subjectId);
        
        $className = '';
        $classrooms = $this->teacherModel->getScheduleMap($teacherId);
        foreach ($classrooms as $c) {
            if ($c['id'] == $classroomId) {
                $className = $c['name'];
                break;
            }
        }

        // Build content first
        $content = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $content .= '<head><meta http-equiv="Content-type" content="text/html;charset=utf-8"></head>';
        $content .= '<body>';
        $content .= '<table border="1">';
        $content .= '<tr>';
        $content .= '<th style="background-color: #f2f2f2;">No</th>';
        $content .= '<th style="background-color: #f2f2f2;">Nama Siswa</th>';
        $content .= '<th style="background-color: #f2f2f2;">NIS</th>';
        $content .= '<th style="background-color: #f2f2f2;">Nilai</th>';
        $content .= '<th style="background-color: #f2f2f2;">Catatan</th>';
        $content .= '</tr>';

        $no = 1;
        foreach ($grades as $grade) {
            $content .= '<tr>';
            $content .= '<td>' . $no++ . '</td>';
            $content .= '<td>' . e($grade['student_name']) . '</td>';
            $content .= '<td style="mso-number-format:\'@\';">' . e($grade['nis']) . '</td>';
            $content .= '<td>' . ($grade['score'] !== null ? e((string)$grade['score']) : '-') . '</td>';
            $content .= '<td>' . ($grade['notes'] !== null ? e((string)$grade['notes']) : '') . '</td>';
            $content .= '</tr>';
        }
        
        $content .= '</table>';
        $content .= '</body></html>';

        $rawFilename = 'Nilai_' . $className . '_' . $title . '_' . date('Ymd_His') . '.xls';
        $filename = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $rawFilename);

        $this->sendExcelDownload($filename, $content);
    }

    public function exportAttendance(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $sessionId = (int) $this->request('session_id', 0);

        if (!$sessionId) {
            flash('error', 'Parameter ekspor tidak lengkap.');
            $this->redirect('teacher/attendance');
        }

        // Get session info
        $sessions = $this->teacherModel->getAttendanceSessions($teacherId);
        $currentSession = null;
        foreach ($sessions as $s) {
            if ((int) $s['id'] === $sessionId) {
                $currentSession = $s;
                break;
            }
        }

        if (!$currentSession) {
            flash('error', 'Sesi absensi tidak ditemukan.');
            $this->redirect('teacher/attendance');
        }

        $records = $this->teacherModel->getAttendanceRecordsBySession($sessionId);

        // Build content first
        $content = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $content .= '<head><meta http-equiv="Content-type" content="text/html;charset=utf-8"></head>';
        $content .= '<body>';

        // Header info
        $content .= '<table>';
        $content .= '<tr><td style="font-weight:bold; font-size:14px;" colspan="5">Rekap Absensi</td></tr>';
        $content .= '<tr><td>Kelas</td><td colspan="4">' . e($currentSession['classroom_name']) . '</td></tr>';
        $content .= '<tr><td>Mata Pelajaran</td><td colspan="4">' . e($currentSession['subject_name']) . '</td></tr>';
        $content .= '<tr><td>Tanggal</td><td colspan="4">' . e($currentSession['attendance_date']) . '</td></tr>';
        $content .= '<tr><td>Catatan</td><td colspan="4">' . e((string) $currentSession['notes']) . '</td></tr>';
        $content .= '<tr><td colspan="5"></td></tr>';
        $content .= '</table>';

        // Attendance table
        $content .= '<table border="1">';
        $content .= '<tr>';
        $content .= '<th style="background-color: #f2f2f2;">No</th>';
        $content .= '<th style="background-color: #f2f2f2;">Nama Siswa</th>';
        $content .= '<th style="background-color: #f2f2f2;">NIS</th>';
        $content .= '<th style="background-color: #f2f2f2;">Status</th>';
        $content .= '<th style="background-color: #f2f2f2;">Keterangan</th>';
        $content .= '</tr>';

        $no = 1;
        $statusLabels = ['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpha' => 'Alfa'];
        foreach ($records as $record) {
            $statusLabel = $statusLabels[$record['status']] ?? ucfirst($record['status']);
            $content .= '<tr>';
            $content .= '<td>' . $no++ . '</td>';
            $content .= '<td>' . e($record['student_name']) . '</td>';
            $content .= '<td style="mso-number-format:\'@\';">' . e($record['nis']) . '</td>';
            $content .= '<td>' . e($statusLabel) . '</td>';
            $content .= '<td>' . e((string) ($record['notes'] ?? '')) . '</td>';
            $content .= '</tr>';
        }

        // Summary row
        $content .= '<tr><td colspan="5"></td></tr>';
        $content .= '<tr>';
        $content .= '<td colspan="2" style="font-weight:bold;">Ringkasan</td>';
        $content .= '<td style="color:#16a34a; font-weight:bold;">Hadir: ' . e((string) $currentSession['count_hadir']) . '</td>';
        $content .= '<td style="color:#3b82f6; font-weight:bold;">Izin: ' . e((string) $currentSession['count_izin']) . '</td>';
        $content .= '<td style="color:#eab308; font-weight:bold;">Sakit: ' . e((string) $currentSession['count_sakit']) . '</td>';
        $content .= '</tr>';
        $content .= '<tr>';
        $content .= '<td colspan="2"></td>';
        $content .= '<td style="color:#dc2626; font-weight:bold;">Alfa: ' . e((string) $currentSession['count_alpha']) . '</td>';
        $content .= '<td style="font-weight:bold;">Total: ' . e((string) $currentSession['total_records']) . '</td>';
        $content .= '<td></td>';
        $content .= '</tr>';

        $content .= '</table>';
        $content .= '</body></html>';

        $rawFilename = 'Absensi_' . $currentSession['classroom_name'] . '_' . $currentSession['subject_name'] . '_' . $currentSession['attendance_date'] . '.xls';
        $filename = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $rawFilename);

        $this->sendExcelDownload($filename, $content);
    }

    private function sendExcelDownload(string $filename, string $content): void
    {
        // Clear ALL output buffers to ensure no stale output breaks headers
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Remove any previously set headers
        if (!headers_sent()) {
            header_remove();
            header('Content-Type: application/vnd.ms-excel; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . strlen($content));
            header('Cache-Control: max-age=0, no-cache, must-revalidate');
            header('Pragma: public');
            header('Expires: 0');
        }

        echo $content;
        exit;
    }

    public function editAttendance(): void
    {
        Auth::requireRole('teacher');

        $sessionId = (int) ($_GET['session_id'] ?? 0);
        $teacherId = $this->teacherId();
        $sessions = $this->teacherModel->getAttendanceSessions($teacherId);

        // Find the session
        $currentSession = null;
        foreach ($sessions as $s) {
            if ((int)$s['id'] === $sessionId) {
                $currentSession = $s;
                break;
            }
        }

        $records = $sessionId ? $this->teacherModel->getAttendanceRecordsBySession($sessionId) : [];

        $this->view('teacher/edit_attendance', [
            'title' => 'Edit Absensi',
            'session' => $currentSession,
            'records' => $records,
            'sessionId' => $sessionId,
        ]);
    }

    public function updateAttendance(): void
    {
        Auth::requireRole('teacher');

        try {
            $teacherId = $this->teacherId();
            $recordIds = $_POST['record_ids'] ?? [];
            $statuses = $_POST['statuses'] ?? [];

            foreach ($recordIds as $recordId) {
                $status = $statuses[$recordId] ?? 'hadir';
                $this->teacherModel->updateAttendanceRecord($teacherId, (int) $recordId, $status);
            }

            flash('success', 'Absensi berhasil diperbarui.');
        } catch (Throwable $e) {
            flash('error', 'Gagal memperbarui absensi: ' . $e->getMessage());
        }

        $this->redirect('teacher/attendance');
    }
}
