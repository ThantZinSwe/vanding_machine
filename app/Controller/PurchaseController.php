<?php

declare(strict_types=1);

namespace App\Controller;

use App\Models\Product;
use App\Models\Transaction;
use App\Requests\Purchase\PurchaseRequest;
use Core\Controller;
use Core\Session;

class PurchaseController extends Controller
{
    public function index(string $productId)
    {
        $product = Product::query()
            ->where('id', $productId)
            ->first();

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
        $product = Product::query()
            ->where('id', $productId)
            ->first();

        if (!$product) {
            throw new \Exception('Product not found');
        }

        $validatedData = PurchaseRequest::check($_POST);

        Transaction::query()->create([
            'user_id' => auth()->id,
            'product_id' => $productId,
            'quantity' => $validatedData['quantity'],
            'total_price' => $validatedData['quantity'] * $product->price,
        ]);

        Product::query()->where('id', $productId)->update([
            'quantity_available' => $product->quantity_available - $validatedData['quantity'],
        ]);

        Session::flash('success', 'Product purchased successfully');
        
        return back();
    }
}
