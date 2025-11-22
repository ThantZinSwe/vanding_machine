<?php

declare(strict_types=1);

namespace Core;

abstract class Controller
{
    protected function view(string $view, array $data = [])
    {
        extract($data);

        require base_path("resources/views/{$view}.php");
    }
}
