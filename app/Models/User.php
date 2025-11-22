<?php

namespace App\Models;

use Core\Model;

/**
 * @property string $role
 */
class User extends Model
{
    protected string $table = 'users';

    protected array $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
