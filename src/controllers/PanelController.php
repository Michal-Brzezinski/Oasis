<?php

require_once 'DashboardController.php';
require_once __DIR__ . '/../repository/SensorsRepository.php';

class PanelController extends DashboardController
{
    private $sensorsRepository;

    public function __construct()
    {
        parent::__construct();
        // $this->sensorsRepository = new SensorsRepository();
    }

    /**
     * Strona główna - Dashboard z kartami czujników
     */
    public function index()
    {
        $sensorsData = $this->getSensorsData();

        $this->renderDashboard('dashboard/panel/index', [
            'pageTitle' => 'Dashboard',
            'activeTab' => 'panel',
            'sensorsData' => $sensorsData
        ]);
    }

    /**
     * API endpoint - pobiera aktualne dane z czujników (AJAX)
     */
    public function getSensorData()
    {
        header('Content-Type: application/json');

        $sensorId = $_GET['id'] ?? null;

        if (!$sensorId) {
            echo json_encode(['error' => 'Sensor ID required']);
            return;
        }

        // TODO: Pobierz rzeczywiste dane z bazy
        $data = [
            'temperature' => round(20 + rand(0, 80) / 10, 1),
            'humidity' => rand(40, 80),
            'pressure' => rand(990, 1030),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        echo json_encode($data);
    }

    /**
     * Szczegóły czujnika - wykres historyczny
     */
    public function sensorDetails()
    {
        $sensorId = $_GET['id'] ?? null;
        $sensorType = $_GET['type'] ?? 'temperature';

        if (!$sensorId) {
            $this->redirect('/dashboard/panel');
            return;
        }

        $sensorData = $this->getSensorById($sensorId);
        $historicalData = $this->getHistoricalData($sensorId, $sensorType);

        $this->renderDashboard('dashboard/panel/details', [
            'pageTitle' => 'Szczegóły czujnika',
            'activeTab' => 'panel',
            'sensor' => $sensorData,
            'historicalData' => $historicalData,
            'sensorType' => $sensorType
        ]);
    }

    // Metody pomocnicze

    private function getSensorsData()
    {
        // TODO: Pobierz z bazy danych
        return [
            'temperature' => [
                'value' => 24,
                'unit' => '°C',
                'icon' => '🌡️',
                'chartData' => $this->generateMockChartData(),
                'color' => 'blue'
            ],
            'humidity' => [
                'value' => 60,
                'unit' => '%',
                'icon' => '💧',
                'chartData' => $this->generateMockChartData(),
                'color' => 'green'
            ],
            'pressure' => [
                'value' => 1012,
                'unit' => 'hPa',
                'icon' => '🌪️',
                'chartData' => $this->generateMockChartData(),
                'color' => 'navy'
            ]
        ];
    }

    private function getSensorById($id)
    {
        // TODO: Implementacja
        return [
            'id' => $id,
            'name' => 'Czujnik #' . $id,
            'type' => 'temperature'
        ];
    }

    private function getHistoricalData($sensorId, $type)
    {
        // TODO: Pobierz z bazy
        $data = [];
        for ($i = 23; $i >= 0; $i--) {
            $data[] = [
                'time' => date('H:i', strtotime("-{$i} hours")),
                'value' => rand(18, 28)
            ];
        }
        return $data;
    }

    private function generateMockChartData()
    {
        $data = [];
        for ($i = 0; $i < 24; $i++) {
            $data[] = rand(40, 100);
        }
        return $data;
    }
}
