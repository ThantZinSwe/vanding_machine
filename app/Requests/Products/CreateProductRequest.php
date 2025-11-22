<?php

declare(strict_types=1);

namespace App\Requests\Products;

use Core\FormRequest;

class CreateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return is_admin();
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
