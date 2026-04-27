<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Para obtener el usuario autenticado
use App\Models\Ticket; // Asegúrate de crear este modelo
use App\Models\Reply;   // Asegúrate de crear este modelo si vas a manejar respuestas aquí
// Si quieres validación más avanzada, puedes crear un Form Request:
// use App\Http\Requests\StoreTicketRequest;

class SoporteController extends Controller
{
    /**
     * Muestra una lista de los tickets del usuario autenticado.
     * Corresponde a la ruta GET /soporte (soporte.index)
     */
    public function index()
    {
        // Obtener los tickets del usuario logueado, ordenados por más reciente
        $tickets = Ticket::where('user_id', Auth::id())
                         ->latest() // Ordena por created_at DESC
                         ->paginate(10); // Pagina los resultados

        // Retorna la vista 'soporte.index' pasándole los tickets
        return view('soporte.index', compact('tickets'));
    }

    /**
     * Muestra el formulario para crear un nuevo ticket.
     * Corresponde a la ruta GET /soporte/crear (soporte.create)
     */
    public function create()
    {
        // Simplemente muestra la vista con el formulario
        return view('soporte.create');
        // Podrías pasarle categorías si las tuvieras:
        // $categories = Category::all();
        // return view('soporte.create', compact('categories'));
    }

    /**
     * Guarda un nuevo ticket en la base de datos.
     * Corresponde a la ruta POST /soporte (soporte.store)
     * Si usas Form Request, cambia Request a StoreTicketRequest
     */
    public function store(Request $request) // Cambiar 'Request' por 'StoreTicketRequest' si lo creas
    {
        // --- Validación ---
        // Puedes usar un Form Request (recomendado) o validar aquí:
        $validatedData = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            // 'category_id' => 'nullable|exists:categories,id', // Ejemplo si tuvieras categorías
        ]);

        // --- Creación del Ticket ---
        $ticket = new Ticket();
        $ticket->user_id = Auth::id(); // Asigna el ID del usuario logueado
        $ticket->subject = $validatedData['subject'];
        $ticket->message = $validatedData['message'];
        $ticket->status = 'Abierto'; // Estado inicial por defecto
        // $ticket->category_id = $validatedData['category_id'] ?? null; // Si usas categorías

        $ticket->save(); // Guarda el ticket en la base de datos

        // --- Redirección ---
        // Redirige al listado de tickets con un mensaje de éxito
        return redirect()->route('soporte.index')
                         ->with('success', '¡Tu ticket de soporte ha sido creado exitosamente!');
                         // También podrías redirigir a soporte.show con el ticket recién creado:
                         // return redirect()->route('soporte.show', $ticket->id)->with('success', '...');
    }

    /**
     * Muestra un ticket específico y sus detalles (y opcionalmente respuestas).
     * Corresponde a la ruta GET /soporte/{ticket} (soporte.show)
     *
     * @param  int  $id El ID del ticket a mostrar
     */
    public function show($id) // Laravel inyectará el ID del ticket desde la ruta {ticket}
    {
        // Busca el ticket por ID o falla (muestra error 404 si no existe)
        $ticket = Ticket::findOrFail($id);

        // --- Autorización ---
        // Asegúrate de que el usuario autenticado pueda ver este ticket
        // (Debe ser el dueño del ticket o un administrador/agente)
        // Ejemplo básico: solo el dueño puede ver
        if ($ticket->user_id !== Auth::id()) {
             // Si no es el dueño, muestra un error 403 (Prohibido)
             // En una app real, podrías usar Gates o Policies para manejar esto mejor
             // y permitir acceso a administradores.
            abort(403, 'No tienes permiso para ver este ticket.');
        }

        // --- Cargar Respuestas (Opcional) ---
        // Si quieres mostrar las respuestas, cárgalas aquí.
        // Carga ansiosa (eager loading) para evitar problemas N+1: carga las respuestas Y el usuario de cada respuesta
        // $ticket->load('replies.user');

        // Retorna la vista 'soporte.show' pasándole el ticket encontrado
        return view('soporte.show', compact('ticket'));
    }

    /**
     * (Opcional) Añade una respuesta a un ticket existente.
     * Corresponde a la ruta POST /soporte/{ticket}/responder (soporte.reply)
     */
    // public function addReply(Request $request, $id)
    // {
    //     $ticket = Ticket::findOrFail($id);
    //
    //     // Autorización (similar a show, o usar Policy)
    //     if ($ticket->user_id !== Auth::id() /* && !Auth::user()->isAdmin() */ ) {
    //         abort(403);
    //     }
    //
    //     $validated = $request->validate([
    //         'message' => 'required|string|min:5',
    //     ]);
    //
    //     $reply = new Reply();
    //     $reply->ticket_id = $ticket->id;
    //     $reply->user_id = Auth::id(); // Quién responde
    //     $reply->message = $validated['message'];
    //     $reply->save();
    //
    //     // Cambiar estado del ticket si es necesario (ej: si responde un admin, poner "Respondido")
    //     // if (Auth::user()->isAdmin()) {
    //     //    $ticket->status = 'Respondido';
    //     //    $ticket->save();
    //     // } else {
            // // Si responde el cliente, quizás ponerlo "Pendiente de Agente" o algo así
            // $ticket->status = 'Respuesta Cliente';
            // $ticket->save();
    //     // }
    //
    //     // Notificar al usuario/admin correspondiente (Opcional)
    //
    //     return redirect()->route('soporte.show', $ticket->id)
    //                      ->with('success', 'Respuesta añadida.');
    // }

}