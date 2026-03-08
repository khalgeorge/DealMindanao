<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'    => \App\Http\Middleware\AdminMiddleware::class,
            'honeypot' => \App\Http\Middleware\VerifyHoneypot::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
        // 419 CSRF / Session Expired — redirect gracefully instead of showing
        // the default Laravel "Page Expired" view.
        //
        $exceptions->render(function (
            \Illuminate\Session\TokenMismatchException $e,
            \Illuminate\Http\Request $request
        ) {
            $message = 'Your session expired. Please log in again to continue.';

            if ($request->is('admin/*') || $request->is('admin')) {
                // Admin URL: decide destination by auth state.
                //
                // Case 1 — auth session still valid, only CSRF is stale.
                //   → redirect()->route('admin.login') would hit middleware('guest')
                //     and bounce the user to home. Send to dashboard instead;
                //     the page will reload with a fresh CSRF token.
                if ($request->user()?->is_admin) {
                    return redirect()->route('admin.dashboard')
                        ->with('warning', $message);
                }

                // Case 2 — session fully expired (user() === null).
                //   → safe to redirect to admin login; no auth = guest middleware passes.
                $intended = $request->headers->get(
                    'referer',
                    route('admin.dashboard')
                );
                session()->put('url.intended', $intended);

                return redirect()->route('admin.login')
                    ->with('warning', $message);
            }

            // Public user (authenticated or guest) → homepage.
            return redirect()->route('home')
                ->with('warning', $message);
        });
    })->create();
