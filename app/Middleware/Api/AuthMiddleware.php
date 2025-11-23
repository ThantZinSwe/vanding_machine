<?php

declare(strict_types=1);

namespace App\Middleware\Api;

use App\Models\User;
use Core\Middleware;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware extends Middleware
{
    public static ?User $user = null;

    public static function handle()
    {
        $headers = apache_request_headers();
        $authHeader = $headers['Authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return self::unauthorized();
        }

        $jwt = $matches[1];
        $key = env('JWT_SECRET', 'your-secret-key');

        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            
            self::$user = User::query()->where('email', $decoded->email)->first();

            if (!self::$user) {
                return self::unauthorized();
            }

        } catch (\Exception $e) {
            return self::unauthorized();
        }

        return true;
    }

    private static function unauthorized()
    {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    public static function user()
    {
        return self::$user;
    }
}