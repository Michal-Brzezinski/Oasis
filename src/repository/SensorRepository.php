<?php

require_once __DIR__ . '/../repository/Repository.php';
require_once __DIR__ . '/../models/Sensor.php';
require_once __DIR__ . '/../models/SensorReading.php';

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
        $stmt = $this->database->prepare('
            SELECT * FROM sensors WHERE id = :id LIMIT 1
        ');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToSensor($row) : null;
    }

    public function createSensor(int $regionId, string $name, string $type): void
    {
        $stmt = $this->database->prepare('
            INSERT INTO sensors (region_id, name, type)
            VALUES (:region_id, :name, :type)
        ');

        $stmt->execute([
            ':region_id' => $regionId,
            ':name' => $name,
            ':type' => $type
        ]);
    }

    public function updateSensor(int $id, string $name, string $type, bool $isActive): void
    {
        $stmt = $this->database->prepare('
        UPDATE sensors
        SET name = :name,
            type = :type,
            is_active = :active,
            updated_at = NOW()
        WHERE id = :id
    ');

        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':active', $isActive, PDO::PARAM_BOOL); // <-- boolean
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }


    public function deleteSensor(int $id): void
    {
        $stmt = $this->database->prepare('DELETE FROM sensors WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
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

    public function getAllSensors(): array
    {
        $stmt = $this->database->prepare('SELECT * FROM sensors');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sensors = [];
        foreach ($rows as $row) {
            $sensors[] = $this->mapToSensor($row);
        }
        return $sensors;
    }
    public function addReading(int $sensorId, float $value): void
    {
        $stmt = $this->database->prepare(' INSERT INTO latest_sensor_readings (sensor_id, value) VALUES (:sensor_id, :value) ');
        $stmt->execute([':sensor_id' => $sensorId, ':value' => $value]);
    }
    public function getLastReading(int $sensorId): ?SensorReading
    {
        $stmt = $this->database->prepare(' SELECT * FROM latest_sensor_readings WHERE sensor_id = :sensor_id ORDER BY created_at DESC LIMIT 1 ');
        $stmt->bindValue(':sensor_id', $sensorId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new SensorReading((int)$row['id'], (int)$row['sensor_id'], (float)$row['value'], $row['created_at']);
    }

    public function getLastReadings(int $sensorId, int $limit = 50): array
    {
        $stmt = $this->database->prepare('
        SELECT value, created_at
        FROM latest_sensor_readings
        WHERE sensor_id = :id
        ORDER BY created_at DESC
        LIMIT :limit
    ');
        $stmt->bindValue(':id', $sensorId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastReadingsForAllSensors(int $limit = 50): array
    {
        $stmt = $this->database->prepare('
        SELECT sensor_id, value, created_at
        FROM latest_sensor_readings
        ORDER BY created_at DESC
        LIMIT :limit
    ');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function increaseMoistureForRegion(int $regionId, float $amount): void
    {
        $stmt = $this->database->prepare("
        SELECT id FROM sensors WHERE region_id = :region
    ");
        $stmt->execute([':region' => $regionId]);

        $sensors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($sensors as $s) {
            $last = $this->getLastReading($s['id']);
            $newValue = min(100, ($last ? $last->getValue() : 50) + $amount);
            $this->addReading($s['id'], $newValue);
        }
    }
}
