<?php

declare(strict_types=1);

use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\ProductsController;
use App\Controller\PurchaseController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use Core\Router;

// auth
Router::get('/register', [AuthController::class, 'register'])->middleware(GuestMiddleware::class);
Router::post('/register', [AuthController::class, 'submitRegister']);
Router::get('/login', [AuthController::class, 'login'])->middleware(GuestMiddleware::class);
Router::post('/login', [AuthController::class, 'submitLogin']);
Router::post('/logout', [AuthController::class, 'logout'])->middleware(AuthMiddleware::class);

// home
Router::get('/', [HomeController::class, 'index']);

// products
Router::get('/products', [ProductsController::class, 'index'])->middleware(AuthMiddleware::class);
Router::get('/products/create', [ProductsController::class, 'create'])->middleware(AuthMiddleware::class);
Router::post('/products', [ProductsController::class, 'store'])->middleware(AuthMiddleware::class);
Router::get('/products/{id}/edit', [ProductsController::class, 'edit'])->middleware(AuthMiddleware::class);
Router::put('/products/{id}', [ProductsController::class, 'update'])->middleware(AuthMiddleware::class);
Router::delete('/products/{id}', [ProductsController::class, 'destroy'])->middleware(AuthMiddleware::class);

// purchase
Router::get('/products/{id}/purchase', [PurchaseController::class, 'index'])->middleware(AuthMiddleware::class);
Router::post('/products/{id}/purchase', [PurchaseController::class, 'store'])->middleware(AuthMiddleware::class);