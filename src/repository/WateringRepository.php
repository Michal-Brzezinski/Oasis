<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/WateringAction.php';

class WateringRepository extends Repository
{
    public function getActionsByRegion(int $regionId): array
    {
        $stmt = $this->database->prepare('
            SELECT * FROM watering_actions WHERE region_id = :rid
        ');
        $stmt->bindValue(':rid', $regionId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->mapToAction($r), $rows);
    }

    private function mapToAction(array $row): WateringAction
    {
        return new WateringAction(
            (int)$row['id'],
            (int)$row['region_id'],
            $row['schedule_id'] ? (int)$row['schedule_id'] : null,
            $row['initiated_by'] ? (int)$row['initiated_by'] : null,
            $row['started_at'],
            $row['stopped_at'],
            $row['status'],
            $row['volume_liters'] ? (float)$row['volume_liters'] : null
        );
    }
}
