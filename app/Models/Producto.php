<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Cuenta;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion_corta',
        'precio',
        'imagen_url',
        'es_popular',
        'ventas_count',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'es_popular' => 'boolean',
        'ventas_count' => 'integer',
    ];

    public function getPrecioFormateadoAttribute(): string
    {
        return number_format($this->precio ?? 0, 0, ',', '.');
    }

    public function getImagenDisplayUrlAttribute(): string
    {
        $filename = $this->imagen_url;
        if (!$filename) {
            return asset('images/placeholder-product.png');
        }
        
        $publicPath = 'images/productos/' . $filename;
        if (file_exists(public_path($publicPath))) {
            return asset($publicPath);
        } else {
            return asset('images/placeholder-product.png');
        }
    }

    // Stock basado en cuentas disponibles
    public function getStockDisponibleAttribute(): int
    {
        return $this->cuentas()
            ->where('status', 'disponible')
            ->whereNull('sold_to_user_id')
            ->count();
    }

    // --- RELACIONES ---

    public function cuentas(): HasMany
    {
        return $this->hasMany(Cuenta::class, 'product_id');
    }

    public function cuentasDisponibles(): HasMany
    {
        return $this->hasMany(Cuenta::class, 'product_id')
            ->where('status', 'disponible')
            ->whereNull('sold_to_user_id');
    }

    public function cuentasVendidas(): HasMany
    {
        return $this->hasMany(Cuenta::class, 'product_id')
            ->where('status', 'vendida')
            ->whereNotNull('sold_to_user_id');
    }

    public function scopePopular($query)
    {
        return $query->where('es_popular', true);
    }

    public function scopeConStock($query)
    {
        return $query->whereHas('cuentas', function($q) {
            $q->where('status', 'disponible')
              ->whereNull('sold_to_user_id');
        });
    }
}
