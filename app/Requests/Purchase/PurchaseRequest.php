<?php

declare(strict_types=1);

namespace App\Requests\Purchase;

use App\Middleware\Api\AuthMiddleware;
use Core\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !is_admin() || AuthMiddleware::user()->role === 'user';
    }

    public function rules(): array
    {
        return [
            'quantity' => [
                'required',
                'numeric',
                'min:1',
                'positive',
                'max:' . $this->params['quantity_available'],
            ],
        ];
    }
}
