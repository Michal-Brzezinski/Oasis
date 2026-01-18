<?php

require_once __DIR__ . '/Repository.php';
require_once __DIR__ . '/../models/Notification.php';

class NotificationRepository extends Repository
{
    public function addNotification(int $userId, string $message): void
    {
        $stmt = $this->database->prepare('
            INSERT INTO notifications (user_id, message)
            VALUES (:user_id, :message)
        ');

        $stmt->execute([
            ':user_id' => $userId,
            ':message' => $message
        ]);
    }

    public function getUnreadByUser(int $userId): array
    {
        $stmt = $this->database->prepare('
            SELECT * FROM notifications
            WHERE user_id = :user_id AND is_read = FALSE
            ORDER BY created_at DESC
        ');

        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $notifications = [];

        foreach ($rows as $row) {
            $notifications[] = new Notification(
                (int)$row['id'],
                (int)$row['user_id'],
                $row['message'],
                $row['created_at'],
                (bool)$row['is_read']
            );
        }

        return $notifications;
    }

    public function markAllAsRead(int $userId): void
    {
        $stmt = $this->database->prepare('
            UPDATE notifications
            SET is_read = TRUE
            WHERE user_id = :user_id
        ');

        $stmt->execute([':user_id' => $userId]);
    }
}
