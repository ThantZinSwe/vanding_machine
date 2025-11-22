<?php

declare(strict_types=1);

namespace App\Controller;

use App\Models\Product;
use App\Models\Transaction;
use App\Requests\Purchase\PurchaseRequest;
use App\Services\ProductService;
use App\Services\TransactionService;
use Core\Controller;
use Core\Session;

class PurchaseController extends Controller
{
    private ProductService $productService;
    
    private TransactionService $transactionService;

    public function __construct(
        ?ProductService $productService = null,
        ?TransactionService $transactionService = null
    ) {
        $this->productService = $productService ?? new ProductService();
        $this->transactionService = $transactionService ?? new TransactionService();
    }

    public function index(string $productId)
    {
        $product = $this->productService->findById($productId);

        if (!$product) {
            abort(404, "Product not found");
        }

        return $this->view('products/purchase/index', [
            'product' => $product,
        ]);
    }

    public function store(string $productId)
    {
        /** @var Product $product */
        $product = $this->productService->findById($productId);

        if (!$product) {
            abort(404, "Product not found");
        }

        $validatedData = PurchaseRequest::check($_POST, [
            'quantity_available' => $product->quantity_available,
        ]);

        $transaction = $this->transactionService->create([
            'user_id' => auth()->id,
            'product_id' => $productId,
            'quantity' => $validatedData['quantity'],
            'total_price' => $validatedData['quantity'] * $product->price,
        ]);

        if (!$transaction) {
            abort(500, "Failed to create transaction");
        }

        $product = $this->productService->updateQuantity($productId, $product->quantity_available - $validatedData['quantity']);

        if (!$product) {
            abort(500, "Failed to update product quantity");
        }

        Session::flash('success', 'Product purchased successfully');
        
        return back();
    }
}
