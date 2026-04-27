<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Adaptar la descripción y título si es necesario --}}
    <meta name="description" content="Establecer Nueva Contraseña - STstreaming">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logoauf.ico') }}" type="image/x-icon">
    <title>Establecer Nueva Contraseña - STstreaming</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    {{-- Estilos inline para errores (igual que en welcome) --}}
    <style>
        .error-message { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; }
        .form-group { margin-bottom: 1rem; } /* Asegúrate que esta clase o similar exista */
         /* Estilo opcional para el enlace si envuelve un botón, si no usas botón no es necesario */
         .caja-trasera-button-link { text-decoration: none; }
    </style>
</head>
<body>
    <main>
        <div class="contenedor__todo">

             {{-- Panel Trasero --}}
             <div class="caja__trasera">
                 {{-- Usamos la misma estructura de clases por si tu CSS depende de ellas --}}
                <div class="caja__trasera-login active">
                    <div class="prompt-content-wrapper">
                         <h3>Define tu Nueva Contraseña</h3>
                         <p>Elige una contraseña segura y confírmala en el panel derecho.</p>
                         {{-- No ponemos botones aquí --}}
                    </div>
               </div>
             </div>

             {{-- Panel Principal (Formulario) --}}
             <div class="contenedor__login-register">

                {{-- FORMULARIO PARA INGRESAR NUEVA CONTRASEÑA --}}
                {{-- Añadimos clase active para que sea visible si tu JS la requiere --}}
                <form method="POST" action="{{ route('password.store') }}" class="formulario__reset-password active">
                    @csrf
                    <div class="form-content-wrapper">

                        <h2>Establecer Nueva Contraseña</h2>

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="form-group">
                            <label for="email" style="display: block; margin-bottom: 5px;">Correo Electrónico</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                                   placeholder="Tu correo electrónico"
                                   autocomplete="username"
                                   aria-describedby="email-error">
                            @error('email')
                                <span class="error-message" id="email-error" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-top: 15px;">
                             <label for="password" style="display: block; margin-bottom: 5px;">Nueva Contraseña</label>
                            <input id="password" type="password" name="password" required
                                   placeholder="Ingresa tu nueva contraseña"
                                   autocomplete="new-password"
                                   minlength="8"
                                   aria-describedby="password-error">
                            @error('password')
                                <span class="error-message" id="password-error" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-top: 15px;">
                            <label for="password_confirmation" style="display: block; margin-bottom: 5px;">Confirmar Nueva Contraseña</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                   placeholder="Confirma tu nueva contraseña"
                                   autocomplete="new-password"
                                   minlength="8">
                            @error('password_confirmation')
                                <span class="error-message" id="password_confirmation-error" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- Botón de envío --}}
                        <div style="text-align: center; margin-top: 25px;">
                             {{-- Asegúrate que la clase btn-submit exista y tenga estilos en estilos.css --}}
                            <button type="submit" class="btn-submit">
                                Restablecer Contraseña
                            </button>
                        </div>

                        {{-- Errores generales (si no son específicos de campo) --}}
                        {{-- Usamos error-container si está definido en estilos.css --}}
                        @if ($errors->any() && !$errors->has('email') && !$errors->has('password') && !$errors->has('password_confirmation'))
                            <div class="error-container" role="alert" aria-live="assertive" style="margin-top: 15px;">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                    </div> {{-- Fin form-content-wrapper --}}
                </form>

            </div> {{-- Fin contenedor__login-register --}}

        </div> {{-- Fin contenedor__todo --}}
    </main>
    {{-- Script JS (puede o no ser necesario aquí, depende de si maneja la UI activa) --}}
    <script src="{{ asset('js/script.js') }}" defer></script>
</body>
</html>