<?php

require_once 'src/controllers/AppController.php';
require_once 'src/repository/RegionRepository.php';
require_once 'src/repository/NotificationRepository.php';

class DashboardController extends AppController
{
    private RegionRepository $regionRepository;

    public function __construct()
    {
        $this->regionRepository = new RegionRepository();
    }

    public function index(): void
    {
        $this->requireLogin();

        $userId = $this->getCurrentUserId();
        $regions = $this->regionRepository->getRegionsByOwner($userId);

        if ($_SESSION['user_role'] === 'ADMIN') {
            $this->render('dashboard/admin/index', [
                'regions' => $regions,
                'pageTitle' => 'Dashboard Admina'
            ]);
        } else {
            $this->render('dashboard/index', [
                'regions' => $regions,
                'pageTitle' => 'Dashboard'
            ]);
        }
    }


    public function admin(): void
    {
        $this->requireLogin();

        if ($_SESSION['user_role'] !== 'ADMIN') {
            http_response_code(403);
            $this->render('403', ['message' => 'Brak dostępu']);
        }

        $users = (new UserRepository())->getUsers();

        $this->render('dashboard/admin', [
            'users' => $users,
            'pageTitle' => 'Zarządzanie użytkownikami'
        ]);
    }

    public function deleteUser(): void
    {
        $this->requireLogin();

        if ($_SESSION['user_role'] !== 'ADMIN') {
            http_response_code(403);
            $this->render('403', ['message' => 'Brak dostępu']);
        }

        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            (new UserRepository())->deleteUser($id);
            $this->addFlash('success', 'Użytkownik został usunięty.');
        }

        header("Location: /dashboard/admin");
        exit();
    }



    public function markNotificationsRead(): void
    {
        $this->requireLogin();

        $userId = $this->getCurrentUserId();

        $repo = new NotificationRepository();

        $repo->markAllAsRead($userId);

        echo json_encode(['success' => true]);
    }
}
