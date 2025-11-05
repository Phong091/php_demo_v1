<?php

namespace App\Http\Middleware;

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
        ];
        $path = ltrim($request->path(), '/');
        $isGuestRoute = ($path === '' || in_array($path, $guestPaths, true) || str_starts_with($path, 'reset-password'));

        if (!$isGuestRoute) {
            return $next($request);
        }

        // Đồng bộ logic với AuthMiddleware: khôi phục session từ cookie nếu có
        if (!$request->session()->has('user_id') && $request->hasCookie('remember_user')) {
            $request->session()->put('user_id', $request->cookie('remember_user'));
        }

        if ($request->session()->has('user_id')) {
            return redirect('/profile');
        }

        return $next($request);
    }
}
