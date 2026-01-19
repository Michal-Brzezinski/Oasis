<?php

require_once __DIR__ . '/../repository/Repository.php';
require_once __DIR__ . '/../models/Camera.php';

class CamerasRepository extends Repository
{
    public function getCamerasByRegion(int $regionId): array
    {
        $stmt = $this->database->prepare('
            SELECT * FROM cameras WHERE region_id = :rid
        ');
        $stmt->bindValue(':rid', $regionId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->mapToCamera($r), $rows);
    }

    public function getCameraById(int $id): ?Camera
    {
        $stmt = $this->database->prepare('
            SELECT * FROM cameras WHERE id = :id LIMIT 1
        ');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToCamera($row) : null;
    }

    public function createCamera(int $regionId, string $name, string $streamUrl): void
    {
        $stmt = $this->database->prepare('
            INSERT INTO cameras (region_id, name, stream_url)
            VALUES (:region_id, :name, :stream_url)
        ');

        $stmt->execute([
            ':region_id' => $regionId,
            ':name' => $name,
            ':stream_url' => $streamUrl
        ]);
    }

    public function updateCamera(int $id, string $name, string $streamUrl, bool $isActive): void
    {
        $stmt = $this->database->prepare('
            UPDATE cameras
            SET name = :name, stream_url = :stream_url, is_active = :active, updated_at = NOW()
            WHERE id = :id
        ');

        $stmt->execute([
            ':name' => $name,
            ':stream_url' => $streamUrl,
            ':active' => $isActive,
            ':id' => $id
        ]);
    }

    public function deleteCamera(int $id): void
    {
        $stmt = $this->database->prepare('DELETE FROM cameras WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    private function mapToCamera(array $row): Camera
    {
        return new Camera(
            (int)$row['id'],
            (int)$row['region_id'],
            $row['name'],
            $row['stream_url'],
            $row['snapshot_url'],
            (bool)$row['is_active'],
            $row['created_at'],
            $row['updated_at']
        );
    }

    public function updateSnapshot(int $id, string $url): void
    {
        $stmt = $this->database->prepare("
        UPDATE cameras SET snapshot_url = :url WHERE id = :id
    ");
        $stmt->execute([
            ':url' => $url,
            ':id' => $id
        ]);
    }

    public function getAllCameras(): array
    {
        $stmt = $this->database->prepare('SELECT * FROM cameras');
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->mapToCamera($r), $rows);
    }
}
