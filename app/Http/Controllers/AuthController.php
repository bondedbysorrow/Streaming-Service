<?php

namespace App\Http\Controllers;

use App\Models\User; // Asegúrate de importar User si lo usas en otros métodos
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Importa Hash si lo usas (ej. en register si no fuera redundante)
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Verified; // Importa Verified si lo usas en verifyEmail


class AuthController extends Controller
{
    /**
     * Muestra el formulario de login.
     */
    public function showLogin()
    {
        // Considera usar la vista 'welcome' si ahí tienes ambos formularios
        // return view('welcome');
        // O la vista específica si existe:
        return view('auth.login');
    }

    /**
     * Maneja el intento de inicio de sesión.
     * Esta es la implementación estándar y correcta.
     */
    public function login(Request $request)
    {
        // 1. Validación de entrada
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Intento de autenticación (Verifica email y hash de contraseña)
        // El segundo argumento opcional es para "Recordarme"
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // 3. Éxito: Autenticación correcta
            $request->session()->regenerate(); // Regenera la sesión por seguridad

            // Redirige al destino intentado o al dashboard
            return redirect()->intended('/dashboard'); // Asegúrate que la ruta /dashboard exista
        }

        // 4. Falla: Autenticación incorrecta
        // Devuelve al usuario a la página anterior (login)
        return back()->withErrors([
            // Usa la clave del campo (email) para mostrar el error junto a ese campo
            'email' => __('auth.failed'), // Mensaje estándar de Laravel: "These credentials do not match our records."
        ])->onlyInput('email'); // Devuelve solo el email, no la contraseña, al formulario
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/'); // Redirige a la página principal
    }

    /**
     * Maneja el registro de un nuevo usuario.
     * IMPORTANTE: Este método es REDUNDANTE si estás usando RegisteredUserController
     * para las rutas de registro en web.php. Considera eliminarlo de aquí
     * si no lo estás llamando desde ninguna ruta.
     */
    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);
    //     Auth::login($user);
    //     return redirect('/dashboard');
    // }

    /**
     * Maneja la petición de restablecimiento de contraseña (enviar enlace).
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Restablece la contraseña del usuario.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }

     /**
     * Verifica la dirección de correo electrónico del usuario.
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended('/dashboard?verified=1');
        }

        // La verificación de hash puede variar ligeramente en versiones muy recientes de Laravel
        // pero esta lógica o la integrada en VerifyEmailController debería funcionar.
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Enlace de verificación inválido o expirado.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended('/dashboard?verified=1')->with('status', 'Correo verificado exitosamente.');
    }
}