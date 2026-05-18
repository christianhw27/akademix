<?php

class Controller
{
    protected function model(string $name): object
    {
        if (!class_exists($name)) {
            throw new RuntimeException("Model {$name} tidak ditemukan.");
        }

        return new $name();
    }

    protected function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new RuntimeException("View {$view} tidak ditemukan.");
        }

        require __DIR__ . '/../views/layouts/header.php';
        require $viewFile;
        require __DIR__ . '/../views/layouts/footer.php';
    }

    protected function redirect(string $route = ''): void
    {
        header('Location: ' . route_url($route));
        exit;
    }

    protected function back(string $fallback = ''): void
    {
        $target = $_SERVER['HTTP_REFERER'] ?? route_url($fallback);
        header('Location: ' . $target);
        exit;
    }

    protected function request(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
}
