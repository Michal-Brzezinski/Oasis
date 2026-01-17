<?php

class WateringAction
{
    public function __construct(
        private int $id,
        private int $regionId,
        private ?int $scheduleId,
        private ?int $initiatedBy,
        private string $startedAt,
        private ?string $stoppedAt,
        private string $status,
        private ?float $volumeLiters
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
    public function getRegionId(): int
    {
        return $this->regionId;
    }
    public function getScheduleId(): ?int
    {
        return $this->scheduleId;
    }
    public function getInitiatedBy(): ?int
    {
        return $this->initiatedBy;
    }
    public function getStartedAt(): string
    {
        return $this->startedAt;
    }
    public function getStoppedAt(): ?string
    {
        return $this->stoppedAt;
    }
    public function getStatus(): string
    {
        return $this->status;
    }
    public function getVolumeLiters(): ?float
    {
        return $this->volumeLiters;
    }
}
