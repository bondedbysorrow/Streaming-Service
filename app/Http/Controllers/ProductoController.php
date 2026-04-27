<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Cuenta;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ProductoController extends Controller
{
    /**
     * Búsqueda AJAX para productos
     */
    public function searchAjax(Request $request): JsonResponse
    {
        $query = $request->input('query', '');
        
        if (strlen(trim($query)) < 2) {
            return response()->json([]);
        }

        try {
            $resultados = Producto::where('activo', true)
                ->where(function($q) use ($query) {
                    $q->where('nombre', 'LIKE', "%{$query}%")
                      ->orWhere('descripcion_corta', 'LIKE', "%{$query}%");
                })
                ->withCount(['cuentasDisponibles as stock'])
                ->select(['id', 'nombre', 'imagen_url', 'precio'])
                ->take(8)
                ->get();

            $resultados_formateados = $resultados->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'imagen_url' => $producto->imagen_display_url,
                    'precio' => $producto->precio,
                    'precio_formateado' => $producto->precio_formateado,
                    'stock' => $producto->stock ?? 0
                ];
            });

            return response()->json($resultados_formateados);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda AJAX: ' . $e->getMessage());
            return response()->json(['error' => 'Error en la búsqueda'], 500);
        }
    }

    /**
     * Mostrar detalles de un producto específico
     */
    public function showApi(Producto $producto): JsonResponse
    {
        try {
            // Verificar que el producto esté activo
            if (!$producto->activo) {
                return response()->json(['message' => 'Producto no disponible'], 404);
            }

            // Calcular stock disponible con cache para optimizar
            $cacheKey = "producto_stock_{$producto->id}";
            $stockDisponible = Cache::remember($cacheKey, 300, function () use ($producto) {
                return $producto->cuentasDisponibles()->count();
            });

            Log::debug("API showApi: Stock calculado para Producto ID {$producto->id}: {$stockDisponible}");

            return response()->json([
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'descripcion_corta' => $producto->descripcion_corta,
                'imagen_url' => $producto->imagen_display_url,
                'precio_formateado' => $producto->precio_formateado,
                'precio' => $producto->precio,
                'stock_disponible' => $stockDisponible,
                'es_popular' => $producto->es_popular,
                'activo' => $producto->activo
            ]);

        } catch (\Exception $e) {
            Log::error("API showApi: Error para Producto ID {$producto->id}. Error: " . $e->getMessage());
            return response()->json(['message' => 'Error al obtener producto'], 500);
        }
    }

    /**
     * Limpiar cache de stock cuando sea necesario
     */
    public function clearStockCache($productoId)
    {
        Cache::forget("producto_stock_{$productoId}");
    }
}
