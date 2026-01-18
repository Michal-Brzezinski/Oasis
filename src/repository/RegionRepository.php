<?php

require_once __DIR__ . '/Repository.php';
require_once __DIR__ . '/../models/Region.php';

class RegionRepository extends Repository
{
    public function getRegionsByOwner(int $ownerId): array
    {
        $stmt = $this->database->prepare('
            SELECT * FROM regions WHERE owner_id = :owner ORDER BY id ASC
        ');
        $stmt->bindValue(':owner', $ownerId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->mapToRegion($r), $rows);
    }

    public function getRegionById(int $id): ?Region
    {
        $stmt = $this->database->prepare('
            SELECT * FROM regions WHERE id = :id LIMIT 1
        ');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToRegion($row) : null;
    }

    public function createRegion(int $ownerId, string $name): void
    {
        $stmt = $this->database->prepare('
            INSERT INTO regions (owner_id, name)
            VALUES (:owner, :name)
        ');

        $stmt->execute([
            ':owner' => $ownerId,
            ':name' => $name
        ]);
    }

    public function updateRegion(int $id, string $name): void
    {
        $stmt = $this->database->prepare('
            UPDATE regions SET name = :name WHERE id = :id
        ');

        $stmt->execute([
            ':name' => $name,
            ':id' => $id
        ]);
    }

    public function deleteRegion(int $id): void
    {
        $stmt = $this->database->prepare('
            DELETE FROM regions WHERE id = :id
        ');

        $stmt->execute([':id' => $id]);
    }

    private function mapToRegion(array $row): Region
    {
        return new Region(
            (int)$row['id'],
            (int)$row['owner_id'],
            $row['name'],
            $row['description'] ?? null,        // nullable
            $row['created_at'],                 // w bazie nie-null
            $row['updated_at'] ?? null          // nullable
        );
    }
}