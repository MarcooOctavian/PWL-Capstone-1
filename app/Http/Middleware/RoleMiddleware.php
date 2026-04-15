<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('/login-admin');
        }

        if (empty($roles)) {
            $roles = [1, 2];
        }

        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Akses ditolak');
        }
        return $next($request);
    }
}
