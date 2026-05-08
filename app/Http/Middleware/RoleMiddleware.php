<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $userRole = auth()->user()->role;

        // If user role is NOT allowed, block access
        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}