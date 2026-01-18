<?php

require_once 'src/controllers/AppController.php';
require_once 'src/repository/SensorRepository.php';
require_once 'src/repository/NotificationRepository.php';
require_once 'src/repository/RegionRepository.php';

class SimulationController extends AppController
{
    private SensorRepository $sensorRepository;
    private NotificationRepository $notificationRepository;
    private RegionRepository $regionRepository;

    public function __construct()
    {
        $this->sensorRepository = new SensorRepository();
        $this->notificationRepository = new NotificationRepository();
        $this->regionRepository = new RegionRepository();
    }

    public function run(): void
    {
        // Brak requireLogin – to może być wywoływane przez CRON / backend
        $sensors = $this->sensorRepository->getAllSensors();

        foreach ($sensors as $sensor) {
            $this->simulateSensor($sensor);
        }

        echo "Simulation OK";
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
}
