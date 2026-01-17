<?php

require_once __DIR__ . '/../repository/Repository.php';
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
}
