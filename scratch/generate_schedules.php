<?php
$host = '127.0.0.1';
$db   = 'akademix';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

echo "Membersihkan data jadwal, mata pelajaran, dan guru lama...\n";
$pdo->exec("DELETE FROM schedules");
$pdo->exec("DELETE FROM teacher_subjects");
$pdo->exec("DELETE FROM subjects");
$pdo->exec("DELETE FROM teachers WHERE user_id > 11");
$pdo->exec("DELETE FROM users WHERE role = 'teacher' AND id > 11");

$passwordHash = password_hash('password', PASSWORD_BCRYPT);

// Define subjects
$subjectsList = [
    'MAT' => 'Matematika',
    'BIN' => 'Bahasa Indonesia',
    'ING' => 'Bahasa Inggris',
    'FIS' => 'Fisika',
    'KIM' => 'Kimia',
    'BIO' => 'Biologi',
    'SEJ' => 'Sejarah',
    'GEO' => 'Geografi',
    'EKO' => 'Ekonomi',
    'SOS' => 'Sosiologi',
    'PAI' => 'Pendidikan Agama Islam',
    'PKN' => 'Pendidikan Pancasila dan Kewarganegaraan',
    'PJK' => 'Pendidikan Jasmani Olahraga dan Kesehatan',
    'SBD' => 'Seni Budaya',
    'PRA' => 'Prakarya dan Kewirausahaan'
];

$subjectIds = [];
foreach ($subjectsList as $code => $name) {
    $stmt = $pdo->prepare("INSERT INTO subjects (code, name) VALUES (?, ?)");
    $stmt->execute([$code, $name]);
    $subjectIds[$code] = $pdo->lastInsertId();
}

$teacherNames = [
    'MAT' => 'Budi Santoso',
    'BIN' => 'Siti Aminah',
    'ING' => 'Ratna Sari',
    'FIS' => 'Hendra Wijaya',
    'KIM' => 'Ahmad Dahlan',
    'BIO' => 'Nurul Hidayati',
    'SEJ' => 'Joko Purwanto',
    'GEO' => 'Agus Setiawan',
    'EKO' => 'Dewi Lestari',
    'SOS' => 'Dwi Yulianti',
    'PAI' => 'Abdul Malik',
    'PKN' => 'Tri Hastuti',
    'PJK' => 'Rahmat Hidayat',
    'SBD' => 'Maya Indah',
    'PRA' => 'Andi Saputra'
];

$teacherIds = [];
$teacherCount = 100;
foreach ($teacherNames as $code => $fullName) {
    $teacherCount++;
    $username = 'guru.' . strtolower(explode(' ', $fullName)[0]) . $teacherCount;
    $email = strtolower(explode(' ', $fullName)[0]) . $teacherCount . '@akademix.test';
    $nip = '1980' . str_pad($teacherCount, 6, '0', STR_PAD_LEFT);
    
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role, full_name, email, is_active) VALUES (?, ?, 'teacher', ?, ?, 1)");
    $stmt->execute([$username, $passwordHash, $fullName, $email]);
    $userId = $pdo->lastInsertId();
    
    $stmt = $pdo->prepare("INSERT INTO teachers (user_id, nip, phone) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $nip, '0812' . rand(10000000, 99999999)]);
    $teacherId = $pdo->lastInsertId();
    
    $teacherIds[$code] = $teacherId;
    
    // Assign subject to teacher
    $stmt = $pdo->prepare("INSERT INTO teacher_subjects (teacher_id, subject_id) VALUES (?, ?)");
    $stmt->execute([$teacherId, $subjectIds[$code]]);
}

$activeYearId = $pdo->query("SELECT id FROM academic_years WHERE is_active = 1 LIMIT 1")->fetchColumn();
$classroomsRaw = $pdo->query("SELECT id, class_id, grade_level FROM classrooms WHERE academic_year_id = $activeYearId")->fetchAll();

$days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
$timeSlots = [
    ['07:00:00', '08:30:00'],
    ['08:30:00', '10:00:00'],
    ['10:30:00', '12:00:00'],
    ['12:30:00', '14:00:00'],
];

$subjectCodes = array_keys($subjectsList);

echo "Membuat jadwal untuk " . count($classroomsRaw) . " kelas aktif...\n";
$scheduleCount = 0;
foreach ($classroomsRaw as $cr) {
    $classroomId = $cr['id'];
    
    foreach ($days as $day) {
        foreach ($timeSlots as $slot) {
            $randomSubjectCode = $subjectCodes[array_rand($subjectCodes)];
            
            $stmt = $pdo->prepare("INSERT INTO schedules (classroom_id, subject_id, teacher_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $classroomId,
                $subjectIds[$randomSubjectCode],
                $teacherIds[$randomSubjectCode],
                $day,
                $slot[0],
                $slot[1]
            ]);
            $scheduleCount++;
        }
    }
}

echo "Berhasil! Dibuat: " . count($subjectIds) . " mapel, " . count($teacherIds) . " guru, $scheduleCount jadwal pelajaran.\n";
