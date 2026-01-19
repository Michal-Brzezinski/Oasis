<?php

class Camera
{
    public function __construct(
        private int $id,
        private int $regionId,
        private string $name,
        private string $streamUrl,
        private ?string $snapshotUrl,
        private bool $isActive,
        private string $createdAt,
        private ?string $updatedAt
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
    public function getRegionId(): int
    {
        return $this->regionId;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getStreamUrl(): string
    {
        return $this->streamUrl;
    }
    public function isActive(): bool
    {
        return $this->isActive;
    }
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }
    public function getSnapshotUrl(): ?string
    {
        return $this->snapshotUrl;
    }
}
