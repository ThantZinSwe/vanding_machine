<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Middleware;

class GuestMiddleware extends Middleware
{
    public static function handle(): mixed
    {
        if (guest()) {
            return true;
        }

        return redirect('/products');
    }
}
