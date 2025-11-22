<?php

namespace App\Models;

use Core\Model;

/**
 * @property int $id
 * @property string $name
 * @property float $price
 * @property int $quantity_available
 */
class Product extends Model
{
    protected string $table = 'products';

    protected array $fillable = [
        'name',
        'price',
        'quantity_available',
    ];

    public static function updateQuantity(int $productId, int $quantity)
    {
        return self::query()->where('id', $productId)->update([
            'quantity_available' => $quantity,
        ]);
    }
}
