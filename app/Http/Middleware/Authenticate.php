<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Override redirect behavior for unauthenticated users.
     */
    protected function redirectTo($request): ?string
    {
        // For API routes, return JSON instead of redirecting to "login"
        if ($request->expectsJson() || $request->is('api/*')) {
            abort(response()->json(['error' => 'Unauthorized'], 401));
        }

        // Optional: return a route name if you ever add a web login
        return null;
    }
}
