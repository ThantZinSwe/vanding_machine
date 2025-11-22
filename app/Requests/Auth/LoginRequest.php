<?php

declare(strict_types=1);

namespace App\Requests\Auth;

use Core\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return is_guest();
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ];
    }
}
