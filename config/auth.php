<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    | ... (comentarios originales sin cambios) ...
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    | ... (comentarios originales sin cambios) ...
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // --- INICIO: GUARD 'sanctum' AÑADIDO ---
        // Define cómo funciona el guard 'sanctum' (usando el driver de Sanctum)
        'sanctum' => [
            'driver' => 'sanctum',
            'provider' => null, // Normalmente null para Sanctum
        ],
        // --- FIN: GUARD 'sanctum' AÑADIDO ---

        // --- INICIO: GUARD 'api' AÑADIDO Y CONFIGURADO ---
        // Define que el guard 'api' DEBE USAR el driver 'sanctum'
        'api' => [
            'driver' => 'sanctum', // <-- ¡ESTO ES LO CLAVE!
            'provider' => 'users', // Indica que los tokens/autenticación se asocian a tu modelo User
        ],
        // --- FIN: GUARD 'api' AÑADIDO Y CONFIGURADO ---

    ], // <-- Fin del array 'guards'

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    | ... (comentarios originales sin cambios) ...
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    | ... (comentarios originales sin cambios) ...
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    | ... (comentarios originales sin cambios) ...
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];