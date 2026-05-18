<?php
$host = '127.0.0.1';
$db   = 'akademix';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

echo "Membersihkan data lama...\n";
$pdo->exec("DELETE FROM classroom_students WHERE student_id IN (SELECT id FROM students WHERE user_id > 11)");
$pdo->exec("DELETE FROM students WHERE user_id > 11");
$pdo->exec("DELETE FROM users WHERE id > 11 AND role = 'student'");

$firstNames = ['Agus', 'Budi', 'Candra', 'Dian', 'Eka', 'Fajar', 'Gilang', 'Hendra', 'Intan', 'Joko',
               'Kiki', 'Lestari', 'Maya', 'Nia', 'Oki', 'Putri', 'Qori', 'Rizky', 'Sari', 'Tari',
               'Udin', 'Vina', 'Wawan', 'Yani', 'Zainal', 'Rafi', 'Aditya', 'Ahmad', 'Reza', 'Dimas',
               'Irfan', 'Ayu', 'Rini', 'Siti', 'Nur', 'Dewi', 'Tri', 'Sri', 'Wahyu', 'Naufal',
               'Ilham', 'Dika', 'Rian', 'Fikri', 'Hafiz', 'Rangga', 'Alif', 'Farhan', 'Syifa', 'Nisa',
               'Zahra', 'Aulia', 'Dina', 'Salsabila', 'Kevin', 'Bagas', 'Fauzan', 'Nanda', 'Salma', 'Hana'];
$lastNames  = ['Saputra', 'Pratama', 'Wijaya', 'Kusuma', 'Santoso', 'Hidayat', 'Setiawan', 'Nugroho',
               'Siregar', 'Lestari', 'Sari', 'Purnama', 'Wulandari', 'Rahmawati', 'Putri', 'Susanti',
               'Fauzi', 'Hakim', 'Mahendra', 'Gunawan', 'Wibowo', 'Haryanto', 'Yulianto', 'Ramadhan',
               'Firmansyah', 'Syahputra', 'Maulana', 'Arifin', 'Kurniawan', 'Widodo'];

$cohortsRaw = $pdo->query("SELECT id, year_name FROM cohorts ORDER BY year_name DESC")->fetchAll();
$cohorts = [];
foreach ($cohortsRaw as $i => $c) {
    if ($i == 0) $cohorts[10] = $c['id'];
    if ($i == 1) $cohorts[11] = $c['id'];
    if ($i == 2) $cohorts[12] = $c['id'];
}

$classesRaw = $pdo->query("SELECT id, name FROM classes ORDER BY name")->fetchAll();
$classesByName = [];
foreach ($classesRaw as $c) {
    $classesByName[$c['name']] = $c['id'];
}

$activeYearId = $pdo->query("SELECT id FROM academic_years WHERE is_active = 1 LIMIT 1")->fetchColumn();
$classroomsRaw = $pdo->query("SELECT id, class_id, grade_level FROM classrooms WHERE academic_year_id = $activeYearId")->fetchAll();
$classrooms = [];
foreach ($classroomsRaw as $cr) {
    $classrooms[$cr['grade_level']][$cr['class_id']] = $cr['id'];
}

// This is bcrypt hash for "password"
$passwordHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lC/.og/at2.uheWG/igiK';

$studentCount = 100;
$successCount = 0;

foreach ([10, 11, 12] as $grade) {
    $cohortId = $cohorts[$grade] ?? 1;
    $classNames = ['IPA 1', 'IPA 2', 'IPA 3', 'IPS 1', 'IPS 2'];

    foreach ($classNames as $className) {
        $classId     = $classesByName[$className] ?? null;
        $classroomId = $classrooms[$grade][$classId] ?? null;

        for ($i = 1; $i <= 20; $i++) {
            $studentCount++;

            $firstName = $firstNames[array_rand($firstNames)];
            $lastName  = $lastNames[array_rand($lastNames)];
            $fullName  = $firstName . ' ' . $lastName;
            // Predictable username: siswa.101, siswa.102, ...
            $username  = 'siswa.' . $studentCount;
            // Predictable email: siswa101@akademix.test
            $email     = 'siswa' . $studentCount . '@akademix.test';
            $nis       = '2025' . str_pad($studentCount, 4, '0', STR_PAD_LEFT);
            $gender    = (rand(0, 1) == 0) ? 'L' : 'P';

            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role, full_name, email, is_active) VALUES (?, ?, 'student', ?, ?, 1)");
                $stmt->execute([$username, $passwordHash, $fullName, $email]);
                $studentUserId = $pdo->lastInsertId();

                $stmt = $pdo->prepare("INSERT INTO students (user_id, nis, gender, cohort_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$studentUserId, $nis, $gender, $cohortId]);
                $studentId = $pdo->lastInsertId();

                if ($classroomId) {
                    $stmt = $pdo->prepare("INSERT INTO classroom_students (classroom_id, student_id) VALUES (?, ?)");
                    $stmt->execute([$classroomId, $studentId]);
                }

                $pdo->commit();
                $successCount++;
            } catch (Exception $e) {
                $pdo->rollBack();
                echo "Error pada iterasi $studentCount: " . $e->getMessage() . "\n";
            }
        }
    }
}

echo "Berhasil membuat $successCount siswa!\n";
echo "\nContoh kredensial login siswa:\n";
echo "  Username : siswa.101  | Email: siswa101\@akademix.test | Password: password\n";
echo "  Username : siswa.150  | Email: siswa150\@akademix.test | Password: password\n";
echo "  Username : siswa.200  | Email: siswa200\@akademix.test | Password: password\n";
