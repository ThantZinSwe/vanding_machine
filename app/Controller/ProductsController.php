<?php

declare(strict_types=1);

namespace App\Controller;

use App\Models\Product;
use App\Requests\Products\CreateProductRequest;
use App\Requests\Products\UpdateProductRequest;
use Core\Controller;
use Core\Session;

class ProductsController extends Controller
{
    public function index()
    {
        $search = $_GET['q'] ?? '';
        $sort   = $_GET['sort'] ?? 'id';
        $order  = strtolower($_GET['order'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';
        $page   = (int) $_GET['page'] ?? 1;

        $query = Product::query();

        if (!empty($search)) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $paginator = $query->orderBy($sort, $order)->paginate($page);

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

        $this->view('products/create', [
            'title' => 'Create Product',
        ]);
    }

    public function store()
    {
        $validatedData = CreateProductRequest::check($_POST);

        $product = Product::create($validatedData);

        if (!$product) {
            abort(500, "Failed to create product");
        }

        Session::flash('success', 'Product created successfully');

        back();
    }

    public function edit(string $id)
    {
        if (!is_admin()) {
            abort(403, "You are not allowed to access this page");
        }

        $product = Product::query()->where('id', $id)->first();

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

        $product = Product::query()->where('id', $id)->update($validatedData);

        if (!$product) {
            abort(500, "Failed to update product");
        }

        Session::flash('success', 'Product updated successfully');

        back();
    }

    public function destroy(string $id)
    {
        Product::query()->where('id', $id)->delete();

        redirect('/products');
    }
}
