<?php

class App
{
    public function run(array $routes): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $route = $this->resolveRoute();
        $_SERVER['APP_ROUTE'] = $route;

        $handler = $routes[$method][$route] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo '404 - Halaman tidak ditemukan.';
            return;
        }

        [$controllerName, $action] = $handler;

        if (!class_exists($controllerName)) {
            throw new RuntimeException("Controller {$controllerName} tidak ditemukan.");
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $action)) {
            throw new RuntimeException("Action {$action} tidak ditemukan pada {$controllerName}.");
        }

        $controller->{$action}();
    }

    private function resolveRoute(): string
    {
        if (isset($_GET['route'])) {
            return trim((string) $_GET['route'], '/');
        }

        $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

        if ($scriptDir !== '/' && $scriptDir !== '' && str_starts_with($uriPath, $scriptDir)) {
            $uriPath = substr($uriPath, strlen($scriptDir));
        }

        $uriPath = trim($uriPath, '/');

        if (str_starts_with($uriPath, 'index.php')) {
            $uriPath = trim(substr($uriPath, strlen('index.php')), '/');
        }

        return $uriPath;
    }
}
