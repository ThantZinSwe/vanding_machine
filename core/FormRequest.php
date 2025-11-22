<?php

declare(strict_types=1);

namespace Core;

use Core\Validation;

abstract class FormRequest
{
    protected array $params = [];

    public function authorize(): bool
    {
        return true;
    }

    public static function check(array $data, array $params = []): array
    {
        $instance = new static();

        $instance->params = $params;

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
