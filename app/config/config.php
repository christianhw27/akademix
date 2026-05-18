<?php

return [
    'app' => [
        'name' => 'AKADEMIX',
        'base_url' => (function () {
            if (PHP_SAPI === 'cli') {
                return 'http://localhost/akademix/public';
            }
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
            $base = rtrim($scriptDir, '/');
            return $scheme . '://' . $host . $base;
        })(),
        'timezone' => 'Asia/Jakarta',
    ],
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: '3306',
        'database' => getenv('DB_NAME') ?: 'akademix',
        'username' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
];
