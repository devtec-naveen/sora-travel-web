<?php

namespace App\Repositories\Api;
use App\Models\User;
use App\Models\EmailTemplateModel;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => config('constant.roleText.user'),
            'tc' => $data['tc'],
        ]);
    }

    public function findByEmailAndRole($email, $role)
    {
        return User::where('email', $email)
            ->where('role', $role)
            ->first();
    }

    public function findBySlugEmailTemplate($slug)
    {
        return EmailTemplateModel::where('slug', $slug)->where('status','active')->first();
    }



}
