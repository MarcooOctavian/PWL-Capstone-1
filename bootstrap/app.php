<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->redirectUsersTo(function (Request $request) {
            if (auth()->check() && auth()->user()->role == '1') {
                return '/panel';
            }

            return '/home';
        });

    })
    ->withExceptions(function (Exceptions $exceptions) {

    })->create();
