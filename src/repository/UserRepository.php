<?php

require_once 'Repository.php';

class UserRepository extends Repository
{

    public function getUsers(): ?array
    {
        $stmt = $this->database->prepare('SELECT * FROM users');
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    }

    public function getUserByEmail(string $email)
    {
        $stmt = $this->database->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $users = $stmt->fetch(PDO::FETCH_ASSOC); // Jeśli brak użytkownika, $user = false

        return $users; // Zwróci false, jeśli nie znaleziono użytkownika
    }

    public function createUser(string $email, string $hashedPassword, string $firstname, string $lastname)
    {
        $fullName = $firstname . ' ' . $lastname;

        // Możesz wygenerować nickname automatycznie
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
}
