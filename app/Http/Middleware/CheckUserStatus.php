<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Auto Inactive
            if ($user->last_login_at && \Carbon\Carbon::parse($user->last_login_at)->lt(now()->subYear())) {
                $user->status = 0;
                $user->save();
            }

            if ($user->status == 0) {
                if ($user->role == 3) {
                    if (
                        !$request->is('reactivate-user*') &&
                        !$request->is('logout')
                    ) {
                        return redirect('/reactivate-user');
                    }
                }
                if (in_array($user->role, [1, 2])) {
                    if (
                        !$request->is('reactivate*') &&
                        !$request->is('logout')
                    ) {
                        return redirect('/reactivate');
                    }
                }
            }
        }
        return $next($request);
    }
}
