<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use App\Models\Cuenta;
use App\Models\User;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Throwable;

class PurchaseController extends Controller
{
    /**
     * Procesa la compra de un carrito completo.
     */
    public function store(Request $request): JsonResponse
    {
        // Tu lógica existente para comprar desde el carrito...
    }

    /**
     * Procesa la compra inmediata de un solo producto desde la tienda.
     */
    public function buyNow(Request $request): JsonResponse
    {
        $request->validate(['product_id' => 'required|exists:productos,id']);
        
        $user = Auth::user();
        $producto = Producto::findOrFail($request->product_id);
        $precioTotal = $producto->precio;

        if ($user->saldo < $precioTotal) {
            return response()->json(['success' => false, 'message' => 'No tienes saldo suficiente para esta compra.'], 400);
        }

        DB::beginTransaction();
        try {
            // 1. Verificar y bloquear una cuenta disponible
            $cuenta = Cuenta::where('product_id', $producto->id)
                            ->where('status', 'disponible')
                            ->lockForUpdate()
                            ->first();

            if (!$cuenta) {
                throw new \Exception("Lo sentimos, no hay stock disponible para '{$producto->nombre}' en este momento.");
            }

            // 2. Crear el Pedido
            $pedido = Pedido::create([
                'user_id' => $user->id,
                'status' => 'Completado',
                'total_amount' => $precioTotal,
                'item_count' => 1,
                'payment_method' => 'Saldo Monedero (Compra Directa)'
            ]);

            // 3. Crear el PedidoItem
            PedidoItem::create([
                'pedido_id' => $pedido->id,
                'product_id' => $producto->id,
                'quantity' => 1,
                'precio_unitario' => $producto->precio,
                'subtotal' => $precioTotal,
            ]);

            // 4. Actualizar la cuenta como vendida
            $cuenta->update([
                'sold_to_user_id' => $user->id,
                'sold_at' => now(),
                'status' => 'vendida',
                'pedido_id' => $pedido->id,
            ]);

            // 5. Descontar saldo al usuario
            $user->decrement('saldo', $precioTotal);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "¡Compra de '{$producto->nombre}' realizada con éxito!",
                'redirect_url' => route('mis-compras.show', ['compra' => $pedido->id])
            ]);

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error en Compra Directa: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
