<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\User; // Se mantiene la importación del modelo User
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class TiendaController extends Controller
{
    public function index(Request $request)
    {
        // Se mantiene tu lógica de consulta original
        $query = Producto::query();

        // --- Tus Filtros (Sin cambios) ---
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion_corta', 'LIKE', "%{$search}%");
            });
        }
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->get('categoria_id'));
        }
        if ($request->filled('precio_min')) {
            $query->where('precio', '>=', $request->get('precio_min'));
        }
        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->get('precio_max'));
        }
        if ($request->filled('populares')) {
            $query->where('es_popular', true);
        }

        // --- Tu Lógica de Ordenamiento (Sin cambios) ---
        $orderBy = $request->get('order_by', 'latest');
        switch ($orderBy) {
            case 'precio_asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio', 'desc');
                break;
            case 'nombre':
                $query->orderBy('nombre', 'asc');
                break;
            case 'populares':
                $query->orderBy('es_popular', 'desc')->orderBy('ventas_count', 'desc');
                break;
            default:
                $query->latest();
        }

        // Paginación (Sin cambios)
        $productos = $query->paginate(12)->withQueryString();
        
        // Variables que tu vista necesita (Sin cambios)
        $categorias = Categoria::orderBy('nombre')->get();
        $productosPopulares = Producto::where('es_popular', true)
            ->whereHas('cuentas', function($q) {
                $q->where('status', 'disponible')
                  ->whereNull('sold_to_user_id');
            })
            ->limit(6)
            ->get();

        // --- ✅ CORRECCIÓN: OBTENER ESTADÍSTICAS REALES SIN LA COLUMNA 'ROLE' ---
        $totalProductos = Producto::count(); // Cuenta todos los productos
        $totalClientes = User::count();      // Cuenta todos los usuarios registrados. Esto soluciona el error.

        // --- Devolver todas las variables a la vista, incluyendo las nuevas ---
        return view('tienda.index', compact(
            'productos', 
            'categorias', 
            'productosPopulares',
            'totalProductos',
            'totalClientes'
        ));
    }

    /**
     * Se mantiene tu método getProducto original para no afectar
     * la lógica del stock que ya tenías implementada.
     */
    public function getProducto($id): JsonResponse
    {
        try {
            $producto = Producto::findOrFail($id);
            
            return response()->json([
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'descripcion_corta' => $producto->descripcion_corta ?? 'Cuenta premium de alta calidad',
                'precio' => $producto->precio,
                'stock_disponible' => $producto->stock_disponible, // Se mantiene tu lógica de stock
                'imagen_url' => $producto->imagen_display_url,
                'es_popular' => $producto->es_popular
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Producto no encontrado',
                'message' => 'El producto solicitado no existe o no está disponible.'
            ], 404);
        }
    }
}
