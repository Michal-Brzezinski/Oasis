<?php

class Schedule
{
    public function __construct(
        private int $id,
        private int $regionId,
        private string $name,
        private string $cronExpression,
        private float $volumeLiters,
        private bool $isEnabled,
        private string $createdAt,
        private ?string $nextRun,
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
    public function getCronExpression(): string
    {
        return $this->cronExpression;
    }
    public function getVolumeLiters(): float
    {
        return $this->volumeLiters;
    }
    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }
    public function getNextRun(): ?string
    {
        return $this->nextRun;
    }
}
