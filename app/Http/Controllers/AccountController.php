<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Pedido;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    /**
     * Muestra la página "Mis Pedidos" del usuario.
     */
    public function index(): View
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        Log::info("AccountController@index: Buscando pedidos para User ID: {$user->id}");

        $pedidos = Pedido::where('user_id', $user->id)
                         ->orderBy('created_at', 'desc')
                         ->paginate(15);

        Log::info("AccountController@index: Se encontraron {$pedidos->total()} pedidos para User ID: {$user->id}");

        return view('cuenta.index', [
            'pedidos' => $pedidos,
            'usuario' => $user
        ]);
    }

    /**
     * ✅ *** MÉTODO AÑADIDO Y CORREGIDO ***
     * Muestra los detalles de un pedido específico.
     *
     * @param Pedido $pedido
     * @return View
     */
    public function show(Pedido $pedido): View
    {
        // 1. Seguridad: Asegurarse de que el usuario solo vea sus propios pedidos
        if (auth()->id() !== $pedido->user_id) {
            abort(403, 'Acceso no autorizado.');
        }

        // 2. Carga de relaciones anidadas (Eager Loading)
        // Esta es la corrección clave. Carga los 'items' y, para cada item, carga la 'cuenta' asociada.
        // Esto asume que en tu modelo PedidoItem tienes una relación llamada `cuenta()`.
        $pedido->load('items.cuenta');

        // 3. Devolver la vista con los datos del pedido
        // Asumo que tienes una vista en 'resources/views/cuenta/show.blade.php'
        return view('cuenta.show', compact('pedido'));
    }
}
