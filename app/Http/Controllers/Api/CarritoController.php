<?php

// Ruta: app/Http/Controllers/Api/CarritoController.php

namespace App\Http\Controllers\Api; // <-- Namespace CON Api

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// No necesitamos Auth aquí si usamos $request->user()
use App\Models\CartItem;
use App\Models\Producto; // Para verificar existencia o stock si es necesario
use Illuminate\Http\JsonResponse; // Para tipado de retorno
use Illuminate\Support\Facades\Log; // Para logs si quieres depurar
use Illuminate\Database\QueryException; // Para capturar errores de BD

// Nombre de clase CORRECTO (puede ser igual porque está en otro Namespace)
class CarritoController extends Controller
{
    /**
     * Almacena un nuevo item en el carrito o actualiza la cantidad si ya existe.
     * Asociado a la ruta POST /api/carrito
     */
    public function store(Request $request): JsonResponse
    {
        Log::info('===== API /carrito store INICIO =====');
        Log::info('Request Data:', $request->all());

        $validatedData = $request->validate([
            'product_id' => 'required|exists:productos,id',
            'quantity' => 'required|integer|min:1'
        ]);
        Log::info('Datos validados OK.');

        $user = $request->user(); // Obtiene usuario autenticado vía API (Sanctum)

        if (!$user) {
            Log::warning('API /carrito store: Usuario NO autenticado.');
            return response()->json(['message' => 'No autenticado.'], 401);
        }
        Log::info('Usuario autenticado ID: ' . $user->id);

        $productId = $validatedData['product_id'];
        $quantity = $validatedData['quantity'];

        // --- Opcional: Descomentar y adaptar para verificar stock ---
        // try {
        //     // Necesitas cargar el producto CON su stock calculado (cuentas_count)
        //     $producto = Producto::withCount(['cuentas' => fn($q) => $q->where('status', 'disponible')], 'cuentas_count')->find($productId);
        //     if (!$producto) { // Doble check por si acaso
        //          return response()->json(['message' => 'Producto no encontrado.'], 404);
        //     }
        //     $stockDisponible = $producto->cuentas_count ?? 0;
        //
        //     $itemExistente = CartItem::where('user_id', $user->id)->where('product_id', $productId)->first();
        //     $cantidadActualEnCarrito = $itemExistente ? $itemExistente->quantity : 0;
        //
        //     if ($stockDisponible < ($cantidadActualEnCarrito + $quantity)) {
        //         Log::warning("API /carrito store: Stock insuficiente para Producto ID: {$productId}. Stock: {$stockDisponible}, Intentando añadir: {$quantity}");
        //         return response()->json(['message' => 'Stock insuficiente'], 400); // 400 Bad Request
        //     }
        // } catch (\Exception $e) {
        //      Log::error("API /carrito store: Error al verificar stock para Producto ID: {$productId}. Error: " . $e->getMessage());
        //      return response()->json(['message' => 'Error al verificar stock.'], 500);
        // }
        // --- Fin Verificación Stock (Opcional) ---


        try {
            Log::info("Buscando item existente para User ID: {$user->id}, Producto ID: {$productId}");
            $cartItem = CartItem::where('user_id', $user->id)
                                ->where('product_id', $productId)
                                ->first();

            if ($cartItem) {
                Log::info("Item encontrado (ID: {$cartItem->id}). Actualizando cantidad: {$cartItem->quantity} + {$quantity}");
                $cartItem->quantity += $quantity;
                try {
                    $cartItem->save();
                    Log::info("Item (ID: {$cartItem->id}) actualizado OK.");
                    $statusCode = 200;
                    $message = 'Cantidad actualizada en el carrito.';
                } catch (QueryException $e) {
                    Log::error("Error DB al ACTUALIZAR CartItem: " . $e->getMessage());
                    return response()->json(['message' => 'Error al actualizar item en BD.'], 500);
                }
            } else {
                Log::info("Item no encontrado. Creando nuevo registro...");
                try {
                    $cartItem = CartItem::create([
                        'user_id' => $user->id,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                    ]);
                    Log::info("Nuevo CartItem creado con ID: {$cartItem->id}");
                    $statusCode = 201;
                    $message = 'Producto añadido al carrito!';
                } catch (QueryException $e) {
                    Log::error("Error DB al CREAR CartItem: " . $e->getMessage());
                    return response()->json(['message' => 'Error al crear item en BD.'], 500);
                }
            }

            Log::info("Respondiendo con éxito. Status: {$statusCode}, Message: {$message}");
            return response()->json([
                'message' => $message,
                'cartItem' => $cartItem->load('product')
            ], $statusCode);

        } catch (\Exception $e) {
             Log::error("Error General en API /carrito store: " . $e->getMessage(), ['exception' => $e]);
             return response()->json(['message' => 'Error interno del servidor.'], 500);
        } finally {
            Log::info('===== API /carrito store FIN =====');
        }
    }

    // Otros métodos API...
}