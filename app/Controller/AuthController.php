<?php

declare(strict_types=1);

namespace App\Controller;

use Core\Controller\Controller;
use Core\Session;
use App\Models\User;
use App\Requests\Auth\LoginRequest;
use App\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function register()
    {
        return $this->view('auth/register');
    }

    public function submitRegister()
    {
        /** @var User $user */
        $user = User::query()->where('email', $_POST['email'])->first();

        if ($user) {
            Session::flash('invalid', 'Email already exists');
            back();
        }

        $validatedData = RegisterRequest::check($_POST);

        $validatedData['password'] = password_hash($validatedData['password'], PASSWORD_DEFAULT);

        User::create($validatedData);

        redirect('/login');
    }

    public function login()
    {
        $this->view('auth/login');
    }

    public function submitLogin()
    {
        $validatedData = LoginRequest::check($_POST);

        /** @var User $user */
        $user = User::query()->where('email', $validatedData['email'])->first();

        if ($user && password_verify($validatedData['password'], $user->password)) {
            Session::put('auth', $user);
            redirect('/products');
        }

        Session::flash('invalid', 'Invalid credentials');
        back();
    }

    public function logout()
    {
        Session::destroy();
        redirect('/login');
    }
}
