<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    /**
     * Redirige al usuario a la página de autenticación del proveedor.
     */
    public function redirectToProvider($provider)
    {
        // Esta función redirige al usuario a la página de login de Google, GitHub, etc.
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtiene la información del usuario desde el proveedor después de la autenticación.
     */
    public function handleProviderCallback($provider)
    {
        try {
            // Obtenemos los datos del usuario desde la red social
            $socialUser = Socialite::driver($provider)->user();

            // Usamos updateOrCreate para buscar por email o crear un nuevo usuario si no existe.
            // Es más eficiente y seguro que un if/else.
            $user = User::updateOrCreate(
                [
                    'email' => $socialUser->getEmail(),
                ],
                [
                    'name' => $socialUser->getName(),
                    // ✅ CORRECCIÓN: Usamos Hash::make y Str::random para crear una contraseña segura.
                    'password' => Hash::make(Str::random(24)),
                    // Como el email viene de un proveedor confiable, lo marcamos como verificado.
                    'email_verified_at' => now(),
                ]
            );

            // Inicia sesión con el usuario encontrado o recién creado.
            Auth::login($user);

            // Redirige al dashboard.
            return redirect('/dashboard');

        } catch (\Exception $e) {
            // Si algo sale mal (ej: el usuario cancela en la página de Google),
            // lo redirigimos a la página principal con un mensaje de error.
            return redirect('/')->with('social_error', 'No se pudo autenticar. Por favor, inténtalo de nuevo.');
        }
    }
}
