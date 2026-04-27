<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Se usa el modelo para crear el usuario. El 'hashed' cast en el modelo User
            // se encargará de hashear la contraseña automáticamente.
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            // Forzamos el guardado y confirmamos la transacción.
            DB::commit();

            // Verificamos que el usuario fue creado antes de intentar el login.
            if (!$user) {
                throw new \Exception("Error: No se pudo crear el usuario.");
            }

            // Iniciamos sesión con la instancia del usuario recién creado.
            Auth::login($user);
            
            // Regeneramos la sesión por seguridad.
            $request->session()->regenerate();

            return response()->json([
                'message' => '¡Cuenta creada e sesión iniciada!',
                'redirect' => route('dashboard') // Usamos la función route() para más seguridad.
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            // Guardamos el error real en los logs para depuración.
            Log::error('Error en el registro: ' . $e->getMessage());
            
            // Devolvemos un error genérico al usuario.
            return response()->json([
                'message' => 'Ocurrió un error inesperado durante el registro. Por favor, inténtelo de nuevo.'
            ], 500);
        }
    }
    
    // ... (tu función login y logout se mantienen igual)
}

