<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class ClearProductCache
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        // Si es una compra exitosa, limpiar cache
        if ($request->route()->getName() === 'purchase.store' && $response->getStatusCode() === 200) {
            $productId = $request->input('productId');
            if ($productId) {
                Cache::forget("producto_stock_{$productId}");
            }
        }
        
        return $response;
    }
}
