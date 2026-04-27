<?php

// 1. Namespace correcto
namespace App\Http\Controllers;

// 2. Importaciones necesarias
use App\Models\Producto; // <-- Asegúrate que tu modelo esté en App\Models\Producto
use App\Models\Cuenta;   // *** NUEVO: Importar modelo Cuenta ***
use Illuminate\Http\Request; // Aunque no se use explícitamente en show, es bueno tenerla por si acaso
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

// 3. Nombre de clase correcto
class ProductoApiController extends Controller
{
    /**
     * Muestra los detalles de un producto específico para la API, incluyendo stock disponible.
     * Responde a la ruta GET /api/productos/{id}
     *
     * @param  int $id El ID del producto recibido desde la ruta
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse // Recibe $id directamente
    {
        Log::debug("API show: Solicitud para producto ID: {$id}"); // Log de entrada

        try {
            // Buscar el producto por ID o fallar (error 404 si no existe)
            // Usamos findOrFail para manejar el 404 automáticamente si no se encuentra
            $producto = Producto::findOrFail($id);

            Log::debug("API show: Producto encontrado ID: {$id} - Nombre: {$producto->nombre}");

            // *** NUEVO: Calcular el stock disponible ***
            $stockDisponible = 0; // Valor por defecto
            try {
                $stockDisponible = Cuenta::where('product_id', $id) // Usamos el $id directamente
                                           ->whereNull('sold_to_user_id')
                                           ->where('status', 'disponible') // Asegúrate que este sea el status correcto
                                           ->count();
                Log::debug("API show: Stock calculado para Producto ID {$id}: {$stockDisponible}");
            } catch (\Exception $e) {
                // Loggear error si falla el conteo, pero continuar para devolver detalles del producto
                Log::error("API show: Error al calcular stock para Producto ID {$id}. Error: " . $e->getMessage());
                // $stockDisponible se quedará en 0 (o podrías poner null o -1 si prefieres)
            }
            // *** FIN NUEVO ***

            // --- Construir la respuesta JSON Específica ---
            // ¡¡ASEGÚRATE que los nombres $producto->atributo coincidan con tu Modelo Producto!!
            $responseData = [
                'id'                => $producto->id,
                'nombre'            => $producto->nombre,
                'precio'            => isset($producto->precio) ? (float) $producto->precio : null, // Precio numérico
                'precio_formateado' => $producto->precio_formateado ?? (isset($producto->precio) ? '$ ' . number_format($producto->precio, 0, ',', '.') : 'No disponible'), // Precio formateado (usa accesor si existe)
                'imagen_url'        => $producto->imagen_display_url ?? $producto->imagen_url ?? asset('images/placeholder-product.png'), // Imagen (usa accesor si existe)
                'descripcion_corta' => $producto->descripcion_corta ?? 'Descripción no disponible.',
                // --- 👇 CAMBIO PRINCIPAL 👇 ---
                'stock_disponible'  => $stockDisponible, // Añadir el stock calculado
                // --- Fin Cambio ---
                // 'descripcion_larga' => $producto->descripcion_larga ?? null, // Añadir si existe
                // Otros campos que necesites...
            ];
            // --- Fin Construcción Respuesta ---

            Log::debug("API show: Enviando respuesta para Producto ID {$id}");
            // Devolver la respuesta JSON
            return response()->json($responseData);

        } catch (ModelNotFoundException $e) {
            // Error si findOrFail no encuentra el producto
            Log::warning("API show: Producto no encontrado ID: {$id}");
            return response()->json(['message' => 'Producto no encontrado.'], 404);

        } catch (\Throwable $e) {
            // Cualquier otro error inesperado durante la búsqueda del producto o construcción inicial
            Log::error("API show: Error buscando/procesando producto ID: {$id} - " . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Error interno al obtener detalles del producto.'], 500);
        }
    } // Fin método show

    // Aquí puedes añadir otros métodos para tu API si los necesitas (index, store, etc.)
}