<?php

declare(strict_types=1);

namespace Core;

use Core\Session;

class Validation
{
    protected array $errors = [];

    protected array $data = [];

    protected array $rules = [];

    protected array $messages = [
        'required' => 'The :field field is required.',
        'email' => 'The :field field must be a valid email address.',
        'min_numeric' => 'The :field must be at least :min.',
        'max_numeric' => 'The :field must not be greater than :max.',
        'min_string'  => 'The :field must be at least :min characters.',
        'max_string'  => 'The :field must not be greater than :max characters.',
        'numeric' => 'The :field must be a number.',
        'positive' => 'The :field field must be a positive number.',
    ];

    public function __construct()
    {
        $this->errors = [];
        $this->data = [];
        $this->rules = [];
    }

    public static function make(array $data, array $rules)
    {
        $instance = new static();

        $instance->data = $data;

        $instance->rules = $rules;

        return $instance;
    }

    public function validate(): bool
    {
        foreach ($this->rules as $field => $rules) {
            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {
                $this->validateRule($field, $value, $rule);

                if (isset($this->errors[$field])) break;
            }
        }

        if (!empty($this->errors)) {
            Session::flash('errors', $this->errors);
            Session::flash('old', $this->data);

            return false;
        }

        return true;
    }

    public function validateRule(string $field, mixed $value, string $rule): void
    {
        $params = [];

        if (str_contains($rule, ':')) {
            [$rule, $param] = explode(':', $rule, 2);
            $params = explode(',', $param);
        }

        if (method_exists($this, $rule)) {
            $this->$rule($field, $value, ...$params);
        }
    }

    public function errors(): array
    {
        return $this->errors;
    }

    protected function required(string $field, mixed $value): void
    {
        if (is_null($value) || $value === '' || (is_array($value) && empty($value))) {
            $this->addError($field, 'required');
        }
    }

    protected function email(string $field, mixed $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'email');
        }
    }

    protected function min(string $field, mixed $value, string|int $min): void
    {
        if (is_numeric($value)) {
            if ($value < $min) {
                $this->addError($field, 'min_numeric', ['min' => $min]);
            }
        } else {
            if (strlen($value) < $min) {
                $this->addError($field, 'min_string', ['min' => $min]);
            }
        }
    }

    protected function max(string $field, mixed $value, string|int $max): void
    {
        if (is_numeric($value)) {
            if ($value > $max) {
                $this->addError($field, 'max_numeric', ['max' => $max]);
            }
        } else {
            if (strlen($value) > $max) {
                $this->addError($field, 'max_string', ['max' => $max]);
            }
        }
    }

    protected function numeric(string $field, mixed $value): void
    {
        if (!is_numeric($value)) {
            $this->addError($field, 'numeric');
        }
    }

    protected function positive(string $field, mixed $value): void
    {
        if (!is_numeric($value) || $value <= 0) {
            $this->addError($field, 'positive');
        }
    }

    protected function addError(string $field, string $rule, array $params = []): void
    {
        $message = $this->messages[$rule] ?? 'The :field is invalid';
        $message = str_replace(':field', $field, $message);
        
        foreach ($params as $key => $value) {
            $message = str_replace(':' . $key, $value, $message);
        }
        
        $this->errors[$field] = $message;
    }
}
