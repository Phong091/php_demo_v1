<?php

namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Bỏ qua các route guest
        $guestPaths = [
            'login',
            'register',
            'forgot-password',
            'logout',
        ];
        $path = ltrim($request->path(), '/');
        if ($path === '' || in_array($path, $guestPaths, true) || str_starts_with($path, 'reset-password')) {
            return $next($request);
        }

        $token = $request->bearerToken() ?: $request->cookie('token');
        if (!$token) {
            return redirect('/login');
        }
        try {
            $jwt = JwtService::fromConfig()->verifyAndDecode($token);
        } catch (\Throwable $e) {
            return redirect('/login');
        }

        $userId = (int)($jwt->sub ?? 0);
        $userRole = (int)($jwt->role ?? 1);

        $request->attributes->set('user_id', $userId);
        $request->attributes->set('user_role', $userRole);

        if (str_starts_with($path, 'admin/')) {
            if ($userRole !== 0) {
                return redirect('/profile');
            }
        } else {
            if ($userRole === 0) {
                return redirect('/admin/profile');
            }
        }

        return $next($request);
    }
}