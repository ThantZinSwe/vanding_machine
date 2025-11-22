<?php

declare(strict_types=1);

namespace App\Requests\Purchase;

use Core\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !is_admin();
    }

    public function rules(): array
    {
        return [
            'quantity' => ['required', 'numeric', 'min:1', 'positive'],
        ];
    }
}
