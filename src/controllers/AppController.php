<?php

require_once __DIR__ . '/../repository/NotificationRepository.php';

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

        if (!file_exists($viewPath)) {
            die("View not found: {$template}");
        }

        // Jeśli to dashboard – użyj layoutu
        if (str_starts_with($template, 'dashboard/')) {
            $contentView = $viewPath;
            include 'public/views/dashboard/layout.php';
            return;
        }

        // Jeśli to zwykły widok (login, register)
        include $viewPath;
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

    protected function addFlash(string $type, string $message): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash'][] = [
            'type' => $type,
            'message' => $message
        ];
    }

    protected function getFlashes(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $flashes = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flashes;
    }

    protected function generateCsrfToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;

        return $token;
    }

    protected function validateCsrfToken(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_POST['csrf_token'], $_SESSION['csrf_token'])) {
            return false;
        }

        $valid = hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);

        unset($_SESSION['csrf_token']); // token jednorazowy

        return $valid;
    }

    protected function getCurrentUserNotifications(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            return [];
        }

        $notificationRepository = new NotificationRepository();
        return $notificationRepository->getUnreadByUser((int)$_SESSION['user_id']);
    }
}
