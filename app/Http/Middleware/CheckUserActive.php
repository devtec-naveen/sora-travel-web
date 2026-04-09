<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('web')->user();

        if ($user && $user->status !== 'active') {
            if ($request->expectsJson()) {
               /** @var \Laravel\Sanctum\PersonalAccessToken|null $token */
                $token = $user->currentAccessToken();
                $token?->delete();
                return response()->json([
                    'status'  => false,
                    'message' => 'Your account has been deactivated by admin.',
                ], 401);
            }

            Auth::guard('web')->logout();
            $request->session()->regenerateToken();
            
            if ($request->routeIs('my-*') || $request->routeIs('airport.*')) {
                return redirect()->route('home')
                    ->with('error', 'Your account has been deactivated by admin.');
            }

            return $next($request);
        }
        return $next($request);
    }
}