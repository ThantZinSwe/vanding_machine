<?php

use Core\Session;

if (!function_exists('auth')) {
    function auth()
    {
        return Session::get('auth') ?? null;
    }
}

if (!function_exists('guest')) {
    function guest()
    {
        return !Session::has('auth');
    }
}

if (!function_exists('is_admin')) {
    function is_admin()
    {
        return auth() && auth()->isAdmin();
    }
}
