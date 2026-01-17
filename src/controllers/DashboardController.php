<?php

require_once 'src/controllers/AppController.php';
require_once 'src/repository/RegionRepository.php';

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

        $this->render('dashboard/layout', [
            'regions' => $regions
        ]);
    }
}
