<?php

declare(strict_types=1);

namespace Core\Controller;

abstract class ApiController 
{
    protected function json(mixed $data, int $status = 200): void
    {
        json_response($data, $status);
    }
    
    protected function getJsonInput(): array
    {
        $input = json_decode(file_get_contents('php://input'), true);

        return $input ?? [];
    }
}