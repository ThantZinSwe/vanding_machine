<?php

declare(strict_types=1);

namespace App\Controller;

use Core\Controller;
use Core\Session;
use App\Models\User;
use App\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function register()
    {
        return $this->view('auth/register');
    }

    public function submitRegister()
    {
        $validatedData = RegisterRequest::check($_POST);

        $validatedData['password'] = password_hash($validatedData['password'], PASSWORD_DEFAULT);

        $user = User::create($validatedData);

        Session::put('auth', $user);

        redirect('/login');
    }

    public function login()
    {
        $this->view('auth/login');
    }
}
