<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\CartItem;
use App\Models\Ticket;
use App\Models\Reply;
use App\Models\Cuenta; // Agregado para las relaciones de compra

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'saldo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'saldo' => 'decimal:2', // Cambiado a decimal para mayor precisión monetaria
        ];
    }

    // --- RELACIONES EXISTENTES ---
    
    public function cartItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function tickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function replies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reply::class);
    }

    // --- NUEVAS RELACIONES PARA EL SISTEMA DE COMPRA ---

    /**
     * Cuentas compradas por el usuario
     */
    public function cuentasCompradas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Cuenta::class, 'sold_to_user_id');
    }

    // --- ACCESSORS Y MUTATORS ---

    /**
     * Accessor para formatear el saldo en formato de moneda
     */
    public function getSaldoFormateadoAttribute(): string
    {
        return '$' . number_format($this->saldo, 0, ',', '.');
    }

    /**
     * Verifica si el usuario tiene saldo suficiente
     */
    public function tieneSaldoSuficiente(float $monto): bool
    {
        return $this->saldo >= $monto;
    }

    /**
     * Descuenta saldo del usuario
     */
    public function descontarSaldo(float $monto): bool
    {
        if (!$this->tieneSaldoSuficiente($monto)) {
            return false;
        }

        $this->saldo -= $monto;
        return $this->save();
    }

    /**
     * Agrega saldo al usuario
     */
    public function agregarSaldo(float $monto): bool
    {
        $this->saldo += $monto;
        return $this->save();
    }
}
