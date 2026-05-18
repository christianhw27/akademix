<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=akademix;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $pdo->exec("ALTER TABLE students DROP FOREIGN KEY fk_students_guardian");
} catch(Exception $e) { echo $e->getMessage() . "\n"; }

try {
    $pdo->exec("ALTER TABLE students DROP COLUMN guardian_id");
} catch(Exception $e) { echo $e->getMessage() . "\n"; }

try {
    $pdo->exec("DROP TABLE guardians");
} catch(Exception $e) { echo $e->getMessage() . "\n"; }

$pdo->exec("DELETE FROM users WHERE role='parent'");

echo "DB Cleanup OK\n";
