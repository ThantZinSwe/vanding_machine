<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Requests\Products\CreateProductRequest;
use App\Requests\Products\UpdateProductRequest;
use App\Services\ProductService;
use Core\Controller\ApiController;

class ProductsController extends ApiController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $search = $_GET['q'] ?? '';
        $sort   = $_GET['sort'] ?? 'id';
        $order  = $_GET['order'] ?? 'asc';
        $page   = (int) ($_GET['page'] ?? 1);

        $paginator = $this->productService->getWithPagination($search, $sort, $order, $page);

        return $this->json($paginator);
    }

    public function show(string $id)
    {
        $product = $this->productService->findById($id);

        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        return $this->json(['data' => $product]);
    }

    public function store()
    {
        $input = $this->getJsonInput();

        $validatedData = CreateProductRequest::check($input);

        $product = $this->productService->create($validatedData);

        if (!$product) {
            return $this->json(['error' => 'Failed to create product'], 500);
        }

        return $this->json(['message' => 'Product created successfully', 'data' => $product], 201);
    }

    public function update(string $id)
    {
        $product = $this->productService->findById($id);

        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        $input = $this->getJsonInput();

        $validatedData = UpdateProductRequest::check($input);

        $success = $this->productService->update($id, $validatedData);

        if (!$success) {
            return $this->json(['error' => 'Failed to update product'], 500);
        }

        return $this->json(['message' => 'Product updated successfully']);
    }

    public function destroy(string $id)
    {
        $product = $this->productService->findById($id);

        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        $this->productService->delete($id);

        return $this->json(['message' => 'Product deleted successfully']);
    }
}
