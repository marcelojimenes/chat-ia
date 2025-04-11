<?php

namespace App\Database;

use PDO;
use PDOException;

final class Sqlite
{
    const DB_FILE = 'database.sqlite';

    private static ?PDO $instance = null;

    public static function getInstance(): ?PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        try {
            self::$instance = new PDO(
                "sqlite:" . self::DB_FILE,
                null,
                null,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            self::initTables();

        } catch (PDOException $e) {
            throw new \RuntimeException('database connection error: ' . $e->getMessage());
        }

        return self::$instance;
    }

    private static function initTables(): void
    {
        try {
            self::$instance->exec("
                CREATE TABLE IF NOT EXISTS interactions (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_message TEXT NOT NULL,
                    assistant_response TEXT NOT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ");

        } catch (PDOException $e) {
            throw new \RuntimeException('error creating tables: ' . $e->getMessage());
        }
    }

}