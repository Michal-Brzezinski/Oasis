<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Sensor.php';

class SensorRepository extends Repository
{
    public function getSensorsByRegion(int $regionId): array
    {
        $stmt = $this->database->prepare('
            SELECT * FROM sensors WHERE region_id = :rid
        ');
        $stmt->bindValue(':rid', $regionId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->mapToSensor($r), $rows);
    }

    public function getSensorById(int $id): ?Sensor
    {
        $stmt = $this->database->prepare('SELECT * FROM sensors WHERE id = :id LIMIT 1');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToSensor($row) : null;
    }


    private function mapToSensor(array $row): Sensor
    {
        return new Sensor(
            (int)$row['id'],
            (int)$row['region_id'],
            $row['name'],
            $row['type'],
            (bool)$row['is_active'],
            $row['created_at'],
            $row['updated_at']
        );
    }
}
