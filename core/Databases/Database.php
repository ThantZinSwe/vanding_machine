<?php

declare(strict_types=1);

namespace Core\Databases;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;

    private PDO $connection;

    private array $config;

    private function __construct()
    {
        $this->config = require base_path('config/database.php');
        $this->connect();
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function isConnected(): bool
    {
        return $this->connection instanceof PDO;
    }

    private function connect(): void
    {
        $config = $this->config['connections'];

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            $dsn = "mysql:host={$config['host']};charset=utf8mb4";
            $pdo = new PDO($dsn, $config['username'], $config['password'], $options);
            
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4";
            $this->connection = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            throw new \Exception("Connection failed: " . $e->getMessage());
        }
    }
}
