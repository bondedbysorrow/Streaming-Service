<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse; // 👈 MEJORA: Importar para el tipado correcto

class AuthenticatedSessionController extends Controller
{
    /**
     * Muestra la vista de login.
     */
    public function create() // Se puede omitir el tipado de Response aquí si solo retornas vistas
    {
        return view('auth.login');
    }

    /**
     * Maneja una solicitud de autenticación entrante.
     */
    public function store(LoginRequest $request): JsonResponse|RedirectResponse // 👈 MEJORA: Tipado más preciso
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Si la petición es AJAX (espera JSON), respondemos con JSON.
        if ($request->wantsJson()) {
            return response()->json([
                'message' => '¡Inicio de sesión exitoso! Redirigiendo...',
                'redirect' => route('dashboard') // Ruta a la que quieres redirigir
            ]);
        }

        // ✅ MEJORA: Comportamiento para peticiones normales (no AJAX).
        // Redirige al dashboard si el login no es vía AJAX.
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destruye una sesión autenticada (logout).
     */
    public function destroy(Request $request): JsonResponse|RedirectResponse // 👈 MEJORA: Tipado más preciso
    {
        // 🚨 BUG CRÍTICO CORREGIDO: Usar logout() en lugar de login()
        Auth::guard('web')->logout(); 

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        
        // Si la petición es AJAX, respondemos con JSON y la ruta de redirección.
        if ($request->wantsJson()) {
            // ✅ MEJORA: Añadir la URL de redirección para que el frontend sepa a dónde ir.
            return response()->json([
                'message' => 'Sesión cerrada correctamente.',
                'redirect' => route('login')
            ]);
        }

        // ✅ MEJORA: Comportamiento para peticiones normales (no AJAX).
        // Redirige a la página de login.
        return redirect()->route('login');
    }
}