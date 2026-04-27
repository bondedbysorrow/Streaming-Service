<?php // config/cors.php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // <-- Asegúrate que 'api/*' esté aquí
    'allowed_methods' => ['*'],
    'allowed_origins' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'http://localhost:8000')), // <-- Usa la variable de .env o pon tu URL directamente
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // <-- ¡MUY IMPORTANTE que sea true!
];