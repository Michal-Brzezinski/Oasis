<?php

require_once 'src/controllers/AppController.php';
require_once 'src/repository/WateringRepository.php';
require_once 'src/repository/RegionRepository.php';
require_once 'src/repository/SensorRepository.php';

class WateringController extends AppController
{
    private WateringRepository $wateringRepository;
    private RegionRepository $regionRepository;
    private SensorRepository $sensorRepository;

    public function __construct()
    {
        $this->wateringRepository = new WateringRepository();
        $this->regionRepository = new RegionRepository();
        $this->sensorRepository = new SensorRepository();
    }

    public function index(): void
    {
        $this->requireLogin();

        $userId = $this->getCurrentUserId();
        $regions = $this->regionRepository->getRegionsByOwner($userId);

        $selectedRegionId = $_GET['region'] ?? ($regions[0]->getId() ?? null);

        $actions = $selectedRegionId
            ? $this->wateringRepository->getActionsByRegion($selectedRegionId)
            : [];

        $this->render('dashboard/watering/index', [
            'regions' => $regions,
            'selectedRegionId' => $selectedRegionId,
            'actions' => $actions
        ]);
    }

    public function start(): void
    {
        $this->requireLogin();

        if (!isset($_POST['region'])) {
            $this->addFlash('error', 'Brak regionu.');
            $this->redirect('/dashboard/watering');
            return;
        }

        $regionId = (int)$_POST['region'];
        $userId = $this->getCurrentUserId();

        $region = $this->regionRepository->getRegionById($regionId);
        if (!$region || $region->getOwnerId() !== $userId) {
            $this->addFlash('error', 'Nieprawidłowy region.');
            $this->redirect('/dashboard/watering');
            return;
        }

        $this->wateringRepository->startAction($regionId, null, $userId);

        $this->addFlash('success', 'Podlewanie zostało uruchomione.');
        $this->redirect('/dashboard/watering?region=' . $regionId);
    }

    public function stop(): void
    {
        $this->requireLogin();

        if (!isset($_GET['id'])) {
            $this->addFlash('error', 'Brak ID akcji.');
            $this->redirect('/dashboard/watering');
            return;
        }

        $id = (int)$_GET['id'];

        $action = $this->wateringRepository->getActionById($id);
        if (!$action) {
            $this->addFlash('error', 'Akcja nie istnieje.');
            $this->redirect('/dashboard/watering');
            return;
        }

        $regionId = $action->getRegionId();

        $this->wateringRepository->completeActionAuto($id);

        $this->sensorRepository->increaseMoistureForRegion($regionId, rand(5, 15));

        $this->addFlash('success', 'Podlewanie zostało zatrzymane.');
        $this->redirect('/dashboard/watering?region=' . $regionId);
    }

    public function delete(): void
    {
        $this->requireLogin();

        if (!isset($_GET['id'])) {
            $this->addFlash('error', 'Brak ID akcji.');
            $this->redirect('/dashboard/watering');
            return;
        }

        $id = (int)$_GET['id'];

        $this->wateringRepository->deleteAction($id);

        $this->addFlash('success', 'Akcja została usunięta.');
        $this->redirect('/dashboard/watering');
    }
}
