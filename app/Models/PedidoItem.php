<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PedidoItem extends Model
{
    protected $table = 'pedido_items';

    protected $fillable = [
        'pedido_id',
        'product_id',
        'quantity',
        'precio_unitario',
        'subtotal',
    ];

    public $timestamps = true;

    /**
     * Relación con Pedido
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * Relación con Producto
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'product_id');
    }

    /**
     * ✅ *** CORRECCIÓN CLAVE ***
     * Define la relación: un PedidoItem tiene UNA Cuenta asociada.
     * Esto asume que en tu tabla 'cuentas' hay una columna 'pedido_item_id'
     * que se llena cuando la cuenta es vendida.
     *
     * @return HasOne
     */
    public function cuenta(): HasOne
    {
        return $this->hasOne(Cuenta::class, 'pedido_item_id');
    }
}
