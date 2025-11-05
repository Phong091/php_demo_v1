<?php

namespace App\Http\Middleware;

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
        ];
        $path = ltrim($request->path(), '/');
        if ($path === '' || in_array($path, $guestPaths, true) || str_starts_with($path, 'reset-password')) {
            return $next($request);
        }

        $userId = $request->session()->get('user_id');

        // Restore session từ remember cookie nếu có
        if (!$userId && $request->hasCookie('remember_user')) {
            $userId = $request->cookie('remember_user');
            $request->session()->put('user_id', $userId);
        }

        if (!$userId) {
            return redirect('/login');
        }

        return $next($request);
    }
}
