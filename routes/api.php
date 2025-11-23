<?php

use App\Controller\Api\AuthController;
use App\Controller\Api\ProductsController;
use App\Controller\Api\PurchaseController;
use App\Middleware\Api\AuthMiddleware;
use Core\Router;

Router::post('/api/login', [AuthController::class, 'login']);

// Products
Router::get('/api/products', [ProductsController::class, 'index'])->middleware(AuthMiddleware::class);
Router::post('/api/products', [ProductsController::class, 'store'])->middleware(AuthMiddleware::class);
Router::get('/api/products/{id}', [ProductsController::class, 'show'])->middleware(AuthMiddleware::class);
Router::put('/api/products/{id}', [ProductsController::class, 'update'])->middleware(AuthMiddleware::class);
Router::delete('/api/products/{id}', [ProductsController::class, 'destroy'])->middleware(AuthMiddleware::class);

// Purchase
Router::post('/api/products/{id}/purchase', [PurchaseController::class, 'store'])->middleware(AuthMiddleware::class);