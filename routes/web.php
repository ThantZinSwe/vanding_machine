<?php

declare(strict_types=1);

use App\Controller\AuthController;
use App\Controller\ProductController;
use Core\Router;

// auth
Router::get('/register', [AuthController::class, 'register']);
Router::get('/login', [AuthController::class, 'login']);

// products
Router::get('/products', [ProductController::class, 'index']);