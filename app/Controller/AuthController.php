<?php

declare(strict_types=1);

namespace App\Controller;

use Core\Controller;

class AuthController extends Controller
{
    public function register()
    {
        $this->view('auth/register');
    }

    public function login()
    {
        $this->view('auth/login');
    }
}
