<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class DashboardController extends AppController
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    /**
     * Główny index dashboardu - przekierowanie do panelu
     */
    public function index()
    {
        header('Location: /dashboard/panel');
        exit();
    }

    /**
     * Renderuje główny layout dashboardu z przekazanym widokiem
     * Metoda pomocnicza dla wszystkich kontrolerów dashboard
     */
    protected function renderDashboard(string $view, array $variables = [])
    {
        // Pobierz dane użytkownika
        $user = $this->getUserData();

        // Dodaj dane użytkownika do zmiennych
        $variables['user'] = $user;

        // Renderuj widok z layoutem dashboardu
        $this->render('dashboard/layout', array_merge($variables, [
            'contentView' => $view,
        ]));
    }

    /**
     * Pobiera dane zalogowanego użytkownika
     */
    private function getUserData()
    {
        // TODO: Implementacja pobierania danych z sesji
        return [
            'id' => 1,
            'name' => 'Jan Kowalski',
            'email' => 'jan@example.com'
        ];
    }

    /**
     * API endpoint dla wyszukiwania (może zostać tutaj lub przenieść do API controller)
     */
    public function search()
    {
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType !== 'application/json') {
            http_response_code(400);
            echo json_encode(['error' => 'Content-Type must be application/json']);
            return;
        }

        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);

        $searchQuery = $decoded['search'] ?? '';

        // TODO: Implementacja wyszukiwania

        header('Content-Type: application/json');
        echo json_encode(['results' => []]);
    }
}
