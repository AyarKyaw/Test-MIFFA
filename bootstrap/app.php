<?php

use App\Http\Middleware\AdminMiddleware;
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
        $middleware->trustProxies(at: '*');

        // Redirect unauthenticated users based on the request URL pattern
        $middleware->redirectTo(
            guests: function (Request $request) {
                // This catches /alumni, /alumni/verify-pending/..., /alumni/dashboard, etc.
                if ($request->is('alumni') || $request->is('alumni/*')) {
                    return route('alumni.login');
                }

                if ($request->is('admin') || $request->is('admin/*')) {
                    return route('admin.login');
                }

                return route('login');
            }
        );

        // Register custom middleware aliases
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();