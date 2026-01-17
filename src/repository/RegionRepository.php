<?php

require_once __DIR__ . '/../repository/Repository.php';
require_once __DIR__ . '/../models/Region.php';

class RegionRepository extends Repository
{
    public function getRegionsByOwner(int $ownerId): array
    {
        $stmt = $this->database->prepare('
            SELECT * FROM regions WHERE owner_id = :owner_id
        ');
        $stmt->bindValue(':owner_id', $ownerId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => $this->mapToRegion($row), $rows);
    }

    private function mapToRegion(array $row): Region
    {
        return new Region(
            (int)$row['id'],
            (int)$row['owner_id'],
            $row['name'],
            $row['description'],
            $row['created_at'],
            $row['updated_at']
        );
    }
}
