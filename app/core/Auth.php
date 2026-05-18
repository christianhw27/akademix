<?php

class Auth
{
    public static function attempt(array $user): void
    {
        $_SESSION['auth_user'] = $user;
    }

    public static function user(): ?array
    {
        return $_SESSION['auth_user'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function id(): ?int
    {
        return self::user()['id'] ?? null;
    }

    public static function role(): ?string
    {
        return self::user()['role'] ?? null;
    }

    public static function logout(): void
    {
        unset($_SESSION['auth_user']);
        session_regenerate_id(true);
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            header('Location: ' . route_url('login'));
            exit;
        }
    }

    public static function requireRole(string|array $roles): void
    {
        self::requireLogin();
        $roles = (array) $roles;

        if (!in_array(self::role(), $roles, true)) {
            flash('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            header('Location: ' . route_url('dashboard'));
            exit;
        }
    }
}
