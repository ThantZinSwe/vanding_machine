<?php

declare(strict_types=1);

namespace App\Controller;

use App\Requests\Products\CreateProductRequest;
use App\Requests\Products\UpdateProductRequest;
use Core\Controller\Controller;
use Core\Session;
use App\Services\ProductService;

class ProductsController extends Controller
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
        $order  = strtolower($_GET['order'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';
        $page   = (int) $_GET['page'] ?? 1;

        $paginator = $this->productService->getWithPagination($search, $sort, $order, $page);

        $this->view('products/index', [
            'paginator' => $paginator,
            'search' => $search,
            'sort' => $sort,
            'order' => strtolower($order),
        ]);
    }

    public function create()
    {
        if (!is_admin()) {
            abort(403, "You are not allowed to access this page");
        }

        $this->view('products/create');
    }

    public function store()
    {
        $validatedData = CreateProductRequest::check($_POST);

        $product = $this->productService->create($validatedData);

        if (!$product) {
            abort(500, "Failed to create product");
        }

        Session::flash('success', 'Product created successfully');

        $this->back();
    }

    public function edit(string $id)
    {
        if (!is_admin()) {
            abort(403, "You are not allowed to access this page");
        }

        $product = $this->productService->findById($id);

        if (!$product) {
            abort(404, "Product not found");
        }

        $this->view('products/edit', [
            'product' => $product,
        ]);
    }

    public function update(string $id)
    {
        $validatedData = UpdateProductRequest::check($_POST);

        $product = $this->productService->update($id, $validatedData);

        if (!$product) {
            abort(500, "Failed to update product");
        }

        Session::flash('success', 'Product updated successfully');

        $this->back();
    }

    public function destroy(string $id)
    {
        $this->productService->delete($id);

        $this->redirect('/products');
    }
}
