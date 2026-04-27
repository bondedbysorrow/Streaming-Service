<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     * Es buena práctica definir esto.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
        // 'category_id', // Descomenta si lo añades
        // 'priority',    // Descomenta si lo añades
    ];

    /**
     * Define la relación "pertenece a" con el modelo User.
     * Un ticket pertenece a un usuario.
     */
    public function user()
    {
        // Asume que la foreign key en la tabla 'tickets' es 'user_id'
        return $this->belongsTo(User::class);
    }

    /**
     * Define la relación "tiene muchas" con el modelo Reply.
     * Un ticket puede tener muchas respuestas.
     */
    public function replies()
    {
        // Asume que la foreign key en la tabla 'replies' es 'ticket_id'
        return $this->hasMany(Reply::class);
    }

    // --- Opcional: Relación si usas categorías ---
    // public function category()
    // {
    //    return $this->belongsTo(Category::class);
    // }
}