<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Asegúrate de que esta línea 'use' esté presente
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Muestra la vista de registro.
     */
    public function create()
    {
        // Retorna la vista que contiene tu formulario (ej: 'welcome' o 'auth.register')
        return view('welcome');
    }

    /**
     * Maneja una petición de registro entrante.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // Validación de los datos de entrada
        try {
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'lowercase', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ], [
                // Mensajes de error personalizados en español
                'name.required' => 'El nombre completo es obligatorio.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El formato del correo electrónico no es válido.',
                'email.lowercase' => 'El correo electrónico debe estar en minúsculas.',
                'email.unique' => 'Este correo electrónico ya está registrado.',
                'password.required' => 'La contraseña es obligatoria.',
                'password.confirmed' => 'Las contraseñas no coinciden.',
                'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            ]);
        } catch (ValidationException $e) {
            // Permite que Laravel maneje la excepción (JSON 422 para AJAX, redirect back para normal)
            throw $e;
        }

        // Creación del usuario si la validación pasa
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Dispara el evento de usuario registrado
        event(new Registered($user));

        // --- LÍNEA AÑADIDA ---
        // Inicia sesión automáticamente para el usuario recién registrado
        Auth::login($user);
        // --- FIN DE LÍNEA AÑADIDA ---

        // Define la ruta a la que se redirigirá después del éxito
        $redirectRoute = route('dashboard'); // Asegúrate de que la ruta nombrada 'dashboard' exista

        // Comprueba si la petición espera una respuesta JSON (AJAX)
        if ($request->expectsJson()) {
            // Devuelve una respuesta JSON con mensaje y URL de redirección
            return response()->json([
                'message' => '¡Registro exitoso!',
                'redirect' => $redirectRoute
            ]);
        }

        // Si no es AJAX, realiza una redirección normal
        return redirect($redirectRoute)->with('status', '¡Registro exitoso! Bienvenido.');
    }
}