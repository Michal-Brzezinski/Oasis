<?php
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/PanelController.php';
require_once 'src/controllers/SchedulesController.php';
require_once 'src/controllers/CamerasController.php';
require_once 'src/controllers/SettingsController.php';

class Routing
{
    private static $instance = null;
    private $routes = [];

    private function __construct()
    {
        $this->routes = [
            // ===== AUTORYZACJA =====
            'login' => [
                'controller' => 'SecurityController',
                'action' => 'login'
            ],
            'register' => [
                'controller' => 'SecurityController',
                'action' => 'register'
            ],
            'logout' => [
                'controller' => 'SecurityController',
                'action' => 'logout'
            ],

            // ===== DASHBOARD - GŁÓWNY =====
            'dashboard' => [
                'controller' => 'DashboardController',
                'action' => 'index'
            ],

            // ===== PANEL (STRONA GŁÓWNA) =====
            'dashboard/panel' => [
                'controller' => 'PanelController',
                'action' => 'index'
            ],
            'dashboard/panel/getSensorData' => [
                'controller' => 'PanelController',
                'action' => 'getSensorData'
            ],
            'dashboard/panel/sensorDetails' => [
                'controller' => 'PanelController',
                'action' => 'sensorDetails'
            ],

            // ===== PODLEWANIE (SCHEDULES) =====
            'dashboard/schedules' => [
                'controller' => 'SchedulesController',
                'action' => 'index'
            ],
            'dashboard/schedules/add' => [
                'controller' => 'SchedulesController',
                'action' => 'add'
            ],
            'dashboard/schedules/edit' => [
                'controller' => 'SchedulesController',
                'action' => 'edit'
            ],
            'dashboard/schedules/delete' => [
                'controller' => 'SchedulesController',
                'action' => 'delete'
            ],
            'dashboard/schedules/toggle' => [
                'controller' => 'SchedulesController',
                'action' => 'toggle'
            ],
            'dashboard/schedules/startManual' => [
                'controller' => 'SchedulesController',
                'action' => 'startManual'
            ],
            'dashboard/schedules/stopManual' => [
                'controller' => 'SchedulesController',
                'action' => 'stopManual'
            ],

            // ===== KAMERY =====
            'dashboard/cameras' => [
                'controller' => 'CamerasController',
                'action' => 'index'
            ],
            'dashboard/cameras/view' => [
                'controller' => 'CamerasController',
                'action' => 'view'
            ],
            'dashboard/cameras/manage' => [
                'controller' => 'CamerasController',
                'action' => 'manage'
            ],
            'dashboard/cameras/add' => [
                'controller' => 'CamerasController',
                'action' => 'add'
            ],
            'dashboard/cameras/edit' => [
                'controller' => 'CamerasController',
                'action' => 'edit'
            ],
            'dashboard/cameras/delete' => [
                'controller' => 'CamerasController',
                'action' => 'delete'
            ],
            'dashboard/cameras/snapshot' => [
                'controller' => 'CamerasController',
                'action' => 'snapshot'
            ],
            'dashboard/cameras/control' => [
                'controller' => 'CamerasController',
                'action' => 'control'
            ],
            'dashboard/cameras/stream' => [
                'controller' => 'CamerasController',
                'action' => 'stream'
            ],

            // ===== USTAWIENIA =====
            'dashboard/settings' => [
                'controller' => 'SettingsController',
                'action' => 'index'
            ],
            'dashboard/settings/update-profile' => [
                'controller' => 'SettingsController',
                'action' => 'updateProfile'
            ],
            'dashboard/settings/change-password' => [
                'controller' => 'SettingsController',
                'action' => 'changePassword'
            ],
            'dashboard/settings/update-notifications' => [
                'controller' => 'SettingsController',
                'action' => 'updateNotifications'
            ],
            'dashboard/settings/calibrateSensor' => [
                'controller' => 'SettingsController',
                'action' => 'calibrateSensor'
            ],
            'dashboard/settings/addSensor' => [
                'controller' => 'SettingsController',
                'action' => 'addSensor'
            ],
            'dashboard/settings/toggleDevice' => [
                'controller' => 'SettingsController',
                'action' => 'toggleDevice'
            ],
            'dashboard/settings/change-language' => [
                'controller' => 'SettingsController',
                'action' => 'changeLanguage'
            ],

            // ===== LEGACY (stare endpointy - można usunąć) =====
            'search-cards' => [
                'controller' => 'DashboardController',
                'action' => 'search'
            ]
        ];
    }

    public static function getInstance(): Routing
    {
        if (self::$instance === null) {
            self::$instance = new Routing();
        }
        return self::$instance;
    }

    public function run(string $path)
    {
        // Usuń trailing slash
        $path = rtrim($path, '/');

        if (array_key_exists($path, $this->routes)) {
            $controller = $this->routes[$path]['controller'];
            $action = $this->routes[$path]['action'];

            $controllerObj = new $controller();
            $controllerObj->$action();
        } else {
            http_response_code(404);
            include 'public/views/404.html';
        }
    }
}
