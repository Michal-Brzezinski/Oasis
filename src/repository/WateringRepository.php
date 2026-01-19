<?php

require_once __DIR__ . '/../repository/Repository.php';
require_once __DIR__ . '/../models/WateringAction.php';

class WateringRepository extends Repository
{
    public function getActionsByRegion(int $regionId): array
    {
        $stmt = $this->database->prepare('
            SELECT * FROM watering_actions WHERE region_id = :rid ORDER BY started_at DESC
        ');
        $stmt->bindValue(':rid', $regionId, PDO::PARAM_INT);
        $stmt->execute();

        return array_map(fn($r) => $this->mapToAction($r), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getActionById(int $id): ?WateringAction
    {
        $stmt = $this->database->prepare('
            SELECT * FROM watering_actions WHERE id = :id LIMIT 1
        ');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToAction($row) : null;
    }

    public function startAction(int $regionId, ?int $scheduleId, ?int $userId): int
    {
        $stmt = $this->database->prepare('
            INSERT INTO watering_actions (region_id, schedule_id, initiated_by, status)
            VALUES (:region_id, :schedule_id, :user_id, \'RUNNING\')
            RETURNING id
        ');

        $stmt->execute([
            ':region_id' => $regionId,
            ':schedule_id' => $scheduleId,
            ':user_id' => $userId
        ]);

        return (int)$stmt->fetchColumn();
    }


    public function completeAction(int $id, float $liters): void
    {
        $stmt = $this->database->prepare('
            UPDATE watering_actions
            SET status = \'COMPLETED\',
                stopped_at = NOW(),
                volume_liters = :liters
            WHERE id = :id
        ');

        $stmt->execute([
            ':liters' => $liters,
            ':id' => $id
        ]);
    }


    public function completeActionAuto(int $id, float $flowLitersPerSecond = 1.0): void
    {
        $action = $this->getActionById($id);
        if (!$action) return;

        $start = new DateTime($action->getStartedAt());
        $now = new DateTime();

        $seconds = max(1, $now->getTimestamp() - $start->getTimestamp());
        $liters = $seconds * $flowLitersPerSecond;

        $stmt = $this->database->prepare('
            UPDATE watering_actions
            SET status = \'COMPLETED\',
                stopped_at = NOW(),
                volume_liters = :liters
            WHERE id = :id
        ');

        $stmt->execute([
            ':liters' => $liters,
            ':id' => $id
        ]);
    }

    public function failAction(int $id): void
    {
        $stmt = $this->database->prepare('
            UPDATE watering_actions
            SET status = \'FAILED\',
                stopped_at = NOW()
            WHERE id = :id
        ');
        $stmt->execute([':id' => $id]);
    }

    public function deleteAction(int $id): void
    {
        $stmt = $this->database->prepare('DELETE FROM watering_actions WHERE id = :id');
        $stmt->execute([':id' => $id]);
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
