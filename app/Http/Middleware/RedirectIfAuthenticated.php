<?php

namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        // Chỉ áp dụng cho các route guest
        $guestPaths = [
            'login',
            'register',
            'forgot-password',
            'logout',
        ];
        $path = ltrim($request->path(), '/');
        $isGuestRoute = ($path === '' || in_array($path, $guestPaths, true) || str_starts_with($path, 'reset-password'));

        if (!$isGuestRoute) {
            return $next($request);
        }

        // JWT: nếu có token hợp lệ thì redirect sang profile
        $token = $request->bearerToken() ?: $request->cookie('token');
        if ($token) {
            try {
                $jwt = JwtService::fromConfig()->verifyAndDecode($token);
                $role = (int)($jwt->role ?? 1);
                return $role === 0 ? redirect('/admin/profile') : redirect('/profile');
            } catch (\Throwable $e) {
                // ignore
            }
        }

        return $next($request);
    }
}
