<?php

namespace App\Services;

use App\Repositories\AdminAuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthService
{
    protected $repo;

    public function __construct(AdminAuthRepository $repo)
    {
        $this->repo = $repo;
    }

    public function login($email, $password)
    {
        $admin = $this->repo->findByEmail($email);

        if (!$admin || !Hash::check($password, $admin->password)) {
            return false;
        }

        Auth::guard('admin')->login($admin);
        return true;
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return true;
    }
}
