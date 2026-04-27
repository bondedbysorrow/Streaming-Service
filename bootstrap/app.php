<?php // bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // --- 👇 Ajustar Middlewares del Grupo 'api' 👇 ---
        $middleware->group('api', [
            // 1. Middleware para manejar cookies encriptadas (normalmente global, pero asegurar aquí)
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            // 2. Middleware para añadir cookies a la respuesta (normalmente global)
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            // 3. Middleware para iniciar la sesión (necesaria para CSRF stateful)
            \Illuminate\Session\Middleware\StartSession::class,
            // 4. Middleware de Sanctum para verificar origen y cookies (debe ir DESPUÉS de los de sesión/cookie)
            EnsureFrontendRequestsAreStateful::class,
            // 5. Throttling (límite de peticiones)
            'throttle:api',
             // 6. Route Model Binding
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
        // --- --- --- --- --- --- --- --- --- --- --- ---

        // Verificar que VerifyCsrfToken NO esté en el alias 'api' si usas EnsureFrontendRequestsAreStateful
        // $middleware->alias([ ... ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();