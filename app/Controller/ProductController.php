<?php

declare(strict_types=1);

namespace App\Controller;

use Core\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $this->view('products/index', [
            'title' => 'Products',
        ]);
    }
}
