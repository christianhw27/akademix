<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=akademix;charset=utf8mb4', 'root', '');

// Generate hash yang benar untuk "password"
$correctHash = password_hash('password', PASSWORD_BCRYPT);
echo "Hash baru: $correctHash\n";
echo "Verifikasi: " . (password_verify('password', $correctHash) ? 'TRUE' : 'FALSE') . "\n\n";

// Update semua siswa yang hashnya salah
$updated = $pdo->exec("UPDATE users SET password_hash = '$correctHash' WHERE role = 'student' AND id > 11");
echo "Updated $updated akun siswa.\n";

// Verifikasi
$stmt = $pdo->prepare("SELECT password_hash FROM users WHERE username = 'siswa.101'");
$stmt->execute();
$hash = $stmt->fetchColumn();
echo "Verifikasi siswa.101: " . (password_verify('password', $hash) ? 'OK' : 'GAGAL') . "\n";
