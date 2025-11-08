<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnlyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = (int)($request->attributes->get('user_role') ?? 1);
        if ($role !== 0) {
            return response('Forbidden', 403);
        }
        return $next($request);
    }
}



