<?php

class Database
{
    private static ?PDO $instance = null;

    public static function instance(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                app_config('db.host'),
                app_config('db.port'),
                app_config('db.database'),
                app_config('db.charset')
            );

            self::$instance = new PDO(
                $dsn,
                app_config('db.username'),
                app_config('db.password'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        }

        return self::$instance;
    }
}
