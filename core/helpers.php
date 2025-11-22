<?php

declare(strict_types=1);

if (!function_exists('base_path')) {
    function base_path($relativePath = '')
    {
         $path = dirname(__DIR__) . ($relativePath ? DIRECTORY_SEPARATOR . $relativePath : '');

        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }
}