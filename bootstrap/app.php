<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register your middleware aliases here
        $middleware->alias([
            'auth.check' => \App\Http\Middleware\CheckAuth::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'client' => \App\Http\Middleware\ClientMiddleware::class,
        ]);

        // Timeout handler must run first so it can register the shutdown function
        $middleware->prepend(\App\Http\Middleware\HandleTimeout::class);

        // Add global middleware (runs on every request)
        $middleware->append([
            \App\Http\Middleware\SetUserType::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Intercept PHP execution-timeout fatal errors and show a clean modal page
        $exceptions->render(function (\Symfony\Component\ErrorHandler\Error\FatalError $e, \Illuminate\Http\Request $request) {
            if (str_contains($e->getMessage(), 'Maximum execution time')) {
                return response()->view('errors.timeout', [], 503)
                    ->header('Retry-After', '30');
            }
        });

        // If CSRF token expired on the logout route, log out gracefully instead of showing 419
        $exceptions->render(function (TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->routeIs('logout')) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/login')->with('success', 'You have been logged out.');
            }
        });
    })->create();