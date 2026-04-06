<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            return route('login'); // Fortify’s login route
        }

        return null;
    }

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
