<?php

namespace App\Http\Controllers;

// 1. Imports necesarios (asegúrate que los namespaces sean correctos)
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use App\Models\Cuenta;   // Necesario para la lógica de withCount
use Illuminate\Support\Facades\Log;
// Quitamos 'use Exception;' si no la usamos directamente, usamos \Throwable en el catch

// 2. Asegúrate que esta es la ÚNICA definición de clase en este archivo
class DashboardController extends Controller
{
    /**
     * Muestra el dashboard principal de la aplicación.
     * Calcula el stock disponible real para los productos mostrados.
     */
    public function index(Request $request): View
    {
        $usuario = Auth::user();
        if (!$usuario) {
             Log::warning('DashboardController@index: Se intentó acceder sin usuario autenticado.');
             abort(403, 'No autenticado.');
        }
        $saldoUsuario = $usuario->saldo ?? 0;

        // Inicializar colecciones vacías por si falla la consulta
        $populares = collect();
        $masVendidos = collect(); // Asumiendo que usas $masVendidos en la vista

        try {
            // --- 👇 Lógica de Stock consistente con TiendaController CORREGIDO 👇 ---
            // Obtener Productos Populares CON stock disponible real
            $populares = Producto::where('es_popular', true) // O el criterio para populares
                ->withCount(['cuentas as stock_disponible_actual' => function ($query) {
                    $query->whereNull('sold_to_user_id')
                          ->where('status', 'disponible'); // <-- Lógica correcta
                }])
                ->take(4) // O el límite deseado para populares
                ->get();

            // --- 👇 Aplicar la misma lógica a Más Vendidos si es necesario 👇 ---
            // Obtener Productos Más Vendidos CON stock disponible real (si los usas en el dashboard)
            $masVendidos = Producto::orderBy('ventas', 'desc') // Asumiendo columna 'ventas'
                 ->withCount(['cuentas as stock_disponible_actual' => function ($query) {
                     $query->whereNull('sold_to_user_id')
                           ->where('status', 'disponible'); // <-- Lógica correcta
                 }])
                 ->take(5) // O el límite deseado
                 ->get();
            // --- Fin Lógica Stock ---

        } catch (\Throwable $e) { // Captura errores más generales
            Log::error('Error al obtener productos para el dashboard: ' . $e->getMessage(), ['exception' => $e]);
            // $populares y $masVendidos se mantendrán como colecciones vacías
        }

        // Pasar datos a la vista (asegúrate que los nombres coincidan con los usados en la vista)
        return view('dashboard', [
            'usuarioNombre' => $usuario->name ?? 'Usuario', // Campo 'name' del usuario
            'populares' => $populares,                 // Productos populares con stock_disponible_actual
            'masVendidos' => $masVendidos,             // Más vendidos con stock_disponible_actual
            'saldoActual' => $saldoUsuario
        ]);
    }
}