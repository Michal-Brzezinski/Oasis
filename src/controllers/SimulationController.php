<?php

require_once 'src/controllers/AppController.php';
require_once 'src/repository/SensorRepository.php';
require_once 'src/repository/NotificationRepository.php';
require_once 'src/repository/RegionRepository.php';
require_once 'src/repository/ScheduleRepository.php';
require_once 'src/repository/WateringRepository.php';
require_once 'src/repository/CamerasRepository.php';
require_once 'src/services/CronService.php';

class SimulationController extends AppController
{
    private SensorRepository $sensorRepository;
    private NotificationRepository $notificationRepository;
    private RegionRepository $regionRepository;
    private ScheduleRepository $scheduleRepository;
    private WateringRepository $wateringRepository;
    private CamerasRepository $cameraRepository;

    public function __construct()
    {
        $this->sensorRepository = new SensorRepository();
        $this->notificationRepository = new NotificationRepository();
        $this->regionRepository = new RegionRepository();
        $this->scheduleRepository = new ScheduleRepository();
        $this->wateringRepository = new WateringRepository();
        $this->cameraRepository = new CamerasRepository();
    }

    public function run(): void
    {
        if (isset($_GET['manual'])) {
            $_SESSION['manual_simulation'] = true;
        } else {
            unset($_SESSION['manual_simulation']);
        }
        $this->simulateCameras();
        $this->simulateSchedules();
        // Brak requireLogin – to może być wywoływane przez CRON / backend
        $sensors = $this->sensorRepository->getAllSensors();

        foreach ($sensors as $sensor) {
            $this->simulateSensor($sensor);
        }

        echo "Simulation OK"; // Wpisując odpowiedni URL pokaże ten komunikat
    }

    private function simulateSensor($sensor): void
    {
        $lastReading = $this->sensorRepository->getLastReading($sensor->getId());
        $currentValue = $lastReading ? $lastReading->getValue() : 50.0; // start np. od 50%

        // Symulacja parowania – spadek o 0.1–0.5
        $delta = rand(1, 5) / 10;
        $newValue = max(0, $currentValue - $delta);

        $this->sensorRepository->addReading($sensor->getId(), $newValue);

        // Jeśli spadło poniżej progu – powiadomienie
        if ($newValue < 30.0) {
            $region = $this->regionRepository->getRegionById($sensor->getRegionId());
            $ownerId = $region->getOwnerId();

            $msg = sprintf(
                'Niska wilgotność w regionie "%s" (czujnik: %s): %.1f%%',
                $region->getName(),
                $sensor->getName(),
                $newValue
            );

            $this->notificationRepository->addNotification($ownerId, $msg);
        }
    }

    private function simulateSchedules(): void
    {
        $cronService = new CronService();
        $now = new DateTime();

        $dueSchedules = $this->scheduleRepository->getDueSchedules();

        foreach ($dueSchedules as $schedule) {

            if (!$schedule->isEnabled()) {
                continue;
            }

            $cron = $schedule->getCronExpression();

            if (!$cronService->isDue($cron, $now)) {
                continue;
            }

            $regionId = $schedule->getRegionId();

            // Start akcji
            $actionId = $this->wateringRepository->startAction(
                $regionId,
                $schedule->getId(),
                null
            );

            // Symulacja wilgotności
            $increase = rand(5, 15);
            $this->sensorRepository->increaseMoistureForRegion($regionId, $increase);

            // Zakończenie akcji natychmiast
            $this->wateringRepository->completeAction(
                $actionId,
                $schedule->getVolumeLiters()
            );

            // Aktualizacja next_run
            $this->scheduleRepository->updateNextRun(
                $schedule->getId(),
                $cron
            );

            if (!empty($_SESSION['manual_simulation'])) {
                $this->addFlash(
                    'success',
                    sprintf(
                        'Harmonogram "%s" został wykonany — podlano region "%d" (%d L).',
                        $schedule->getName(),
                        $regionId,
                        $schedule->getVolumeLiters()
                    )
                );
            }
        }
    }

    private function simulateCameras(): void
    {
        $cameras = $this->cameraRepository->getAllCameras();

        foreach ($cameras as $camera) {
            $url = "https://picsum.photos/seed/" . rand(1, 9999) . "/400/300";

            $this->cameraRepository->updateSnapshot($camera->getId(), $url);
        }
    }
}
