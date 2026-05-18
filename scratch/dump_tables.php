<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=akademix;charset=utf8mb4', 'root', '');
$res = $pdo->query('SELECT * FROM cohorts')->fetchAll(PDO::FETCH_ASSOC);
echo "Cohorts:\n";
print_r($res);
$res2 = $pdo->query('SELECT * FROM academic_years')->fetchAll(PDO::FETCH_ASSOC);
echo "Academic Years:\n";
print_r($res2);
