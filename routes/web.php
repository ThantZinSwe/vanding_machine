<?php

declare(strict_types=1);

use App\Controller\AuthController;
use App\Controller\ProductController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use Core\Router;

// auth
Router::get('/register', [AuthController::class, 'register'])->middleware(GuestMiddleware::class);
Router::post('/register', [AuthController::class, 'submitRegister']);
Router::get('/login', [AuthController::class, 'login'])->middleware(GuestMiddleware::class);
Router::post('/login', [AuthController::class, 'submitLogin']);

// products
Router::get('/products', [ProductController::class, 'index'])->middleware(AuthMiddleware::class);