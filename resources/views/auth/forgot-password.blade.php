<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Restablecer Contraseña - STstreaming">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logoauf.ico') }}" type="image/x-icon">
    <title>Restablecer Contraseña - STstreaming</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <style>
        .error-message { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; }
        .form-group { margin-bottom: 1rem; }
        /* Estilo opcional para el enlace si envuelve un botón, si no usas botón no es necesario */
        .caja-trasera-button-link { text-decoration: none; }
    </style>
</head>
<body>
    <main>
        <div class="contenedor__todo">

            {{-- ========================================== --}}
            {{-- INICIO: CONTENIDO ACTUALIZADO PANEL TRASERO --}}
            {{-- ========================================== --}}
            <div class="caja__trasera">
                {{-- Usamos la misma estructura de clases por si tu CSS depende de ellas --}}
                <div class="caja__trasera-login active">
                    <div class="prompt-content-wrapper">
                         {{-- Título más apropiado --}}
                         <h3>Recuperación de Cuenta</h3>
                         {{-- Mensaje simple --}}
                         <p>Por favor, ingresa tu correo electrónico en el panel derecho para iniciar el proceso de restablecimiento de contraseña.</p>
                         {{-- Eliminamos el botón de "Iniciar Sesión" de aquí --}}
                    </div>
               </div>
            </div>
            {{-- ======================================== --}}
            {{-- FIN: CONTENIDO ACTUALIZADO PANEL TRASERO --}}
            {{-- ======================================== --}}


            {{-- Panel Principal (Formulario) - Sin cambios respecto a la versión anterior --}}
            <div class="contenedor__login-register">
                <form method="POST" action="{{ route('password.email') }}" class="formulario__forgot-password active">
                    @csrf
                    <div class="form-content-wrapper">
                        <h2>Restablecer Contraseña</h2>
                        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400" style="text-align: center; margin-bottom: 20px; color: var(--text-muted);">
                            ¿Olvidaste tu contraseña? No hay problema. Indícanos tu dirección de correo electrónico y te enviaremos un enlace para restablecer la contraseña que te permitirá elegir una nueva.
                        </div>
                        @if (session('status'))
                            <div class="success-message" style="margin-bottom: 15px;" aria-live="polite">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="email" style="display: block; margin-bottom: 5px;">Correo Electrónico</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                   placeholder="Ingresa tu correo"
                                   autocomplete="email"
                                   aria-describedby="email-error">
                            @error('email')
                                <span class="error-message" id="email-error" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div style="text-align: center; margin-top: 25px;">
                             <button type="submit" class="btn-submit">
                                Enviar Enlace de Restablecimiento
                             </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </main>
    <script src="{{ asset('js/script.js') }}" defer></script>
</body>
</html>