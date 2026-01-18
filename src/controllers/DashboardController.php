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

        $this->render('dashboard/index', [
            'regions' => $regions,
            'pageTitle' => 'Dashboard'
        ]);
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
