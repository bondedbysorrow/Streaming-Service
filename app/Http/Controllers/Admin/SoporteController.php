<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth; // <-- AÑADIDO: Lo necesitaremos para responder

class SoporteController extends Controller
{
    /**
     * Muestra una lista de TODOS los tickets para el administrador.
     */
    public function index()
    {
        $tickets = Ticket::with('user')
                         ->latest()
                         ->paginate(15);
        return view('admin.soporte.index', compact('tickets'));
    }

    /**
     * Muestra un ticket específico al administrador y el formulario para responder.
     * Corresponde a la ruta GET /admin/soporte/{ticket} (admin.soporte.show)
     */
    public function show(Ticket $ticket) // Usamos Route Model Binding
    {
        // Carga las relaciones necesarias: el usuario que creó el ticket,
        // las respuestas y el usuario que escribió cada respuesta.
        $ticket->load('user', 'replies.user');

        // --- 👇 CAMBIO AQUÍ 👇 ---
        // Ahora retornamos la vista Blade que vamos a crear
        return view('admin.soporte.show', compact('ticket'));
        // --- 👆 FIN CAMBIO 👆 ---
    }

    /**
     * Añade una respuesta de un administrador a un ticket.
     * Corresponde a la ruta POST /admin/soporte/{ticket}/responder (admin.soporte.reply)
     */
    public function addReply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string|min:5',
        ]);

        // Creamos la respuesta asociada al TICKET
        // y al USUARIO ADMIN que está logueado actualmente
        $ticket->replies()->create([
            'user_id' => Auth::id(), // ID del admin logueado
            'message' => $request->input('message'),
        ]);

        // Opcional: Cambiar estado del ticket a 'Respondido' automáticamente
        $ticket->status = 'Respondido';
        $ticket->save();

        // Podrías añadir una notificación para el usuario aquí

        return redirect()->route('admin.soporte.show', $ticket->id)
                         ->with('success', 'Respuesta enviada correctamente.'); // Usamos 'success' para mensajes positivos
    }

     /**
     * Actualiza el estado de un ticket por parte de un administrador.
      * Corresponde a la ruta POST /admin/soporte/{ticket}/status (admin.soporte.status)
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|string|in:Abierto,Respondido,Cerrado', // Asegura que el estado sea uno de los válidos
        ]);

        $ticket->status = $request->input('status');
        $ticket->save();

        return redirect()->route('admin.soporte.show', $ticket->id)
                         ->with('success', 'Estado del ticket actualizado.');
    }
}