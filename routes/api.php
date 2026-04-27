<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoApiController; // Para detalles de producto
use App\Http\Controllers\PedidoController;    // <-- **AÑADIDO:** Importar PedidoController
use App\Http\Controllers\CartController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Rutas para llamadas AJAX o aplicaciones externas. Prefijo /api/
*/

// --- RUTA PÚBLICA (o con su propio middleware si es necesario) ---
// Obtener detalles de un producto (usado por el modal de compra rápida)
Route::get('/productos/{id}', [ProductoApiController::class, 'show'])
    ->where('id', '[0-9]+')
    ->name('api.productos.show');


// --- RUTAS PROTEGIDAS (Requieren autenticación API, ej. Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {

    // Obtener datos del usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('api.user');
    
    // Obtener saldo del usuario autenticado
    Route::get('/user/balance', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'balance' => $user->saldo ?? 0,
            'success' => true
        ]);
    })->name('api.user.balance');
    Route::middleware('auth:sanctum')->post('/verificar-cuentas', [CartController::class, 'verificarCuentas']);
    // --- 👇 NUEVA RUTA AÑADIDA PARA DETALLES DE PEDIDO (JSON) 👇 ---
    Route::get('/pedidos/{pedido}', [PedidoController::class, 'showApi'])
         ->where('pedido', '[0-9]+') // Asegurar ID numérico para el pedido
         ->name('api.pedidos.show'); // Nombre para la ruta API de detalles del pedido

});