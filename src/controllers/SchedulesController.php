<?php

require_once 'src/controllers/AppController.php';
require_once 'src/repository/ScheduleRepository.php';
require_once 'src/repository/RegionRepository.php';

class SchedulesController extends AppController
{
    private ScheduleRepository $scheduleRepository;
    private RegionRepository $regionRepository;

    public function __construct()
    {
        $this->scheduleRepository = new ScheduleRepository();
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

        $schedules = $selectedRegionId !== null
            ? $this->scheduleRepository->getSchedulesByRegion($selectedRegionId)
            : [];

        $this->render('dashboard/schedules/index', [
            'regions' => $regions,
            'selectedRegionId' => $selectedRegionId,
            'schedules' => $schedules
        ]);
    }


    public function add(): void
    {
        $this->requireLogin();

        if ($this->isGet()) {
            $regions = $this->regionRepository->getRegionsByOwner($this->getCurrentUserId());
            $this->render('dashboard/schedules/add', ['regions' => $regions]);
            return;
        }

        if ($this->isPost()) {
            if (!$this->validateCsrfToken()) {
                $this->addFlash('error', 'Nieprawidłowy token bezpieczeństwa.');
                $this->redirect('/dashboard/schedules');
            }

            $regionId = (int)$_POST['region_id'];
            $name = trim($_POST['name']);
            $cron = trim($_POST['cron_expression']);
            $liters = (float)$_POST['volume_liters'];

            $this->scheduleRepository->createSchedule($regionId, $name, $cron, $liters);

            $this->redirect('/dashboard/schedules?region=' . $regionId);
        }
    }

    public function edit(): void
    {
        $this->requireLogin();

        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo "Missing schedule ID";
            return;
        }

        $scheduleId = (int)$_GET['id'];
        $schedule = $this->scheduleRepository->getScheduleById($scheduleId);

        if (!$schedule) {
            http_response_code(404);
            echo "Schedule not found";
            return;
        }

        if ($this->isGet()) {
            $this->render('dashboard/schedules/edit', ['schedule' => $schedule]);
            return;
        }

        if ($this->isPost()) {
            if (!$this->validateCsrfToken()) {
                $this->addFlash('error', 'Nieprawidłowy token bezpieczeństwa.');
                $this->redirect('/dashboard/schedules');
            }

            $name = trim($_POST['name']);
            $cron = trim($_POST['cron_expression']);
            $liters = (float)$_POST['volume_liters'];
            $enabled = isset($_POST['is_enabled']);

            $this->scheduleRepository->updateSchedule($scheduleId, $name, $cron, $liters, $enabled);

            $this->redirect('/dashboard/schedules?region=' . $schedule->getRegionId());
        }
    }

    public function delete(): void
    {
        $this->requireLogin();

        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo "Missing schedule ID";
            return;
        }

        $scheduleId = (int)$_GET['id'];
        $schedule = $this->scheduleRepository->getScheduleById($scheduleId);

        if (!$schedule) {
            http_response_code(404);
            echo "Schedule not found";
            return;
        }

        $this->scheduleRepository->deleteSchedule($scheduleId);

        $this->redirect('/dashboard/schedules?region=' . $schedule->getRegionId());
    }
}
