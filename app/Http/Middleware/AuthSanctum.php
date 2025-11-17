<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class AuthSanctum
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken()) {
            Auth::setUser(
                Auth::guard('sanctum')->user()
            );
        }

        return $next($request);
    }
}