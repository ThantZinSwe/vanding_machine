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

if (!function_exists('sort_url')) {
    function sort_url($col, $sort, $order, $search) 
    {
        return '?' . http_build_query([
            'q' => $search,
            'sort' => $col,
            'order' => ($sort === $col && $order === 'asc') ? 'desc' : 'asc',
            'page' => 1 
        ]);
    }
}

if (!function_exists('page_url')) {
    function page_url($page, $search, $sort, $order)
    {
        return '?' . http_build_query([
            'q' => $search,
            'sort' => $sort,
            'order' => $order,
            'page' => $page
        ]);
    }
}
