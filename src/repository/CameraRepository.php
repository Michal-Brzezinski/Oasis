<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Camera.php';

class CameraRepository extends Repository
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

    private function mapToCamera(array $row): Camera
    {
        return new Camera(
            (int)$row['id'],
            (int)$row['region_id'],
            $row['name'],
            $row['stream_url'],
            (bool)$row['is_active'],
            $row['created_at'],
            $row['updated_at']
        );
    }
}
