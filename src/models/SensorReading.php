<?php

class SensorReading
{
    public function __construct(
        private int $id,
        private int $sensorId,
        private float $value,
        private string $createdAt
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
    public function getSensorId(): int
    {
        return $this->sensorId;
    }
    public function getValue(): float
    {
        return $this->value;
    }
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
