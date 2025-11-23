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
            if (wants_json()) {
                json_response(['error' => 'Unauthorized'], 403);
            }
            abort(403, "Unauthorized");
        }

        $validator = Validation::make($data, $instance->rules());

        if (!$validator->validate()) {
            if (wants_json()) {
                json_response(['errors' => $validator->errors()], 422);
            }
           back();
        }

        return $data;
    }

    public abstract function rules(): array;
}
