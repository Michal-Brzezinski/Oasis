<?php

require_once 'src/controllers/AppController.php';
require_once 'src/repository/WateringRepository.php';
require_once 'src/repository/RegionRepository.php';

class WateringController extends AppController
{
    private WateringRepository $wateringRepository;
    private RegionRepository $regionRepository;

    public function __construct()
    {
        $this->wateringRepository = new WateringRepository();
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

        $actions = $selectedRegionId !== null
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

        if (!isset($_GET['region'])) {
            http_response_code(400);
            echo "Missing region ID";
            return;
        }

        $regionId = (int)$_GET['region'];
        $userId = $this->getCurrentUserId();

        // Start action
        $actionId = $this->wateringRepository->startAction($regionId, null, $userId);

        // MOCK watering
        sleep(2);

        // Complete action with mock liters
        $this->wateringRepository->completeAction($actionId, rand(5, 15));

        $this->redirect('/dashboard/watering?region=' . $regionId);
    }

    public function fail(): void
    {
        $this->requireLogin();

        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo "Missing action ID";
            return;
        }

        $actionId = (int)$_GET['id'];
        $this->wateringRepository->failAction($actionId);

        $this->redirect('/dashboard/watering');
    }
}
