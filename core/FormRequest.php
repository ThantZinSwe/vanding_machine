<?php

declare(strict_types=1);

namespace Core;

use Core\Validation;

abstract class FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public static function check(array $data): array
    {
        $instance = new static($data);

        if (!$instance->authorize()) {
            abort(403, "Unauthorized");
        }

        $validator = Validation::make($data, $instance->rules());

        if (!$validator->validate()) {
           back();
        }

        return $data;
    }

    public abstract function rules(): array;
}
