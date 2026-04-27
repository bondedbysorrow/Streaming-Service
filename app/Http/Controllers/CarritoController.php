<?php

namespace App\Http\Controllers; // Namespace correcto

// --- Imports necesarios ---
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Producto; // Asegúrate que este modelo exista y tenga la relación 'product' en CartItem
use App\Models\Cuenta;    // Asegúrate que este modelo exista
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class CarritoController extends Controller
{
    /**
     * Muestra la vista del carrito con los items actuales y el total.
     * Responde a GET /carrito
     */
    public function index(): View
    {
        $user = Auth::user();
        // Eager load 'product' para evitar N+1 queries en la vista y en el cálculo del total
        $cartItems = $user->cartItems()->with('product')->get();

        // Calcular el total
        $total = $cartItems->sum(function ($item) {
            // Asegurarse que el producto y su precio existen antes de calcular
            return ($item->product && isset($item->product->precio))
                   ? $item->product->precio * $item->quantity
                   : 0; // Si no hay producto o precio, no suma
        });

        Log::info("Mostrando carrito para User ID: {$user->id}. Items: {$cartItems->count()}, Total: {$total}");

        return view('carrito.index', compact('cartItems', 'total'));
    } // --- FIN index() ---

    /**
     * Añade un item al carrito o actualiza cantidad (vía AJAX), VERIFICANDO STOCK.
     * Responde a POST /carrito
     */
    public function store(Request $request): JsonResponse
    {
        Log::info('CarritoController@store: Inicio', ['data' => $request->all()]);
        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:productos,id', // Asegúrate que la tabla se llame 'productos'
                'quantity' => 'required|integer|min:1'
            ]);
            Log::info('CarritoController@store: Validación OK');

            $user = Auth::user();
            $productId = $validatedData['product_id'];
            $quantityToAdd = $validatedData['quantity'];

            // --- Verificación de Stock ---
            try {
                // Contar cuentas disponibles para ese producto
                $stockDisponible = Cuenta::where('product_id', $productId)
                                         ->whereNull('sold_to_user_id') // Que no esté vendida
                                         ->where('status', 'disponible') // Que esté marcada como disponible
                                         ->count();

                // Buscar si el item ya existe en el carrito del usuario
                $itemExistente = CartItem::where('user_id', $user->id)
                                         ->where('product_id', $productId)
                                         ->first();

                $cantidadActualEnCarrito = $itemExistente ? $itemExistente->quantity : 0;
                $cantidadTotalNecesaria = $cantidadActualEnCarrito + $quantityToAdd;

                Log::info("Stock Check - ProdID: {$productId}, Disp: {$stockDisponible}, EnCarrito: {$cantidadActualEnCarrito}, QuiereAñadir: {$quantityToAdd}, Necesario: {$cantidadTotalNecesaria}");

                // Comparar stock disponible con lo que se necesitaría EN TOTAL en el carrito
                if ($stockDisponible < $cantidadTotalNecesaria) {
                    Log::warning("CarritoController@store: Stock insuficiente. ProdID: {$productId}, Disp: {$stockDisponible}, Necesario: {$cantidadTotalNecesaria}");
                    $errorMessage = "Stock insuficiente. Solo quedan {$stockDisponible} unidades disponibles";
                    if ($cantidadActualEnCarrito > 0) { $errorMessage .= " (ya tienes {$cantidadActualEnCarrito} en el carrito)"; }
                    $errorMessage .= ".";
                    // Devolver error 400 (Bad Request) si no hay stock
                    return response()->json(['success' => false, 'message' => $errorMessage, 'current_stock' => $stockDisponible], 400);
                }
                 Log::info("Stock Check OK para ProdID: {$productId}");
            } catch (\Exception $e) {
                 Log::error("Error DURANTE verificación de stock en CarritoController@store: " . $e->getMessage());
                 return response()->json(['success' => false, 'message' => 'Error al verificar stock.'], 500);
            }
            // --- Fin Verificación de Stock ---

            // --- Guardar/Actualizar Item ---
            try {
                 $cartItem = $itemExistente;
                 $message = '';
                 $statusCode = 200; // OK por defecto (actualización)

                 if ($cartItem) {
                     // Si ya existe, solo actualiza la cantidad
                     Log::info("Actualizando cantidad para CartItem ID: {$cartItem->id}");
                     $cartItem->quantity += $quantityToAdd;
                     $cartItem->save();
                     $message = 'Cantidad actualizada en el carrito.';
                 } else {
                     // Si no existe, crea un nuevo item
                     Log::info("Creando nuevo CartItem para User ID: {$user->id}, Product ID: {$productId}");
                     $cartItem = CartItem::create([
                         'user_id' => $user->id,
                         'product_id' => $productId,
                         'quantity' => $quantityToAdd
                     ]);
                     $message = 'Producto añadido al carrito.';
                     $statusCode = 201; // Created
                 }
                 // Obtener el nuevo contador total de items distintos en el carrito
                 $newCount = $user->cartItems()->count();
                 Log::info("CarritoController@store: Éxito. User ID: {$user->id}, Cart Count: {$newCount}");
                 // Devolver éxito y el nuevo contador
                 return response()->json(['success' => true, 'message' => $message, 'cart_count' => $newCount], $statusCode);
            } catch (\Exception $e) {
                 Log::error("Error al GUARDAR en CarritoController@store: " . $e->getMessage());
                 return response()->json(['success' => false, 'message' => 'Error al guardar en el carrito.'], 500);
            }

        } catch (ValidationException $e) {
             Log::error('Error de Validación en CarritoController@store:', $e->errors());
             return response()->json(['success' => false, 'message' => 'Datos inválidos.', 'errors' => $e->errors()], 422);
        }
        catch (\Exception $e) {
             Log::error("Error General en CarritoController@store: " . $e->getMessage());
             return response()->json(['success' => false, 'message' => 'Ocurrió un error inesperado.'], 500);
        }
    } // --- FIN store() ---

    /**
     * Elimina un item específico del carrito (vía AJAX).
     * Responde a DELETE /carrito/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        Log::info("Solicitud DELETE para CartItem ID: {$id}");
        $user = Auth::user();

        // Busca el item asegurándose que pertenezca al usuario autenticado
        $cartItem = CartItem::where('id', $id)
                            ->where('user_id', $user->id)
                            ->first();

        // Si no se encuentra el item o no pertenece al usuario
        if (!$cartItem) {
            Log::warning("Intento de eliminar CartItem ID: {$id} (No encontrado o no pertenece a User ID: {$user->id})");
            return response()->json(['success' => false, 'message' => 'El item no se encontró en tu carrito.'], 404); // Not Found
        }

        try {
            // Guardar nombre del producto para el mensaje (si la relación existe)
            // El '??' es el operador null coalescing
            $productName = $cartItem->product->nombre ?? 'Producto';

            // Eliminar el item del carrito
            $cartItem->delete();
            Log::info("CartItem ID: {$id} eliminado exitosamente por User ID: {$user->id}");

            // ===========================================================
            //      >>> INICIO: CORRECCIÓN DEL CÁLCULO DEL TOTAL <<<
            // ===========================================================
            // Recargar la relación 'cartItems' con sus productos asociados para obtener los items restantes
            $user->load('cartItems.product');

            // Calcular el nuevo total sumando (precio * cantidad) de los items restantes
            $newTotal = $user->cartItems->sum(function ($item_restante) {
                // Comprobación robusta: Asegurarse que el item restante y su producto/precio existen
                if ($item_restante->product && isset($item_restante->product->precio)) {
                    return $item_restante->product->precio * $item_restante->quantity;
                }
                // Si falta el producto o el precio por alguna razón, no sumar este item
                Log::warning("Calculando newTotal en destroy: CartItem ID {$item_restante->id} no tiene producto o precio asociado.");
                return 0;
            });

            // Calcular el nuevo contador de items distintos
            $newCount = $user->cartItems->count();

            Log::info("Recálculo después de eliminar - User ID: {$user->id}, New Total: {$newTotal}, New Count: {$newCount}");
            // ===========================================================
            //             >>> FIN: CORRECCIÓN DEL CÁLCULO <<<
            // ===========================================================


            // Devolver respuesta JSON con éxito, mensaje, y los valores recalculados
            return response()->json([
                'success'    => true,
                'message'    => "'{$productName}' quitado del carrito.",
                'cart_total' => $newTotal, // Enviar el total CORREGIDO
                'cart_count' => $newCount,
            ]);

        } catch (\Exception $e) {
            // Registrar cualquier error durante la eliminación o recálculo
            Log::error("Error al eliminar CartItem ID: {$id}. Error: " . $e->getMessage());
            // Devolver una respuesta de error genérica
            return response()->json(['success' => false, 'message' => 'Error al quitar el producto del carrito.'], 500); // Internal Server Error
        }
    } // --- FIN destroy() ---

} // Fin clase CarritoController