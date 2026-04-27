<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cuenta extends Model
{
    protected $table = 'cuentas';

    protected $fillable = [
        'product_id',
        'pedido_id',
        'email_usuario',
        'password',
        'detalles',
        'status',
        'sold_to_user_id',
        'sold_at',
    ];

    protected $casts = [
        'sold_at' => 'datetime',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'product_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sold_to_user_id');
    }

    public function scopeDisponibles($query)
    {
        return $query->where('status', 'disponible')
                    ->whereNull('sold_to_user_id');
    }

    public function scopeVendidas($query)
    {
        return $query->where('status', 'vendida')
                    ->whereNotNull('sold_to_user_id');
    }

    public function marcarComoVendida($userId)
    {
        $this->update([
            'status' => 'vendida',
            'sold_to_user_id' => $userId,
            'sold_at' => now()
        ]);
    }
}
