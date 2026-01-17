<?php

class Region
{
    public function __construct(
        private int $id,
        private int $ownerId,
        private string $name,
        private ?string $description,
        private string $createdAt,
        private ?string $updatedAt
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
    public function getOwnerId(): int
    {
        return $this->ownerId;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getDescription(): ?string
    {
        return $this->description;
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
