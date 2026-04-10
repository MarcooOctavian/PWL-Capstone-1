<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect('/login-admin');
        }

        if (!in_array(auth()->user()->role, [1, 2])) {
            abort(403, 'Akses ditolak');
        }
        return $next($request);
    }
}
