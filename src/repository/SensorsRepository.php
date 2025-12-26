<?php

require_once 'Repository.php';

class SensorsRepository extends Repository
{

    private function __construct() {}

    public function getAllSensors()
    {
        $stmt = $this->database->connect()->prepare('
            SELECT id, name, status, can_calibrate
            FROM sensors
        ');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
