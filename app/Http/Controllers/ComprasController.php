<?php

namespace App\Http\Controllers;

use App\Models\{Producto, Cuenta, Pedido, User};
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Auth, DB, Validator};
use Exception;

class ComprasController extends Controller
{
    /**
     * Procesa una compra directa (un solo producto).
     */
    public function procesarCompraDirecta(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'producto_id' => 'required|integer|exists:productos,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $user = Auth::user();
        $productoId = $request->input('producto_id');
        $quantity = $request->input('quantity');

        DB::beginTransaction();
        try {
            $producto = Producto::findOrFail($productoId);
            $total = $producto->precio * $quantity;

            if ($user->saldo < $total) {
                throw new Exception('Saldo insuficiente para completar la compra.');
            }

            $cuentas = Cuenta::disponibles()
                ->where('product_id', $producto->id)
                ->lockForUpdate()
                ->limit($quantity)
                ->get();

            if ($cuentas->count() < $quantity) {
                $stockReal = Cuenta::disponibles()->where('product_id', $producto->id)->count();
                throw new Exception("Stock insuficiente para {$producto->nombre}. Solicitas {$quantity} pero solo quedan {$stockReal}.");
            }
            
            $pedido = Pedido::create([
                'user_id' => $user->id,
                'status' => 'Completado',
                'total_amount' => $total,
                'item_count' => $quantity,
                'payment_method' => 'Saldo (Directa)',
            ]);

            foreach ($cuentas as $cuenta) {
                $cuenta->update(['pedido_id' => $pedido->id, 'status' => 'vendida', 'sold_to_user_id' => $user->id, 'sold_at' => now()]);
            }

            $user->decrement('saldo', $total);
            DB::commit();
            session(['ultimo_pedido_id' => $pedido->id]);

            return response()->json([
                'success' => true,
                'message' => '¡Compra realizada con éxito!',
                'total_pagado' => $total,
                'redirect_url' => route('compras.exitosa'),
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * ✅ === MÉTODO CORREGIDO Y ROBUSTO PARA PROCESAR EL CARRITO ===
     * Valida y procesa una transacción atómica para todos los items del carrito.
     */
    public function procesarCompraCarrito(Request $request): JsonResponse
    {
        // 1. VALIDACIÓN CORREGIDA: Espera una clave 'items' y dentro 'producto_id'.
        $validator = Validator::make($request->all(), [
            'items'             => 'required|array|min:1',
            'items.*.producto_id' => 'required|integer|exists:productos,id',
            'items.*.quantity'    => 'required|integer|min:1',
        ], [
            'items.required' => 'El carrito no puede estar vacío.',
            'items.*.producto_id.exists' => 'Uno de los productos en tu carrito ya no está disponible.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'El formato del carrito es inválido: ' . $validator->errors()->first()], 422);
        }
        
        $user = Auth::user();
        $cartItems = $request->input('items');
        $grandTotal = 0;
        $totalItemCount = 0;
        $productosAComprar = [];

        DB::beginTransaction();
        try {
            // 2. Primera pasada: Validar stock y calcular totales
            foreach ($cartItems as $item) {
                $producto = Producto::find($item['producto_id']);
                $quantity = $item['quantity'];
                
                $stockReal = Cuenta::disponibles()->where('product_id', $producto->id)->count();
                if ($stockReal < $quantity) {
                    throw new Exception("Stock insuficiente para '{$producto->nombre}'. Solicitas {$quantity} pero solo quedan {$stockReal}.");
                }

                $grandTotal += $producto->precio * $quantity;
                $totalItemCount += $quantity;
                $productosAComprar[] = ['producto' => $producto, 'quantity' => $quantity];
            }

            // 3. Validar saldo del usuario
            if ($user->saldo < $grandTotal) {
                throw new Exception("Saldo insuficiente. Necesitas " . number_format($grandTotal, 0, ',', '.') . " CLP y solo tienes " . number_format($user->saldo, 0, ',', '.') . " CLP.");
            }

            // 4. Segunda pasada: Procesar la compra
            $pedido = Pedido::create([
                'user_id' => $user->id, 'status' => 'Completado',
                'total_amount' => $grandTotal, 'item_count' => $totalItemCount,
                'payment_method' => 'Saldo (Carrito)',
            ]);

            foreach ($productosAComprar as $itemCompra) {
                $cuentas = Cuenta::disponibles()->where('product_id', $itemCompra['producto']->id)->lockForUpdate()->limit($itemCompra['quantity'])->get();
                foreach ($cuentas as $cuenta) {
                    $cuenta->update(['pedido_id' => $pedido->id, 'status' => 'vendida', 'sold_to_user_id' => $user->id, 'sold_at' => now()]);
                }
            }

            // 5. Descontar saldo
            $user->decrement('saldo', $grandTotal);
            DB::commit();
            session(['ultimo_pedido_id' => $pedido->id]);

            return response()->json([
                'success' => true,
                'message' => '¡Tu compra ha sido procesada con éxito!',
                'total_pagado' => $grandTotal,
                'redirect_url' => route('compras.exitosa')
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Muestra la página de compra exitosa.
     */
    public function compraExitosa()
    {
        $pedidoId = session('ultimo_pedido_id');
        if (!$pedidoId) {
            return redirect()->route('tienda.index')->with('error', 'No se encontró información del último pedido.');
        }

        $pedido = Pedido::where('id', $pedidoId)->where('user_id', Auth::id())->with(['cuentas.producto'])->firstOrFail();
        
        return view('compras.exitosa', compact('pedido'));
    }
}