<?php
namespace App\Services;

final class Session {
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['chat_history'])) {
            $_SESSION['chat_history'] = [];
        }
    }

    public static function addMessage(string $type, string $message): void
    {
        $_SESSION['chat_history'][] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    public static function getHistory(): array {
        return $_SESSION['chat_history'] ?? [];
    }
}