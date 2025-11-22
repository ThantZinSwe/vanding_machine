<?php

declare(strict_types=1);

namespace Core\Databases;

use PDO;
use PDOException;

abstract class Migration
{
    protected PDO $db;

    private static PDO $resolver;

    public function __construct()
    {
        if (!self::$resolver) {
            throw new \Exception("Migration connection resolver not set.");
        }

        $this->db = self::$resolver;
    }

    public static function setConnectionResolver(PDO $connection): void
    {
        self::$resolver = $connection;
    }
    
    protected function execute($sql)
    {
        try {
            $this->db->exec($sql);
            return true;
        } catch (PDOException $e) {
            die("Migration failed: " . $e->getMessage());
        }
    }

    abstract public function up(): bool;
    
    abstract public function down(): bool;
}
