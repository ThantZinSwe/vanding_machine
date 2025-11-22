<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

class Transaction extends Model
{
    protected string $table = 'transactions';

    protected array $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total_price',
    ];
}
