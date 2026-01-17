<?php

require_once 'Repository.php';
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
            $row['updated_at']
        );
    }
}
