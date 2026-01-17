<?php


class AppController
{

    private static ?AppController $instance = null;
    private function __construct()
    {
        // np. session_start(); 
    }

    public static function getInstance(): AppController
    {
        if (self::$instance === null) {
            self::$instance = new AppController();
        }
        return self::$instance;
    }

    protected function isGet(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === 'GET';
    }

    protected function isPost(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === 'POST';
    }

    protected function redirect(string $url)
    {
        header("Location: {$url}");
        exit();
    }

    protected function json($data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    // dekorator, który definiuje, jakie metody HTTP są dostępne
    protected function allowMethods(array $methods): bool
    {
        return in_array($_SERVER["REQUEST_METHOD"], $methods);
    }

    protected function render(string $template, array $variables = [])
    {
        extract($variables);

        $viewPath = 'public/views/' . $template . '.php';

        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            // Fallback do .html jeśli .php nie istnieje
            $htmlPath = 'public/views/' . $template . '.html';
            if (file_exists($htmlPath)) {
                include $htmlPath;
            } else {
                die("View not found: {$template}");
            }
        }
    }

    protected function requireLogin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            http_response_code(302);
            header('Location: /login');
            exit();
        }
    }

    protected function getCurrentUserId(): ?int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    }
}
