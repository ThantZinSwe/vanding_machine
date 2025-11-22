<?php

declare(strict_types=1);

namespace Core;

class Router
{
    protected static ?Router $instance = null;
    protected static array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'PATCH' => [],
    ];

    protected ?array $currentRoute = null;

    public static function getInstance(): Router
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected static function addRoute($method, $uri, $action): Router
    {
        $instance = self::getInstance();

        self::$routes[$method][$uri] = [
            'action' => $action,
            'middleware' => [],
        ];

        $instance->currentRoute = [$method, $uri];

        return $instance;
    }

    public static function get(string $uri, array $action): Router
    {
        return self::addRoute('GET', $uri, $action);
    }

    public static function post(string $uri, array $action): Router
    {
        return self::addRoute('POST', $uri, $action);
    }

    public static function put(string $uri, array $action): Router
    {
        return self::addRoute('PUT', $uri, $action);
    }

    public static function delete(string $uri, array $action): Router
    {
        return self::addRoute('DELETE', $uri, $action);
    }

    public static function patch(string $uri, array $action): Router
    {
        return self::addRoute('PATCH', $uri, $action);
    }

    public function run(): mixed
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes[$method] as $routeURI => $route) {
            $pattern = '#^' . preg_replace('/\{([^\}]+)\}/', '([^\/]+)', $routeURI) . '$#';

            if (!preg_match($pattern, $uri, $matches)) continue;

            array_shift($matches);

            return $this->call($route, $matches);
        }

        http_response_code(404);
        echo "404 Not Found";

        return false;
    }

    private function call(array $route, array $matches): mixed
    {
        if (!$this->handleMiddleware($route['middleware'])) return false;

        [$controller, $method] = $route['action'];

        return (new $controller)->$method(...$matches);
    }

    public function middleware($class, $action = 'handle')
    {
        if ($this->currentRoute) {
            [$method, $uri] = $this->currentRoute;
            self::$routes[$method][$uri]['middleware'][] = [
                'class' => $class,
                'action' => $action
            ];
        }
        return $this;
    }

    private function handleMiddleware(array $middlewares): bool
    {
        foreach ($middlewares as $middleware) {
            [$class, $method] = [$middleware['class'], $middleware['action']];

            if (call_user_func([$class, $method]) !== true) {
                return false;
            }
        }

        return true;
    }
}
