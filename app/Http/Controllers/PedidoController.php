<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Cuenta;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    // ... otros métodos que tengas ...

    /**
     * Obtiene los detalles de un pedido para una solicitud AJAX.
     *
     * @param  Request $request
     * @param  int  $id El ID del pedido
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPedidoDetailsAjax(Request $request, $id)
    {
        try {
            // ✅ CORRECCIÓN: Se asegura de cargar las relaciones correctamente.
            // 'cuentas' es la relación en el modelo Pedido que apunta a las Cuentas.
            // 'cuentas.producto' carga la relación 'producto' definida en el modelo Cuenta.
            $pedido = Pedido::with(['cuentas.producto'])
                            ->where('user_id', Auth::id())
                            ->findOrFail($id);

            // Se construye la respuesta JSON
            $formattedPedido = [
                'id' => $pedido->id,
                'status' => $pedido->status,
                'total_amount' => $pedido->total_amount,
                'payment_method' => $pedido->payment_method,
                'created_at_formatted' => $pedido->created_at->format('d/m/Y'),
                'updated_at_formatted' => $pedido->updated_at->format('d/m/Y H:i'),
                'total_amount_formatted' => number_format($pedido->total_amount ?? 0, 0, ',', '.'),
                'items' => $pedido->cuentas->map(function ($cuenta) {
                    // ✅ CORRECCIÓN: Se usa el operador nullsafe (?->) para evitar errores
                    // si un producto asociado a una cuenta ha sido eliminado o no se encuentra.
                    $productName = $cuenta->producto?->nombre ?? 'Producto no encontrado';
                    $unitPrice = $cuenta->producto?->precio_es_popular ?? 0;

                    return [
                        'id' => $cuenta->id,
                        'product_name' => $productName,
                        'quantity' => 1, // Cada cuenta es un item individual
                        'unit_price' => $unitPrice,
                        'subtotal' => $unitPrice, // Para una cuenta individual, subtotal = precio unitario
                        'email_usuario' => $cuenta->email_usuario,
                        'password' => $cuenta->password,
                        'detalles' => $cuenta->detalles,
                    ];
                })
            ];

            return response()->json($formattedPedido);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Intento de acceso a pedido no encontrado o no autorizado. Pedido ID: {$id}, Usuario ID: " . Auth::id());
            return response()->json(['message' => 'Pedido no encontrado o no tienes permiso para verlo.'], 404);
        } catch (\Exception $e) {
            Log::error("Error al obtener detalles del pedido {$id}: " . $e->getMessage());
            return response()->json(['message' => 'Error interno del servidor. Por favor, contacta a soporte.'], 500);
        }
    }

    // ... otros métodos que tengas ...
}
