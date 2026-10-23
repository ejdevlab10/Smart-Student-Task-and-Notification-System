<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Handle expired or duplicate logout requests gracefully.
        $exceptions->render(function (
            TokenMismatchException $exception,
            Request $request
        ) {
            if (
                $request->isMethod('POST') &&
                $request->routeIs('logout')
            ) {
                return redirect()
                    ->route('login')
                    ->with(
                        'status',
                        'Your session has ended or expired. Please sign in again.'
                    );
            }

            // Allow Laravel to handle all other CSRF failures normally.
            return null;
        });

    })->create();