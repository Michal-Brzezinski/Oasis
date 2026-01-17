<?php

class Sensor
{
    public function __construct(
        private int $id,
        private int $regionId,
        private string $name,
        private string $type,
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
    public function getType(): string
    {
        return $this->type;
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
}
