<?php

declare(strict_types=1);

namespace App\Requests\Auth;

use Core\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return guest();
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ];
    }
}
