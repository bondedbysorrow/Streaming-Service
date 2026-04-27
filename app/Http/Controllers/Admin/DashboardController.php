<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuenta;    // Asegúrate que se llame así
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Cuentas Vendidas Hoy
        $cuentasVendidasHoy = Cuenta::where('status', 'vendida')
                                    ->whereDate('sold_at', $today)
                                    ->count();

        // Cuentas Disponibles (Total)
        $cuentasDisponibles = Cuenta::where('status', 'disponible')->count();

        // --- 👇 CÁLCULO AÑADIDO 👇 ---
        // Cuentas Vendidas (Total) - Para el gráfico
        $cuentasVendidas = Cuenta::where('status', 'vendida')->count();
        // --- 👆 FIN CÁLCULO AÑADIDO 👆 ---

        // Top 5 Productos Más Vendidos
        $topProductos = Cuenta::select('product_id', DB::raw('count(*) as total_vendidas'))
                               ->where('status', 'vendida')
                               ->groupBy('product_id')
                               ->orderBy('total_vendidas', 'desc')
                               ->with('producto')
                               ->take(5)
                               ->get();

        // Ingresos Hoy
        // Reemplaza 'precio' si tu columna se llama diferente
        $ingresosHoy = Cuenta::join('productos', 'cuentas.product_id', '=', 'productos.id')
                             ->where('cuentas.status', 'vendida')
                             ->whereDate('cuentas.sold_at', $today)
                             ->sum('productos.precio') ?? 0;

        // Pasamos los datos a la vista, incluyendo el nuevo conteo
        return view('admin.dashboard.index', compact(
            'cuentasVendidasHoy',
            'cuentasDisponibles',
            'cuentasVendidas', // <-- Pasar la nueva variable
            'topProductos',
            'ingresosHoy'
        ));
    }
}