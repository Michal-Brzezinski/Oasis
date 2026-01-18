<?php

class Notification
{
    public function __construct(
        private int $id,
        private int $userId,
        private string $message,
        private string $createdAt,
        private bool $isRead
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
    public function getUserId(): int
    {
        return $this->userId;
    }
    public function getMessage(): string
    {
        return $this->message;
    }
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
    public function isRead(): bool
    {
        return $this->isRead;
    }
}
