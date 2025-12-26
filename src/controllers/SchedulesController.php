<?php

require_once 'DashboardController.php';
require_once __DIR__ . '/../repository/SchedulesRepository.php';
require_once __DIR__ . '/../repository/ZonesRepository.php';

class SchedulesController extends DashboardController
{
    private $schedulesRepository;
    private $zonesRepository;

    public function __construct()
    {
        parent::__construct();
        // $this->schedulesRepository = new SchedulesRepository();
        // $this->zonesRepository = new ZonesRepository();
    }

    /**
     * Strona główna - Sterowanie podlewaniem
     */
    public function index()
    {
        $zones = $this->getZones();
        $schedules = $this->getSchedules();
        $manualStatus = $this->getManualWateringStatus();

        $this->renderDashboard('dashboard/schedules/index', [
            'pageTitle' => 'Sterowanie podlewaniem',
            'activeTab' => 'schedules',
            'zones' => $zones,
            'schedules' => $schedules,
            'manualStatus' => $manualStatus
        ]);
    }

    /**
     * API - Rozpocznij ręczne podlewanie
     */
    public function startManual()
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $zoneId = $data['zone_id'] ?? null;

        if (!$zoneId) {
            $this->json(['error' => 'Zone ID required'], 400);
            return;
        }

        // TODO: Implementacja uruchomienia podlewania
        // $this->schedulesRepository->startManualWatering($zoneId);

        $this->json([
            'success' => true,
            'message' => 'Podlewanie rozpoczęte',
            'zone_id' => $zoneId,
            'started_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * API - Zatrzymaj ręczne podlewanie
     */
    public function stopManual()
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $zoneId = $data['zone_id'] ?? null;

        // TODO: Implementacja zatrzymania
        // $this->schedulesRepository->stopManualWatering($zoneId);

        $this->json([
            'success' => true,
            'message' => 'Podlewanie zatrzymane'
        ]);
    }

    /**
     * Dodaj nowy harmonogram
     */
    public function add()
    {
        if ($this->isPost()) {
            $this->handleAddSchedule();
            return;
        }

        $zones = $this->getZones();

        $this->renderDashboard('dashboard/schedules/add', [
            'pageTitle' => 'Nowy harmonogram',
            'activeTab' => 'schedules',
            'zones' => $zones
        ]);
    }

    /**
     * Edytuj harmonogram
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('/dashboard/schedules');
            return;
        }

        if ($this->isPost()) {
            $this->handleEditSchedule($id);
            return;
        }

        $schedule = $this->getScheduleById($id);
        $zones = $this->getZones();

        $this->renderDashboard('dashboard/schedules/edit', [
            'pageTitle' => 'Edytuj harmonogram',
            'activeTab' => 'schedules',
            'schedule' => $schedule,
            'zones' => $zones
        ]);
    }

    /**
     * Przełącz aktywność harmonogramu (toggle)
     */
    public function toggle()
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $scheduleId = $data['schedule_id'] ?? null;

        if (!$scheduleId) {
            $this->json(['error' => 'Schedule ID required'], 400);
            return;
        }

        // TODO: Toggle w bazie
        // $newState = $this->schedulesRepository->toggleActive($scheduleId);

        $this->json([
            'success' => true,
            'active' => true // $newState
        ]);
    }

    /**
     * Usuń harmonogram
     */
    public function delete()
    {
        if (!$this->isPost()) {
            $this->redirect('/dashboard/schedules');
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $scheduleId = $data['schedule_id'] ?? null;

        if ($scheduleId) {
            // TODO: $this->schedulesRepository->delete($scheduleId);
        }

        $this->json(['success' => true]);
    }

    // Metody pomocnicze

    private function getZones()
    {
        // TODO: Pobierz z bazy
        return [
            ['id' => 1, 'name' => 'Strefa 1: Trawnik frontowy'],
            ['id' => 2, 'name' => 'Strefa 2: Ogród warzywny'],
            ['id' => 3, 'name' => 'Strefa 3: Strefa 1 & 3']
        ];
    }

    private function getSchedules()
    {
        // TODO: Pobierz z bazy
        return [
            [
                'id' => 1,
                'name' => 'Poranne podlewanie trawnika',
                'time' => '06:00',
                'duration' => 15,
                'zone' => 'Strefa 1 & 3',
                'frequency' => 'Codziennie',
                'active' => true
            ],
            [
                'id' => 2,
                'name' => 'Wieczorne nawadnianie warzywnika',
                'time' => '21:00',
                'duration' => 20,
                'zone' => 'Strefa 2',
                'frequency' => 'Co drugi dzień',
                'active' => false
            ]
        ];
    }

    private function getScheduleById($id)
    {
        // TODO: Pobierz z bazy
        return [
            'id' => $id,
            'name' => 'Harmonogram #' . $id,
            'zone_id' => 1,
            'time' => '06:00',
            'duration' => 15,
            'days' => ['mon', 'wed', 'fri']
        ];
    }

    private function getManualWateringStatus()
    {
        // TODO: Pobierz aktualny status
        return [
            'is_active' => false,
            'zone_id' => null,
            'started_at' => null
        ];
    }

    private function handleAddSchedule()
    {
        // TODO: Walidacja i zapis
        $data = [
            'name' => $_POST['name'] ?? '',
            'zone_id' => $_POST['zone_id'] ?? null,
            'time' => $_POST['time'] ?? '',
            'duration' => $_POST['duration'] ?? 0,
            'days' => $_POST['days'] ?? []
        ];

        // $this->schedulesRepository->create($data);

        $_SESSION['message'] = 'Harmonogram został dodany';
        $this->redirect('/dashboard/schedules');
    }

    private function handleEditSchedule($id)
    {
        // TODO: Walidacja i aktualizacja
        $_SESSION['message'] = 'Harmonogram został zaktualizowany';
        $this->redirect('/dashboard/schedules');
    }
}
