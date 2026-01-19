<?php

require_once __DIR__ . '/../repository/Repository.php';
require_once __DIR__ . '/../models/Schedule.php';

class ScheduleRepository extends Repository
{
    public function getSchedulesByRegion(int $regionId): array
    {
        $stmt = $this->database->prepare('
            SELECT * FROM schedules WHERE region_id = :rid
        ');
        $stmt->bindValue(':rid', $regionId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->mapToSchedule($r), $rows);
    }

    public function getScheduleById(int $id): ?Schedule
    {
        $stmt = $this->database->prepare('
            SELECT * FROM schedules WHERE id = :id LIMIT 1
        ');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToSchedule($row) : null;
    }


    public function createSchedule(int $regionId, string $name, string $cron, float $liters): void
    {
        $stmt = $this->database->prepare('
        INSERT INTO schedules (region_id, name, cron_expression, volume_liters, is_enabled, next_run)
        VALUES (:region_id, :name, :cron, :liters, TRUE, NOW())
    ');

        $stmt->execute([
            ':region_id' => $regionId,
            ':name' => $name,
            ':cron' => $cron,
            ':liters' => $liters
        ]);
    }


    public function updateSchedule(int $id, string $name, string $cron, float $liters, bool $enabled): void
    {
        $stmt = $this->database->prepare('
        UPDATE schedules
        SET name = :name,
            cron_expression = :cron,
            volume_liters = :liters,
            is_enabled = :enabled,
            updated_at = NOW()
        WHERE id = :id
    ');

        // Bindujemy wartości z jawnym typem
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':cron', $cron, PDO::PARAM_STR);
        $stmt->bindValue(':liters', $liters, PDO::PARAM_STR); // NUMERIC w Postgres może przyjąć string
        $stmt->bindValue(':enabled', $enabled, PDO::PARAM_BOOL); // boolean poprawnie
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }


    public function deleteSchedule(int $id): void
    {
        $stmt = $this->database->prepare('DELETE FROM schedules WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    private function mapToSchedule(array $row): Schedule
    {
        return new Schedule(
            (int)$row['id'],
            (int)$row['region_id'],
            $row['name'],
            $row['cron_expression'],
            (float)$row['volume_liters'],
            (bool)$row['is_enabled'],
            $row['created_at'],
            $row['next_run'],
            $row['updated_at']
        );
    }

    public function getDueSchedules(): array
    {
        $stmt = $this->database->prepare("
        SELECT * FROM schedules
        WHERE next_run <= NOW()
        ");
        $stmt->execute();

        return array_map(fn($r) => $this->mapToSchedule($r), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function updateNextRun(int $scheduleId, string $cron): void
    {
        $cronService = new CronService();
        $next = $cronService->getNextRun($cron, new DateTime());

        $stmt = $this->database->prepare('
        UPDATE schedules
        SET next_run = :next_run
        WHERE id = :id
    ');

        $stmt->execute([
            ':next_run' => $next->format('Y-m-d H:i:s'),
            ':id' => $scheduleId
        ]);
    }
}
