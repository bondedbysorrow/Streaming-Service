<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
    ];

    /**
     * Define la relación "pertenece a" con el modelo User.
     * Una respuesta pertenece a un usuario (quien la escribió).
     */
    public function user()
    {
        // Asume que la foreign key en la tabla 'replies' es 'user_id'
        return $this->belongsTo(User::class);
    }

    /**
     * Define la relación "pertenece a" con el modelo Ticket.
     * Una respuesta pertenece a un ticket.
     */
    public function ticket()
    {
        // Asume que la foreign key en la tabla 'replies' es 'ticket_id'
        return $this->belongsTo(Ticket::class);
    }
}