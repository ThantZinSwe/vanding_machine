<?php

declare(strict_types=1);

namespace Core\Controller;

use Core\Session;

abstract class Controller
{
    protected function view(string $view, array $data = [])
    {
        if (Session::hasFlash()) {
            $data = array_merge($data, Session::get('_flash'));
            Session::unflash();
        }

        extract($data);

        require base_path("resources/views/{$view}.php");
    }

    protected function redirect(string $path): void
    {
        redirect($path);
    }

    protected function back(): void
    {
        back();
    }

    protected function abort(int $code, string $message = ''): void
    {
        abort($code, $message);
    }
}
