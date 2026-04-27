{{-- resources/views/soporte/show.blade.php --}}
{{-- Vista para que el USUARIO vea el detalle de SU ticket --}}

@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Enlace para volver al listado de tickets del usuario --}}
    <div class="mb-3">
        <a href="{{ route('soporte.index') }}" class="btn btn-secondary btn-sm">&laquo; Volver a Mis Tickets</a>
    </div>

    {{-- Mensajes Flash (si aplican) --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Ticket #{{ $ticket->id }}: {{ $ticket->subject }}</h4>
            {{-- Badge de Estado --}}
            <span>
                 @if($ticket->status == 'Abierto')
                    <span class="badge bg-success">{{ $ticket->status }}</span>
                @elseif($ticket->status == 'Respondido')
                    <span class="badge bg-info text-dark">{{ $ticket->status }}</span>
                @elseif($ticket->status == 'Cerrado')
                    <span class="badge bg-secondary">{{ $ticket->status }}</span>
                @else
                    <span class="badge bg-warning text-dark">{{ $ticket->status }}</span>
                @endif
            </span>
        </div>
        <div class="card-body">
            <p><strong>Fecha Creación:</strong> {{ $ticket->created_at->format('d/m/Y H:i:s') }}</p>
            <p><strong>Última Actualización:</strong> {{ $ticket->updated_at->format('d/m/Y H:i:s') }}</p>
            <hr>

            {{-- Conversación --}}
            <h5 class="mb-3">Conversación</h5>

            {{-- Mensaje Original --}}
            {{-- Aplicamos la clase 'user-message' y quitamos el estilo en línea --}}
            <div class="message user-message mb-3 p-3 rounded">
                {{-- Añadida clase 'message-body' --}}
                <p class="message-body mb-1">{!! nl2br(e($ticket->message)) !!}</p>
                <small class="text-muted">Tú el {{ $ticket->created_at->format('d/m/Y H:i') }}</small>
            </div>

            {{-- Respuestas --}}
            @forelse ($ticket->replies->sortBy('created_at') as $reply)
                @php
                    $isAdminReply = $reply->user_id !== $ticket->user_id; // O $reply->user->is_admin
                    // $bgColor = $isAdminReply ? '#d1e7dd' : '#e9ecef'; // Color manejado por CSS ahora
                    $textAlign = 'text-start'; // Siempre a la izquierda para el usuario
                    $authorName = $isAdminReply ? ($reply->user->name ?? 'Soporte') : 'Tú';
                    $messageClass = $isAdminReply ? 'admin-message' : 'user-message'; // Clase para diferenciar
                @endphp
                {{-- Aplicamos la clase correspondiente y quitamos el estilo en línea --}}
                <div class="message mb-3 p-3 rounded {{ $textAlign }} {{ $messageClass }}">
                    {{-- Añadida clase 'message-body' --}}
                    <p class="message-body mb-1">{!! nl2br(e($reply->message)) !!}</p>
                    <small class="text-muted">
                        {{ $authorName }} el {{ $reply->created_at->format('d/m/Y H:i') }}
                        @if($isAdminReply && $reply->user && $reply->user->is_admin) <span class="badge bg-primary ms-1" style="font-size: 0.7em;">Admin</span> @endif
                    </small>
                </div>
            @empty
                {{-- No mostramos nada explícito si no hay respuestas, el flujo sigue --}}
            @endforelse

             @if ($ticket->status == 'Cerrado')
                <div class="alert alert-secondary text-center mt-3">Este ticket está cerrado.</div>
             @endif

        </div> {{-- Fin card-body --}}
    </div> {{-- Fin card --}}

</div> {{-- Fin .container --}}
@endsection