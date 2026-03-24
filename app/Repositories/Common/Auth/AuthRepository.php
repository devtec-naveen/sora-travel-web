<?php

namespace App\Repositories\Common\Auth;

use App\Models\User;
use App\Models\EmailTemplateModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByEmailAndRole(string $email, string $role): ?User
    {
        return User::where('email', $email)->where('role', $role)->first();
    }

    public function createOrUpdatePendingUser(array $data): User
    {
        $user = User::where('email', $data['email'])->first();

        if ($user && $user->status === 'active') {
            return $user;
        }

        $expireTime = config('mail.otp_expire_time', 10);
        $otp        = (string) random_int(100000, 999999);

        if ($user) {
            $user->update([
                'name'         => $data['name'],
                'password'     => Hash::make($data['password']),
                'phone_number' => $data['phone_number'] ?? null,
                'tc'           => $data['tc'] ?? false,
                'otp'          => $otp,
                'otp_expires_at' => now()->addMinutes($expireTime),
                'status'       => 'inactive',
            ]);
        } else {
            $user = User::create([
                'name'           => $data['name'],
                'email'          => $data['email'],
                'password'       => Hash::make($data['password']),
                'phone_number'   => $data['phone_number'] ?? null,
                'tc'             => $data['tc'] ?? false,
                'otp'            => $otp,
                'otp_expires_at' => now()->addMinutes($expireTime),
                'status'         => 'inactive',
            ]);
        }

        return $user->fresh();
    }

    public function activateUser(User $user): User
    {
        $user->update([
            'otp'            => null,
            'otp_expires_at' => null,
            'status'         => 'active',
        ]);

        return $user->fresh();
    }

    public function findEmailTemplate(string $slug): ?object
    {
        return EmailTemplateModel::where('slug', $slug)->first();
    }

    public function upsertPasswordResetToken(string $email): string
    {
        $plainToken = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token'      => Hash::make($plainToken),
                'created_at' => now(),
            ]
        );

        return $plainToken;
    }
}