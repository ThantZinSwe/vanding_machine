<?php

declare(strict_types=1);

use Core\Router;

require_once './bootstrap.php';

require_once base_path('routes/web.php');

Router::getInstance()->run();