<?php

class User
{
    public function __construct(
        private int $id,
        private string $email,
        private string $passwordHash,
        private string $nickname,
        private string $fullName,
        private ?string $role,
        private string $createdAt,
        private ?string $updatedAt
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
    public function getNickname(): string
    {
        return $this->nickname;
    }
    public function getFullName(): string
    {
        return $this->fullName;
    }
    public function getRole(): ?string
    {
        return $this->role;
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
