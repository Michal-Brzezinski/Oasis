<?php

require_once 'DashboardController.php';
require_once __DIR__ . '/../repository/SensorsRepository.php';

class SensorsController extends DashboardController
{
    private $sensorsRepository;

    public function __construct()
    {
        parent::__construct();
        // $this->sensorsRepository = new SensorsRepository();
    }

    /**
     * Lista wszystkich czujników
     */
    public function index()
    {
        $sensors = $this->getAllSensors();

        $this->renderDashboard('dashboard/sensors/index', [
            'pageTitle' => 'Czujniki',
            'activeTab' => 'sensors',
            'sensors' => $sensors
        ]);
    }

    /**
     * Formularz dodawania nowego czujnika
     */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleAddSensor();
            return;
        }

        $this->renderDashboard('dashboard/sensors/add', [
            'pageTitle' => 'Dodaj czujnik',
            'activeTab' => 'sensors'
        ]);
    }

    /**
     * Edycja czujnika
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /dashboard/sensors');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEditSensor($id);
            return;
        }

        $sensor = $this->getSensorById($id);

        $this->renderDashboard('dashboard/sensors/edit', [
            'pageTitle' => 'Edytuj czujnik',
            'activeTab' => 'sensors',
            'sensor' => $sensor
        ]);
    }

    /**
     * Usuwanie czujnika
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /dashboard/sensors');
            exit();
        }

        $id = $_POST['id'] ?? null;

        if ($id) {
            // TODO: $this->sensorsRepository->delete($id);
        }

        header('Location: /dashboard/sensors');
        exit();
    }

    /**
     * API - dane z czujnika (JSON)
     */
    public function data()
    {
        $id = $_GET['id'] ?? null;

        header('Content-Type: application/json');

        if (!$id) {
            echo json_encode(['error' => 'Sensor ID required']);
            return;
        }

        // TODO: Pobierz dane z czujnika
        echo json_encode([
            'id' => $id,
            'temperature' => 22.5,
            'humidity' => 65,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    // Metody pomocnicze

    private function getAllSensors()
    {
        // TODO: return $this->sensorsRepository->getAll();
        return [];
    }

    private function getSensorById($id)
    {
        // TODO: return $this->sensorsRepository->getById($id);
        return [];
    }

    private function handleAddSensor()
    {
        // TODO: Walidacja i zapis do bazy
        // $this->sensorsRepository->create($_POST);

        header('Location: /dashboard/sensors');
        exit();
    }

    private function handleEditSensor($id)
    {
        // TODO: Walidacja i aktualizacja w bazie
        // $this->sensorsRepository->update($id, $_POST);

        header('Location: /dashboard/sensors');
        exit();
    }

    // W SensorsController.php
    public function activate()
    {
        $id = $_POST['id'] ?? null;

        if ($id) {
            // TODO: Aktywuj czujnik
        }

        header('Location: /dashboard/sensors');
        exit();
    }

    public function getReadings()
    {
        $id = $_GET['id'] ?? null;

        // TODO: Pobierz odczyty

        $this->json([
            'success' => true,
            'data' => []
        ]);
    }
}
