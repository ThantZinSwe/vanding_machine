<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Middleware\Api\AuthMiddleware;
use App\Requests\Purchase\PurchaseRequest;
use App\Services\ProductService;
use App\Services\TransactionService;
use Core\Controller\ApiController;

class PurchaseController extends ApiController
{
    private ProductService $productService;
    private TransactionService $transactionService;

    public function __construct(ProductService $productService, TransactionService $transactionService)
    {
        $this->productService = $productService;
        $this->transactionService = $transactionService;
    }

    public function store(string $id)
    {
        /** @var Product $product */
        $product = $this->productService->findById($id);

        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        $input = $this->getJsonInput();

        $validatedData = PurchaseRequest::check($input, [
            'quantity_available' => $product->quantity_available,
        ]);

        $user = AuthMiddleware::user();

        $transaction = $this->transactionService->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => $validatedData['quantity'],
            'total_price' => $validatedData['quantity'] * $product->price,
        ]);

        if (!$transaction) {
            return $this->json(['error' => 'Failed to create transaction'], 500);
        }

        $success = $this->productService->updateQuantity(
            $id, 
            $product->quantity_available - $validatedData['quantity']
        );

        if (!$success) {
            return $this->json(['error' => 'Failed to update stock'], 500);
        }

        return $this->json([
            'message' => 'Product purchased successfully',
            'data' => $transaction
        ], 201);
    }
}