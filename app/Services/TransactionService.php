<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    public function create(array $data)
    {
        return Transaction::create($data);
    }
}
