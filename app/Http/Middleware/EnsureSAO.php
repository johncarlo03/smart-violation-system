<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSAO
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = (int) auth()->user()->role;

        if ($role === 4) {
            return redirect()->route('superadmin.dashboard');
        }

        if (in_array($role, [3, 6, 7])) {
            return redirect()->route('sao.dashboard');
        }

        if (in_array($role, [2, 5])) {
            return redirect()->route('cso.dashboard');
        }

        if ($role === 1) {
            return redirect()->route('dashboard');
        }

        abort(403, 'Unauthorized Role');
    }
}
