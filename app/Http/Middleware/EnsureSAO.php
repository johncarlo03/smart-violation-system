<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSAO
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->role !== 3) {
            return redirect('/dashboard');
        } else if(auth()->user()->role === 1) {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}
