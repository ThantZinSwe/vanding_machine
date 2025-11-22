<?php

declare(strict_types=1);

use Core\Databases\Database;

require __DIR__ . '/../vendor/autoload.php';

$db = Database::getInstance();

if (!$db->isConnected()) {
    throw new \Exception('Database connection failed');
}

$connection = $db->getConnection();