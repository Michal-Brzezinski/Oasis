<?php

require_once 'src/controllers/AppController.php';
require_once 'src/repository/SensorRepository.php';
require_once 'src/repository/RegionRepository.php';

class SensorsController extends AppController
{
    private SensorRepository $sensorRepository;
    private RegionRepository $regionRepository;

    public function __construct()
    {
        $this->sensorRepository = new SensorRepository();
        $this->regionRepository = new RegionRepository();
    }

    public function index(): void
    {
        $this->requireLogin();

        $userId = $this->getCurrentUserId();
        $regions = $this->regionRepository->getRegionsByOwner($userId);

        $selectedRegionId = null;

        if (isset($_GET['region'])) {
            $selectedRegionId = (int) $_GET['region'];
        } elseif (!empty($regions)) {
            $selectedRegionId = $regions[0]->getId();
        }

        $sensors = $selectedRegionId !== null
            ? $this->sensorRepository->getSensorsByRegion($selectedRegionId)
            : [];

        $this->render('dashboard/sensors/index', [
            'regions' => $regions,
            'selectedRegionId' => $selectedRegionId,
            'sensors' => $sensors
        ]);
    }


    public function add(): void
    {
        $this->requireLogin();

        if ($this->isGet()) {
            $regions = $this->regionRepository->getRegionsByOwner($this->getCurrentUserId());
            $this->render('dashboard/sensors/add', ['regions' => $regions]);
            return;
        }

        if ($this->isPost()) {
            $regionId = (int)$_POST['region_id'];
            $name = trim($_POST['name']);
            $type = trim($_POST['type']);

            $this->sensorRepository->createSensor($regionId, $name, $type);

            $this->redirect('/dashboard/sensors?region=' . $regionId);
        }
    }

    public function edit(): void
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

        if ($this->isGet()) {
            $this->render('dashboard/sensors/edit', [
                'sensor' => $sensor
            ]);
            return;
        }

        if ($this->isPost()) {
            // Pobranie i oczyszczenie danych z formularza
            $name = trim($_POST['name']);
            $type = trim($_POST['type']);

            // Checkbox zawsze konwertujemy na boolean
            $isActive = isset($_POST['is_active']) ? true : false;

            // Aktualizacja sensora w bazie
            $this->sensorRepository->updateSensor($sensorId, $name, $type, $isActive);

            // Przekierowanie z powrotem do listy sensorów dla regionu
            $this->redirect('/dashboard/sensors?region=' . $sensor->getRegionId());
        }
    }



    public function delete(): void
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

        $this->sensorRepository->deleteSensor($sensorId);

        $this->redirect('/dashboard/sensors?region=' . $sensor->getRegionId());
    }
}
