<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function getWithPagination(string $search, string $sort, string $order, int $page)
    {
        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->orderBy($sort, $order)->paginate($page);
    }

    public function findById(string $id)
    {
        return Product::query()->where('id', $id)->first();
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(string $id, array $data)
    {
        return Product::query()->where('id', $id)->update($data);
    }

    public function delete(string $id)
    {
        return Product::query()->where('id', $id)->delete();
    }

    public function updateQuantity(string $productId, int $quantity)
    {
        return Product::query()->where('id', $productId)->update([
            'quantity_available' => $quantity,
        ]);
    }
}
