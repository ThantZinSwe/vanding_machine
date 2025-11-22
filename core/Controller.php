<?php

declare(strict_types=1);

namespace Core;

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
}
