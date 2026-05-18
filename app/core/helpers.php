<?php

function app_config(?string $key = null, mixed $default = null): mixed
{
    static $config;

    if ($config === null) {
        $config = require __DIR__ . '/../config/config.php';
    }

    if ($key === null) {
        return $config;
    }

    $segments = explode('.', $key);
    $value = $config;

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }

        $value = $value[$segment];
    }

    return $value;
}

function base_url(string $path = ''): string
{
    $base = rtrim(app_config('app.base_url'), '/');
    $path = ltrim($path, '/');

    return $path === '' ? $base : $base . '/' . $path;
}

function route_url(string $route = ''): string
{
    $base = base_url('index.php');

    if ($route === '') {
        return $base;
    }

    // Support "admin/classrooms&view=5" by splitting route from extra params
    $parts = explode('&', $route, 2);
    $url = $base . '?route=' . urlencode(trim($parts[0], '/'));

    if (isset($parts[1])) {
        $url .= '&' . $parts[1];
    }

    return $url;
}

function asset_url(string $path): string
{
    return base_url('assets/' . ltrim($path, '/'));
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['old'][$key] ?? $default;
}

function remember_old_input(array $data): void
{
    $_SESSION['old'] = $data;
}

function clear_old_input(): void
{
    unset($_SESSION['old']);
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }

    $value = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);

    return $value;
}

function current_route(): string
{
    return $_SERVER['APP_ROUTE'] ?? '';
}

function is_active_route(string $route): bool
{
    return current_route() === trim($route, '/');
}

function selected(mixed $value, mixed $expected): string
{
    return (string) $value === (string) $expected ? 'selected' : '';
}

function checked(mixed $value, mixed $expected): string
{
    return (string) $value === (string) $expected ? 'checked' : '';
}

function format_datetime(?string $value, string $format = 'd M Y H:i'): string
{
    if (!$value) {
        return '-';
    }

    return date($format, strtotime($value));
}

function format_date(?string $value, string $format = 'd M Y'): string
{
    return format_datetime($value, $format);
}

function role_label(string $role): string
{
    return match ($role) {
        'admin' => 'Admin / TU',
        'teacher' => 'Guru',
        'student' => 'Siswa',
        'parent' => 'Orang Tua',
        default => ucfirst($role),
    };
}

function attendance_badge_class(string $status): string
{
    return match ($status) {
        'hadir' => 'success',
        'izin' => 'info',
        'sakit' => 'warning',
        default => 'danger',
    };
}

function grade_badge_class(float $score): string
{
    if ($score >= 85) {
        return 'success';
    }

    if ($score >= 70) {
        return 'warning';
    }

    return 'danger';
}

function render_file_preview(?string $filePath, string $label = 'Lihat Lampiran'): string
{
    if (empty($filePath)) {
        return '';
    }

    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $url = e(base_url($filePath));
    $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($ext, $imageExts)) {
        return '<div style="margin-top: 8px;"><a href="' . $url . '" target="_blank" style="display:inline-block; border: 1px solid #e2e8f0; border-radius:6px; overflow:hidden;"><img src="' . $url . '" style="max-width:200px; max-height:150px; display:block; object-fit:contain; background:#f8fafc;" alt="Attachment Preview"></a><div style="font-size:11px; color:#64748b; margin-top:4px;">📎 <a href="' . $url . '" target="_blank" style="color:#2563eb; text-decoration:none;">' . e($label) . '</a></div></div>';
    }

    return '<div style="margin-top: 8px;"><a href="' . $url . '" target="_blank" class="btn small" style="text-decoration:none; display:inline-flex; align-items:center; gap:4px; font-size:11px; padding: 2px 6px;">📎 ' . e($label) . '</a></div>';
}
