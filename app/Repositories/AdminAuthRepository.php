<?php

namespace App\Repositories;

use App\Models\User;

class AdminAuthRepository
{
    public function findByEmail($email)
    {
        return User::where('email', $email)
            ->where('role',2)
            ->first();
    }
}
