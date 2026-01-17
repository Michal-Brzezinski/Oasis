<?php

require_once 'src/controllers/AppController.php';
require_once 'src/repository/CamerasRepository.php';
require_once 'src/repository/RegionRepository.php';

class CamerasController extends AppController
{
    private CamerasRepository $cameraRepository;
    private RegionRepository $regionRepository;

    public function __construct()
    {
        $this->cameraRepository = new CamerasRepository();
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

        $cameras = $selectedRegionId !== null
            ? $this->cameraRepository->getCamerasByRegion($selectedRegionId)
            : [];

        $this->render('dashboard/cameras/index', [
            'regions' => $regions,
            'selectedRegionId' => $selectedRegionId,
            'cameras' => $cameras
        ]);
    }


    public function add(): void
    {
        $this->requireLogin();

        if ($this->isGet()) {
            $regions = $this->regionRepository->getRegionsByOwner($this->getCurrentUserId());
            $this->render('dashboard/cameras/add', ['regions' => $regions]);
            return;
        }

        if ($this->isPost()) {
            $regionId = (int)$_POST['region_id'];
            $name = trim($_POST['name']);
            $streamUrl = trim($_POST['stream_url']);

            $this->cameraRepository->createCamera($regionId, $name, $streamUrl);

            $this->redirect('/dashboard/cameras?region=' . $regionId);
        }
    }

    public function edit(): void
    {
        $this->requireLogin();

        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo "Missing camera ID";
            return;
        }

        $cameraId = (int)$_GET['id'];
        $camera = $this->cameraRepository->getCameraById($cameraId);

        if (!$camera) {
            http_response_code(404);
            echo "Camera not found";
            return;
        }

        if ($this->isGet()) {
            $this->render('dashboard/cameras/edit', ['camera' => $camera]);
            return;
        }

        if ($this->isPost()) {
            $name = trim($_POST['name']);
            $streamUrl = trim($_POST['stream_url']);
            $active = isset($_POST['is_active']);

            $this->cameraRepository->updateCamera($cameraId, $name, $streamUrl, $active);

            $this->redirect('/dashboard/cameras?region=' . $camera->getRegionId());
        }
    }

    public function delete(): void
    {
        $this->requireLogin();

        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo "Missing camera ID";
            return;
        }

        $cameraId = (int)$_GET['id'];
        $camera = $this->cameraRepository->getCameraById($cameraId);

        if (!$camera) {
            http_response_code(404);
            echo "Camera not found";
            return;
        }

        $this->cameraRepository->deleteCamera($cameraId);

        $this->redirect('/dashboard/cameras?region=' . $camera->getRegionId());
    }

    public function view(): void
    {
        $this->requireLogin();

        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo "Missing camera ID";
            return;
        }

        $cameraId = (int)$_GET['id'];
        $camera = $this->cameraRepository->getCameraById($cameraId);

        if (!$camera) {
            http_response_code(404);
            echo "Camera not found";
            return;
        }

        $this->render('dashboard/cameras/view', ['camera' => $camera]);
    }
}
