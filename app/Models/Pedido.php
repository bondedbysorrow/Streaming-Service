<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    /**
     * ✅ CORRECCIÓN: Asegurarse de que el nombre de la tabla sea 'pedidos' (plural).
     */
    protected $table = 'pedidos';

    protected $fillable = [
        'user_id',
        'total_amount',
        'item_count',
        'status',
        'payment_method'
    ];

    /**
     * Define la relación: un Pedido pertenece a un Usuario.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * ✅ *** CORRECCIÓN CLAVE ***
     * Define la relación: un Pedido tiene muchas Cuentas.
     * Laravel buscará en la tabla 'cuentas' todos los registros
     * que tengan este 'pedido_id'.
     */
    public function cuentas(): HasMany
    {
        return $this->hasMany(Cuenta::class, 'pedido_id');
    }
}
