<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository
{
    public function getUsers(): array
    {
        $stmt = $this->database->prepare('SELECT * FROM users');
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach ($rows as $row) {
            $users[] = $this->mapToUser($row);
        }

        return $users;
    }

    public function getUserByEmail(string $email): User|false
    {
        $stmt = $this->database->prepare('
            SELECT * FROM users WHERE email = :email LIMIT 1
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        return $this->mapToUser($row);
    }

    public function createUser(string $email, string $hashedPassword, string $firstname, string $lastname): void
    {
        $fullName = $firstname . ' ' . $lastname;
        $nickname = strtolower($firstname . '.' . $lastname);

        $stmt = $this->database->prepare('
            INSERT INTO users (email, password_hash, nickname, full_name)
            VALUES (:email, :password_hash, :nickname, :full_name)
        ');

        $stmt->execute([
            ':email' => $email,
            ':password_hash' => $hashedPassword,
            ':nickname' => $nickname,
            ':full_name' => $fullName
        ]);
    }

    private function mapToUser(array $row): User
    {
        return new User(
            (int)$row['id'],
            $row['email'],
            $row['password_hash'],
            $row['nickname'],
            $row['full_name'],
            $row['role'] ?? null,
            $row['created_at'],
            $row['updated_at'] ?? null
        );
    }
}
