<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Middleware;

class AuthMiddleware extends Middleware
{
    public static function handle(): mixed
    {
        if (!is_null(auth())) {
            return true;
        }

        return redirect('/login');
    }
}
