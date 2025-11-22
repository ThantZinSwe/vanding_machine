<?php

declare(strict_types=1);

namespace App\Controller;

use Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        redirect('/products');
    }
}
