<?php

declare(strict_types=1);

if (!function_exists('dd')) {
    function dd(mixed ...$vars): void
    {
        echo '<pre style="background: #000; color: #fff; padding: 15px; border-radius: 5px; font-family: Consolas, monospace; font-size: 14px; line-height: 1.5;">';

        foreach ($vars as $var) {
            var_dump($var);
        }

        echo '</pre>';
        die(1);
    }
}

if (!function_exists('base_path')) {
    function base_path(string $relativePath = ''): string
    {
        $path = dirname(__DIR__) . ($relativePath ? DIRECTORY_SEPARATOR . $relativePath : '');

        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }
}

if (!function_exists('env')) {
    function env(string $key, ?string $default = null): ?string
    {
        static $env = null;

        if (is_null($env)) {
            $env = [];
            $file = base_path('.env');

            if (file_exists($file)) {
                $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

                foreach ($lines as $line) {
                    $line = trim($line);

                    if (str_starts_with($line, '#')) continue;

                    if (strpos($line, '=') !== false) {
                        [$envKey, $envValue] = explode('=', $line, 2);
                        $env[trim($envKey)] = trim($envValue);
                    }
                }
            }
        }

        return $env[$key] ?? $default;
    }
}

if (!function_exists('back')) {
    function back()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}

if (!function_exists('abort')) {
    function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        echo $message;
        exit;
    }
}