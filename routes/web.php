<?php

declare(strict_types=1);

use App\Controller\ProductController;
use Core\Router;

Router::get('/products', [ProductController::class, 'index']);