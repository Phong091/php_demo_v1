<?php

namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next, ?string $requiredRole = null): Response
    {
        $token = $this->extractToken($request);
        if (!$token) {
            return response('Unauthorized', 401);
        }

        try {
            $jwt = JwtService::fromConfig()->verifyAndDecode($token);
        } catch (\Throwable $e) {
            return response('Unauthorized', 401);
        }

        $request->attributes->set('user_id', (int)($jwt->sub ?? 0));
        $request->attributes->set('user_role', (int)($jwt->role ?? 1));

        if ($requiredRole === 'admin' && (int)($jwt->role ?? 1) !== 0) {
            return response('Forbidden', 403);
        }

        return $next($request);
    }

    private function extractToken(Request $request): ?string
    {
        $auth = $request->header('Authorization');
        if ($auth && str_starts_with($auth, 'Bearer ')) {
            return substr($auth, 7);
        }

        $cookie = $request->cookie('token');
        return $cookie ?: null;
    }
}



