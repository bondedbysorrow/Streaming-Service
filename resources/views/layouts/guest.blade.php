<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- 👇 Eliminamos @vite y añadimos enlace a tu CSS estático 👇 --}}
        <link href="{{ asset('css/estilos.css') }}" rel="stylesheet">

        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}} {{-- <- ELIMINADO/COMENTADO --}}

    </head>
    {{-- 👇 Quitamos clases Tailwind del body, añadimos una clase genérica (opcional) 👇 --}}
    <body class="guest-layout-body">
         {{-- 👇 Quitamos clases Tailwind del div contenedor, añadimos una clase genérica 👇 --}}
         {{--    Asegúrate que 'guest-layout-container' tenga estilos en estilos.css
               para centrar el contenido (ej: display:flex, align-items, justify-content, min-height) --}}
        <div class="guest-layout-container">
            <div>
                <a href="/">
                    {{-- Mantenemos el logo, puedes estilizarlo con CSS si es necesario --}}
                    <x-application-logo class="logo-guest" />
                     {{-- Cambié la clase Tailwind por una genérica 'logo-guest' --}}
                </a>
            </div>

             {{-- 👇 Quitamos clases Tailwind del div 'card', añadimos una clase genérica 👇 --}}
             {{--    Asegúrate que 'guest-card' tenga estilos en estilos.css
                    para darle apariencia de tarjeta (fondo, padding, sombra, bordes redondeados) --}}
            <div class="guest-card">
                {{-- Aquí se inyecta el contenido del login, registro, etc. --}}
                {{ $slot }}
            </div>
        </div>

        {{-- 👇 Añadimos enlace a tu JS estático antes de cerrar el body 👇 --}}
        <script src="{{ asset('js/script.js') }}" defer></script>
    </body>
</html>