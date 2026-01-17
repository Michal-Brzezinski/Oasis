<?php

require_once 'src/controllers/AppController.php';
require_once 'src/repository/RegionRepository.php';
require_once 'src/repository/SensorRepository.php';

class PanelController extends AppController
{
    private RegionRepository $regionRepository;
    private SensorRepository $sensorRepository;

    public function __construct()
    {
        $this->regionRepository = new RegionRepository();
        $this->sensorRepository = new SensorRepository();
    }

    public function index(): void
    {
        $this->requireLogin();

        $userId = $this->getCurrentUserId();
        $regions = $this->regionRepository->getRegionsByOwner($userId);

        $selectedRegionId = null;

        // Wybór regionu z GET lub domyślnie pierwszego w tablicy
        if (isset($_GET['region'])) {
            $selectedRegionId = (int) $_GET['region'];
        } elseif (!empty($regions)) {
            $selectedRegionId = $regions[0]->getId();
        }

        // Jeśli nie ma żadnego regionu, przekazujemy pustą listę sensorów
        if ($selectedRegionId === null) {
            $this->render('dashboard/panel/index', [
                'regions' => $regions,
                'sensors' => []
            ]);
            return;
        }

        $sensors = $this->sensorRepository->getSensorsByRegion($selectedRegionId);

        $this->render('dashboard/panel/index', [
            'regions' => $regions,
            'selectedRegionId' => $selectedRegionId,
            'sensors' => $sensors
        ]);
    }


    public function sensorDetails(): void
    {
        $this->requireLogin();

        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo "Missing sensor ID";
            return;
        }

        $sensorId = (int)$_GET['id'];
        $sensor = $this->sensorRepository->getSensorById($sensorId);

        if (!$sensor) {
            http_response_code(404);
            echo "Sensor not found";
            return;
        }

        $this->render('dashboard/panel/details', [
            'sensor' => $sensor
        ]);
    }

    public function getSensorData()
    {
        $this->requireLogin();

        if (!isset($_GET['id'])) {
            return $this->json(['error' => 'Missing sensor ID'], 400);
        }

        $sensorId = (int)$_GET['id'];
        $sensor = $this->sensorRepository->getSensorById($sensorId);

        if (!$sensor) {
            return $this->json(['error' => 'Sensor not found'], 404);
        }

        // Na razie mock — później pobierzemy z MQTT/API
        $mockData = [
            'sensorId' => $sensorId,
            'value' => rand(20, 80),
            'unit' => 'percent',
            'timestamp' => date('Y-m-d H:i:s')
        ];

        return $this->json($mockData);
    }
}
