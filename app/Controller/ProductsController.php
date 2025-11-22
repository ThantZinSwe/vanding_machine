<?php

declare(strict_types=1);

namespace App\Controller;

use App\Models\Product;
use App\Requests\Products\CreateProductRequest;
use App\Requests\Products\UpdateProductRequest;
use Core\Controller;

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

        $paginator = $query->orderBy($sort, $order)->paginate(15, $page);

        $this->view('products/index', [
            'paginator' => $paginator,
            'search' => $search,
            'sort' => $sort,
            'order' => strtolower($order),
        ]);
    }

    public function create()
    {
        $this->view('products/create', [
            'title' => 'Create Product',
        ]);
    }

    public function store()
    {
        $validatedData = CreateProductRequest::check($_POST);

        Product::create($validatedData);

        redirect('/products');
    }

    public function edit(string $id)
    {
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

        Product::query()->where('id', $id)->update($validatedData);

        redirect('/products');
    }

    public function destroy(string $id)
    {
        Product::query()->where('id', $id)->delete();

        redirect('/products');
    }
}
