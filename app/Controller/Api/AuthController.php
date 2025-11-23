<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Models\User;
use App\Requests\Auth\LoginRequest;
use Core\Controller\ApiController;
use Firebase\JWT\JWT;

class AuthController extends ApiController
{
    public function login()
    {
        $data = $this->getJsonInput();

        $validatedData = LoginRequest::check($data);

        /** @var User $user */
        $user = User::query()->where('email', $validatedData['email'])->first();

        if ($user && password_verify($data['password'], $user->password)) {
            $token = Jwt::encode([
                'role' => $user->role,
                'email' => $user->email
            ], env('JWT_SECRET'), 'HS256');

            return $this->json([
                'token' => $token,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
        }

        return $this->json(['error' => 'Invalid credentials'], 401);
    }
}
