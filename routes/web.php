<?php

use Illuminate\Support\Facades\Route;

// Controladores de la aplicación
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\SoporteController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Auth\AuthController; // Asegúrate de que esté importado

// Controladores de Administración
use App\Http\Controllers\Admin\SoporteController as AdminSoporteController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Grupo Principal de Rutas 'web'
|--------------------------------------------------------------------------
|
| La mayoría de las rutas de la aplicación deben estar aquí para recibir
| el estado de la sesión, la protección CSRF, y el manejo de cookies,
| lo cual es esencial para que la autenticación (Auth) funcione correctamente.
|
*/
Route::middleware('web')->group(function () {

    // --- RUTAS PARA INVITADOS (NO AUTENTICADOS) ---
    Route::middleware('guest')->group(function () {
        // Página de bienvenida con los formularios
        Route::get('/', function() {
            return view('welcome');
        })->name('home');

        // Rutas AJAX para procesar login y registro
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login');
            
        Route::post('/register', [AuthController::class, 'register'])
             ->middleware('throttle:5,1') // También protegemos el registro
             ->name('register');
    });

    // --- RUTAS PÚBLICAS (ACCESIBLES PARA TODOS) ---
    Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda.index');
    Route::get('/productos/buscar-ajax', [ProductoController::class, 'buscarAjax'])->name('productos.buscar-ajax');
    Route::get('/api/productos/{id}', [TiendaController::class, 'getProducto'])->name('api.producto');

    // --- RUTAS PROTEGIDAS (REQUIEREN AUTENTICACIÓN) ---
    Route::middleware(['auth', 'verified'])->group(function () {
        
        // Ruta para cerrar sesión
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // GRUPO DE RUTAS PARA EL CARRITO
        Route::prefix('carrito')->name('carrito.')->group(function () {
            Route::get('/', [CarritoController::class, 'index'])->name('index');
            Route::post('/', [CarritoController::class, 'store'])->name('store');
            Route::post('/clear', [CarritoController::class, 'clear'])->name('clear');
            Route::delete('/item/{item}', [CartController::class, 'destroy'])->name('destroy');
            Route::post('/item/update/{item}', [CartController::class, 'updateQuantity'])->name('update');
        });

        // RUTAS DE COMPRA
        Route::post('/compras/procesar', [ComprasController::class, 'procesarCompraDirecta'])->name('compras.procesar');
        Route::post('/compras/procesar-carrito', [ComprasController::class, 'procesarCompraCarrito'])->name('compras.procesar.carrito');
        Route::get('/compras/exitosa', [ComprasController::class, 'compraExitosa'])->name('compras.exitosa');
        Route::post('/api/validar-compra', [ComprasController::class, 'validarCompra'])->name('api.validar.compra');

        // RUTAS DE HISTORIAL Y GESTIÓN
        Route::get('/mis-compras', [ComprasController::class, 'index'])->name('mis-compras.index');
        Route::get('/mis-compras/{compra}', [ComprasController::class, 'show'])->where('compra', '[0-9]+')->name('mis-compras.show');
        Route::get('/mi-cuenta', [AccountController::class, 'index'])->name('cuenta.index');
        Route::get('/mi-cuenta/pedidos/{id}/details-ajax', [PedidoController::class, 'getPedidoDetailsAjax'])->name('cuenta.pedidoDetailsAjax');

        // Perfil
        Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
        Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
        Route::post('/perfil/avatar', [PerfilController::class, 'updateAvatar'])->name('perfil.avatar');
        
        // RUTAS DE SALDO
        Route::prefix('saldo')->name('saldo.')->group(function () {
            Route::get('/', [SaldoController::class, 'index'])->name('index');
            Route::post('/cargar', [SaldoController::class, 'cargar'])->name('cargar');
            Route::get('/historial', [SaldoController::class, 'historial'])->name('historial');
            Route::get('/verificar', [SaldoController::class, 'verificarSaldo'])->name('verificar');
        });

        // Pedidos
        Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');

        // Soporte Usuario
        Route::resource('soporte', SoporteController::class)->except(['edit', 'update', 'destroy']);
        Route::post('/soporte/{ticket}/reply', [SoporteController::class, 'reply'])->name('soporte.reply');

        // RUTAS DE PRODUCTOS (dentro de auth por si se necesita saber si el user está logueado para mostrar precios, etc)
        Route::prefix('productos')->name('productos.')->group(function () {
            Route::get('/', [TiendaController::class, 'index'])->name('index');
            Route::get('/{producto}', [ProductoController::class, 'show'])->name('show');
            Route::get('/categoria/{categoria}', [TiendaController::class, 'categoria'])->name('categoria');
            Route::get('/{id}/relacionados', [TiendaController::class, 'productosRelacionados'])->name('relacionados');
            Route::get('/{id}/stock', [TiendaController::class, 'verificarStock'])->name('stock');
        });

        // RUTAS API ADICIONALES
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/productos/search', [TiendaController::class, 'buscarProductos'])->name('productos.search');
            Route::get('/usuario/estadisticas', [AccountController::class, 'estadisticas'])->name('usuario.estadisticas');
        });
    });

    // Se mantiene este archivo para rutas auxiliares como reseteo de contraseña.
    require __DIR__.'/auth.php';
});


/*
|--------------------------------------------------------------------------
| Rutas de Administración (Se mantienen fuera del grupo 'web' principal si
| tienen su propio prefijo y middleware de sesión, aunque también podrían
| ir dentro. Esta estructura es válida).
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'can:view-admin-dashboard'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::prefix('cuentas')->name('cuentas.')->group(function () {
            Route::get('/', [AdminDashboardController::class, 'cuentas'])->name('index');
            Route::post('/importar', [AdminDashboardController::class, 'importarCuentas'])->name('importar');
            Route::put('/{cuenta}/status', [AdminDashboardController::class, 'cambiarStatus'])->name('status');
        });
        
        Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::get('/ventas', [AdminDashboardController::class, 'reporteVentas'])->name('ventas');
            Route::get('/stock', [AdminDashboardController::class, 'reporteStock'])->name('stock');
            Route::get('/usuarios', [AdminDashboardController::class, 'reporteUsuarios'])->name('usuarios');
        });
});

/*
|--------------------------------------------------------------------------
| Rutas de Webhook y API Externa (DEBEN estar fuera del grupo 'web'
| para evitar la verificación CSRF).
|--------------------------------------------------------------------------
*/
Route::prefix('webhook')->name('webhook.')->group(function () {
    Route::post('/pago-confirmado', [SaldoController::class, 'webhookPago'])->name('pago.confirmado');
    Route::post('/stock-actualizado', [ProductoController::class, 'webhookStock'])->name('stock.actualizado');
});

/*
|--------------------------------------------------------------------------
| Rutas de Manejo de Errores y Fallback (Generales)
|--------------------------------------------------------------------------
*/
Route::fallback(fn() => response()->view('errors.404', [], 404));
Route::get('/error/500', fn() => response()->view('errors.500', [], 500))->name('error.500');
Route::get('/error/403', fn() => response()->view('errors.403', [], 403))->name('error.403');

/*
|--------------------------------------------------------------------------
| Rutas de Desarrollo (Solo en entorno local)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::prefix('dev')->name('dev.')->group(function () {
        Route::get('/test-compra', [ComprasController::class, 'testCompra'])->name('test.compra');
        Route::get('/seed-cuentas', fn() => 'Cuentas de prueba generadas')->name('seed.cuentas');
        Route::get('/clean-test-data', fn() => 'Datos de prueba limpiados')->name('clean.test');
    });
}
