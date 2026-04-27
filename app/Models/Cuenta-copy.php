<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// Importar Producto y User si no están ya en el namespace
// use App\Models\Producto;
// use App\Models\User;

// Asegúrate de que SÓLO esté esta definición de clase en este archivo
class Cuenta extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'cuentas';

    /**
     * Los atributos asignables masivamente.
     */
    protected $fillable = [
        'product_id',
        'email_usuario',
        'password',
        'detalles',
        'status',
        'sold_to_user_id',
        'sold_at',
    ];

    /**
     * Relación con Producto.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'product_id');
    }

    /**
     * Relación con User.
     */
    public function vendidoA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sold_to_user_id');
    }
} // <-- Fin de la CLASE CUENTA (No debe haber otra definición de clase después)