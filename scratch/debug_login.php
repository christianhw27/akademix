<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=akademix;charset=utf8mb4', 'root', '');

// Test 1: Cek apakah user ada
$stmt = $pdo->prepare("SELECT id, username, email, password_hash, role, is_active FROM users WHERE username = 'siswa.101' OR email = 'siswa101@akademix.test' LIMIT 3");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "=== Cek User di DB ===\n";
foreach ($users as $u) {
    echo "ID: {$u['id']}, Username: {$u['username']}, Email: {$u['email']}, Role: {$u['role']}, Active: {$u['is_active']}\n";
    echo "  Hash: {$u['password_hash']}\n";
    echo "  password_verify('password', hash) = " . (password_verify('password', $u['password_hash']) ? 'TRUE' : 'FALSE') . "\n\n";
}

// Test 2: Cek hash yang digunakan
$testHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lC/.og/at2.uheWG/igiK';
echo "=== Cek Hash ===\n";
echo "Hash valid untuk 'password': " . (password_verify('password', $testHash) ? 'TRUE' : 'FALSE') . "\n\n";

// Test 3: Total siswa
$total = $pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();
echo "Total user role=student: $total\n";
