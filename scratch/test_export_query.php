<?php
require __DIR__ . '/../app/core/helpers.php';
require __DIR__ . '/../app/core/Database.php';
require __DIR__ . '/../app/core/Model.php';
require __DIR__ . '/../app/core/Session.php';

$db = Database::instance();

// Simulate the fixed getGradesBySession query
// teacher_id=19 (Sari/Hendra), classroom_id=5, grade_type=tugas, title=Nilai Akhir Fisika, subject_id=19
$stmt = $db->prepare('
    SELECT COALESCE(g.score, NULL) AS score, 
           COALESCE(g.notes, NULL) AS notes,
           su.full_name AS student_name, st.nis
    FROM classroom_students cs
    INNER JOIN students st ON st.id = cs.student_id
    INNER JOIN users su ON su.id = st.user_id
    LEFT JOIN grades g ON g.student_id = cs.student_id
      AND g.teacher_id = :teacher_id
      AND g.classroom_id = :classroom_id
      AND g.grade_type = :grade_type
      AND g.title = :title
      AND g.subject_id = :subject_id
    WHERE cs.classroom_id = :classroom_id2
    ORDER BY su.full_name ASC
');
$stmt->execute([
    'teacher_id' => 19,
    'classroom_id' => 5,
    'grade_type' => 'tugas',
    'title' => 'Nilai Akhir Fisika',
    'subject_id' => 19,
    'classroom_id2' => 5,
]);
$results = $stmt->fetchAll();

echo "Total students: " . count($results) . "\n";
echo str_pad("No", 4) . str_pad("Nama", 25) . str_pad("NIS", 12) . str_pad("Nilai", 10) . "Catatan\n";
echo str_repeat("-", 65) . "\n";
foreach ($results as $i => $r) {
    echo str_pad((string)($i+1), 4) 
       . str_pad($r['student_name'], 25) 
       . str_pad($r['nis'], 12) 
       . str_pad($r['score'] ?? '-', 10) 
       . ($r['notes'] ?? '') . "\n";
}
