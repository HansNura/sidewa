<?php

use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\IpWhitelist;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware: IP whitelist check on every request
        $middleware->append(IpWhitelist::class);

        $middleware->alias([
            'role' => EnsureUserHasRole::class,
            'ip.whitelist' => IpWhitelist::class,
        ]);

        // Redirect unauthenticated users to the correct login page based on guard
        $middleware->redirectGuestsTo(function ($request) {
            // If the route uses the 'warga' guard, redirect to warga login
            $guards = $request->route()?->middleware() ?? [];

            foreach ($guards as $m) {
                if (str_contains($m, 'auth:warga')) {
                    return route('layanan.mandiri.login');
                }
            }

            // Default: admin/staff login
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
