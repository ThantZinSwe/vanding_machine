<?php

declare(strict_types=1);

namespace App\Requests\Products;

use App\Middleware\Api\AuthMiddleware;
use Core\FormRequest;

class CreateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return is_admin() || AuthMiddleware::user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'price' => ['required', 'numeric', 'positive'],
            'quantity_available' => ['required', 'numeric', 'positive'],
        ];
    }
}
