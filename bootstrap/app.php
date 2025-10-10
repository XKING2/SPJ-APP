<?php

use App\Http\Middleware\SessionTimeout;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 🧩 Tambahkan middleware global dan alias di sini
        $middleware->web(append: [
            // Jika kamu ingin session timeout berlaku di semua route web, bisa aktifkan ini:
            SessionTimeout::class,
        ]);

        $middleware->alias([
            // Middleware untuk autentikasi bawaan Laravel
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,

            // Middleware kustom untuk peran user
            'role' => RoleMiddleware::class,

            // Middleware kustom untuk sesi per user
            'session.timeout' => SessionTimeout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
