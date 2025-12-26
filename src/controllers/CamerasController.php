<?php

require_once 'DashboardController.php';
require_once __DIR__ . '/../repository/CamerasRepository.php';

class CamerasController extends DashboardController
{
    private $camerasRepository;

    public function __construct()
    {
        parent::__construct();
        // $this->camerasRepository = new CamerasRepository();
    }

    /**
     * Strona główna - Lista kamer
     */
    public function index()
    {
        $cameras = $this->getAllCameras();
        $mainCamera = $cameras[0] ?? null;

        $this->renderDashboard('dashboard/cameras/index', [
            'pageTitle' => 'Podgląd na żywo',
            'activeTab' => 'cameras',
            'cameras' => $cameras,
            'mainCamera' => $mainCamera
        ]);
    }

    /**
     * Pojedynczy widok kamery na pełnym ekranie
     */
    public function view()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('/dashboard/cameras');
            return;
        }

        $camera = $this->getCameraById($id);

        $this->renderDashboard('dashboard/cameras/view', [
            'pageTitle' => 'Kamera - ' . $camera['name'],
            'activeTab' => 'cameras',
            'camera' => $camera
        ]);
    }

    /**
     * Zarządzanie kamerami (lista do edycji)
     */
    public function manage()
    {
        $cameras = $this->getAllCameras();

        $this->renderDashboard('dashboard/cameras/manage', [
            'pageTitle' => 'Zarządzanie kamerami',
            'activeTab' => 'cameras',
            'cameras' => $cameras
        ]);
    }

    /**
     * Dodaj nową kamerę
     */
    public function add()
    {
        if ($this->isPost()) {
            $this->handleAddCamera();
            return;
        }

        $this->renderDashboard('dashboard/cameras/add', [
            'pageTitle' => 'Dodaj kamerę',
            'activeTab' => 'cameras'
        ]);
    }

    /**
     * Edytuj kamerę
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('/dashboard/cameras/manage');
            return;
        }

        if ($this->isPost()) {
            $this->handleEditCamera($id);
            return;
        }

        $camera = $this->getCameraById($id);

        $this->renderDashboard('dashboard/cameras/edit', [
            'pageTitle' => 'Edytuj kamerę',
            'activeTab' => 'cameras',
            'camera' => $camera
        ]);
    }

    /**
     * Usuń kamerę
     */
    public function delete()
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $cameraId = $data['camera_id'] ?? null;

        if ($cameraId) {
            // TODO: $this->camerasRepository->delete($cameraId);
        }

        $this->json(['success' => true]);
    }

    /**
     * API - Zrób snapshot z kamery
     */
    public function snapshot()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->json(['error' => 'Camera ID required'], 400);
            return;
        }

        // TODO: Implementacja snapshota
        // $imagePath = $this->camerasRepository->takeSnapshot($id);

        $this->json([
            'success' => true,
            'image_url' => '/uploads/snapshots/camera_' . $id . '_' . time() . '.jpg'
        ]);
    }

    /**
     * API - Kontrola kamery (zoom, pan, tilt)
     */
    public function control()
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $cameraId = $data['camera_id'] ?? null;
        $action = $data['action'] ?? null; // zoom_in, zoom_out, pan_left, pan_right, etc.

        // TODO: Implementacja kontroli kamery

        $this->json(['success' => true]);
    }

    // Metody pomocnicze

    private function getAllCameras()
    {
        // TODO: Pobierz z bazy
        return [
            [
                'id' => 1,
                'name' => 'Grządka #1',
                'location' => 'Ogród przedni',
                'stream_url' => '/public/img/login_img.webp', // Mock
                'is_online' => true,
                'status_color' => 'green'
            ],
            [
                'id' => 2,
                'name' => 'Wejście',
                'location' => 'Wejście główne',
                'stream_url' => '/public/img/login_img.webp', // Mock
                'is_online' => true,
                'status_color' => 'green'
            ],
            [
                'id' => 3,
                'name' => 'Patio',
                'location' => 'Taras',
                'stream_url' => '/public/img/login_img.webp', // Mock
                'is_online' => true,
                'status_color' => 'yellow'
            ],
            [
                'id' => 4,
                'name' => 'Tylny Ogród',
                'location' => 'Ogród tylny',
                'stream_url' => '/public/img/login_img.webp', // Mock
                'is_online' => false,
                'status_color' => 'red'
            ],
            [
                'id' => 5,
                'name' => 'Szklarnia',
                'location' => 'Szklarnia',
                'stream_url' => '/public/img/login_img.webp', // Mock
                'is_online' => false,
                'status_color' => 'red'
            ]
        ];
    }

    private function getCameraById($id)
    {
        $cameras = $this->getAllCameras();
        foreach ($cameras as $camera) {
            if ($camera['id'] == $id) {
                return $camera;
            }
        }
        return null;
    }

    private function handleAddCamera()
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'location' => $_POST['location'] ?? '',
            'stream_url' => $_POST['stream_url'] ?? '',
            'username' => $_POST['username'] ?? '',
            'password' => $_POST['password'] ?? ''
        ];

        // TODO: Walidacja i zapis
        // $this->camerasRepository->create($data);

        $_SESSION['message'] = 'Kamera została dodana';
        $this->redirect('/dashboard/cameras/manage');
    }

    private function handleEditCamera($id)
    {
        // TODO: Walidacja i aktualizacja
        $_SESSION['message'] = 'Kamera została zaktualizowana';
        $this->redirect('/dashboard/cameras/manage');
    }
}
