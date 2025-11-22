<?php

declare(strict_types=1);

use Core\Databases\Database;
use Core\Session;

require __DIR__ . '/../vendor/autoload.php';

// init session
Session::init();

// save previous url
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $currentUri = $_SERVER['REQUEST_URI'];
    // $exclude = ['/login', '/register'];

    // if (!in_array($currentUri, $exclude)) {
    Session::put('previous_url', $currentUri);
    // }
}

// init database
$db = Database::getInstance();

if (!$db->isConnected()) {
    throw new \Exception('Database connection failed');
}

$connection = $db->getConnection();
