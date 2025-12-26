<?php

require_once 'DashboardController.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/SensorsRepository.php';

class SettingsController extends DashboardController
{
    private $userRepository;
    private $sensorsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        // $this->sensorsRepository = new SensorsRepository();
    }

    /**
     * Strona główna ustawień
     */
    public function index()
    {
        $user = $this->getCurrentUser();
        $notifications = $this->getNotificationSettings();
        $sensors = $this->getUserSensors();
        $devices = $this->getUserDevices();

        $this->renderDashboard('dashboard/settings/index', [
            'pageTitle' => 'Ustawienia',
            'activeTab' => 'settings',
            'user' => $user,
            'notifications' => $notifications,
            'sensors' => $sensors,
            'devices' => $devices
        ]);
    }

    /**
     * Aktualizuj profil użytkownika
     */
    public function updateProfile()
    {
        if (!$this->isPost()) {
            $this->redirect('/dashboard/settings');
            return;
        }

        $email = $_POST['email'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';

        // TODO: Walidacja email
        // TODO: Jeśli new_password nie jest pusty, zmień hasło

        // $this->userRepository->updateEmail($userId, $email);
        // if ($newPassword) {
        //     $this->userRepository->updatePassword($userId, $newPassword);
        // }

        $_SESSION['message'] = 'Profil zaktualizowany pomyślnie';
        $this->redirect('/dashboard/settings');
    }

    /**
     * Aktualizuj powiadomienia
     */
    public function updateNotifications()
    {
        if (!$this->isPost()) {
            $this->redirect('/dashboard/settings');
            return;
        }

        $pushEnabled = isset($_POST['push_enabled']);
        $emailEnabled = isset($_POST['email_enabled']);

        // TODO: Zapisz w bazie
        // $this->userRepository->updateNotificationSettings([
        //     'push' => $pushEnabled,
        //     'email' => $emailEnabled
        // ]);

        $_SESSION['message'] = 'Ustawienia powiadomień zaktualizowane';
        $this->redirect('/dashboard/settings');
    }

    /**
     * Kalibruj czujnik
     */
    public function calibrateSensor()
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $sensorId = $data['sensor_id'] ?? null;

        if (!$sensorId) {
            $this->json(['error' => 'Sensor ID required'], 400);
            return;
        }

        // TODO: Rozpocznij kalibrację
        // $this->sensorsRepository->startCalibration($sensorId);

        $this->json([
            'success' => true,
            'message' => 'Kalibracja rozpoczęta'
        ]);
    }

    /**
     * Dodaj nowy czujnik
     */
    public function addSensor()
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        // TODO: Walidacja i zapis
        // $this->sensorsRepository->create($data);

        $this->json([
            'success' => true,
            'message' => 'Czujnik dodany'
        ]);
    }

    /**
     * Aktywuj/dezaktywuj urządzenie
     */
    public function toggleDevice()
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $deviceId = $data['device_id'] ?? null;

        // TODO: Toggle status urządzenia

        $this->json(['success' => true]);
    }

    /**
     * Zmień język
     */
    public function changeLanguage()
    {
        if (!$this->isPost()) {
            $this->redirect('/dashboard/settings');
            return;
        }

        $language = $_POST['language'] ?? 'Polski';

        // TODO: Zapisz w sesji lub bazie
        $_SESSION['language'] = $language;

        $_SESSION['message'] = 'Język został zmieniony';
        $this->redirect('/dashboard/settings');
    }

    // Metody pomocnicze

    private function getCurrentUser()
    {
        // TODO: Pobierz z sesji i bazy
        return [
            'id' => 1,
            'email' => 'uzytkownik@example.com',
            'name' => 'Jan Kowalski'
        ];
    }

    private function getNotificationSettings()
    {
        // TODO: Pobierz z bazy
        return [
            'push' => true,
            'email' => false
        ];
    }

    private function getUserSensors()
    {
        // TODO: Pobierz z bazy
        return [
            [
                'id' => 1,
                'name' => 'Czujnik wilgotności #1 (Grządka)',
                'status' => 'Skalibrowany',
                'can_calibrate' => true
            ],
            [
                'id' => 2,
                'name' => 'Czujnik temperatury #1 (Szklarnia)',
                'status' => 'Skalibrowany',
                'can_calibrate' => true
            ],
            [
                'id' => 3,
                'name' => 'Czujnik ciśnienia #1 (System)',
                'status' => 'Skalibrowany',
                'can_calibrate' => false
            ]
        ];
    }

    private function getUserDevices()
    {
        // TODO: Pobierz z bazy
        return [
            [
                'id' => 1,
                'name' => 'Pompa wody #1',
                'is_active' => true
            ],
            [
                'id' => 2,
                'name' => 'Kamera #1 (Grządka)',
                'is_active' => true
            ]
        ];
    }
}
