<?php
// Contenido para: app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use App\Models\{CartItem, Producto, Cuenta, Pedido, PedidoItem};
use Exception;

class CartController extends Controller
{
    /**
     * Elimina un item del carrito.
     */
    public function destroy(CartItem $item)
    {
        if ($item->user_id !== Auth::id()) abort(403);
        $item->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Actualiza la cantidad de un item en el carrito.
     */
    public function updateQuantity(Request $request, CartItem $item)
    {
        if ($item->user_id !== Auth::id()) abort(403);
        $validated = $request->validate(['quantity' => 'required|integer|min:1']);
        $item->update(['quantity' => $validated['quantity']]);
        return response()->json(['success' => true]);
    }

    /**
     * Verifica si hay cuentas disponibles para los productos en el carrito.
     */
    public function verificarCuentas(Request $request)
    {
        $items = $request->input('items', []);
        $unavailableProducts = [];

        foreach ($items as $item) {
            $disponibles = Cuenta::where('product_id', $item['product_id'])->where('status', 'disponible')->count();
            if ($disponibles < $item['quantity']) {
                $producto = Producto::find($item['product_id']);
                $unavailableProducts[] = $producto->nombre;
            }
        }
        if (!empty($unavailableProducts)) {
            return response()->json(['is_available' => false, 'message' => 'Stock insuficiente para: ' . implode(', ', $unavailableProducts)], 400);
        }
        return response()->json(['is_available' => true]);
    }

    /**
     * Procesa la compra del carrito.
     */
    public function procesarCompra(Request $request)
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tu carrito está vacío.'], 400);
        }

        $totalAmount = $cartItems->sum(fn($i) => $i->quantity * $i->product->precio);

        if ($user->saldo < $totalAmount) {
            return response()->json(['success' => false, 'message' => 'Saldo insuficiente.'], 400);
        }

        try {
            return DB::transaction(function () use ($user, $cartItems, $totalAmount) {
                // Re-verificar stock dentro de la transacción para seguridad
                foreach ($cartItems as $item) {
                    $disponibles = Cuenta::where('product_id', $item->product_id)->where('status', 'disponible')->lockForUpdate()->count();
                    if ($disponibles < $item->quantity) {
                        throw new Exception('Stock para ' . $item->product->nombre . ' no disponible. La compra ha sido cancelada.');
                    }
                }

                // 1. Crear el Pedido general
                $pedido = Pedido::create([
                    'user_id' => $user->id, 
                    'total_amount' => $totalAmount, 
                    'status' => 'Completado', 
                    'item_count' => $cartItems->sum('quantity'), 
                    'payment_method' => 'Saldo Monedero'
                ]);
                
                // 2. Asignar cuentas y crear items del pedido
                foreach ($cartItems as $item) {
                    $cuentasAsignadas = Cuenta::where('product_id', $item->product_id)->where('status', 'disponible')->lockForUpdate()->limit($item->quantity)->get();
                    
                    foreach ($cuentasAsignadas as $cuenta) {
                        $cuenta->update(['status' => 'vendida', 'pedido_id' => $pedido->id, 'sold_to_user_id' => $user->id, 'sold_at' => now()]);
                    }

                    PedidoItem::create([
                        'pedido_id' => $pedido->id, 
                        'product_id' => $item->product_id, 
                        'quantity' => $item->quantity, 
                        'precio_unitario' => $item->product->precio, 
                        'subtotal' => $item->quantity * $item->product->precio
                    ]);
                }
                
                // 3. Actualizar saldo y vaciar carrito
                $user->decrement('saldo', $totalAmount);
                $user->cartItems()->delete();
                
                // --- MEJORA IMPLEMENTADA ---
                // Devolvemos una URL de redirección a la nueva página de detalles de la compra.
                return response()->json([
                    'success' => true, 
                    'message' => '¡Compra realizada con éxito!', 
                    'redirect_url' => route('mis-compras.show', ['compra' => $pedido->id])
                ]);
            });
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}