{{-- resources/views/admin/soporte/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Enlace para volver al listado --}}
    <div class="mb-3">
        <a href="{{ route('admin.soporte.index') }}" class="btn btn-secondary btn-sm">&laquo; Volver al Listado</a>
    </div>

    {{-- Mensajes de éxito/info --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="row">
        {{-- Columna Principal: Detalles y Conversación --}}
        <div class="col-md-8">

            {{-- Detalles del Ticket --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Ticket #{{ $ticket->id }} - {{ $ticket->subject }}</h4>
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
                    <p><strong>Creado por:</strong> {{ $ticket->user->name ?? 'N/A' }} ({{ $ticket->user->email ?? 'N/A' }})</p>
                    <p><strong>Fecha Creación:</strong> {{ $ticket->created_at->format('d/m/Y H:i:s') }}</p>
                    <p><strong>Última Actualización:</strong> {{ $ticket->updated_at->format('d/m/Y H:i:s') }}</p>
                    <hr>
                    {{-- Mensaje Original del Usuario --}}
                    <h5>Mensaje Original:</h5>
                    {{-- Aplicamos la clase 'user-message' y quitamos el estilo en línea --}}
                    <div class="message user-message p-3 rounded mb-3">
                        {{-- Añadida clase 'message-body' para posible estilo específico --}}
                        <p class="message-body mb-1">{!! nl2br(e($ticket->message)) !!}</p>
                        <small class="text-muted">Enviado por {{ $ticket->user->name ?? 'Usuario' }} el {{ $ticket->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
            </div>

            {{-- Conversación (Respuestas) --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Conversación</h5>
                </div>
                <div class="card-body">
                    @forelse ($ticket->replies->sortBy('created_at') as $reply) {{-- Usamos forelse --}}
                        @php
                            $isUserReply = $reply->user_id === $ticket->user_id;
                            // $bgColor = $isUserReply ? '#e9ecef' : '#d1e7dd'; // Color manejado por CSS ahora
                            $textAlign = $isUserReply ? 'text-start' : 'text-end';
                            $authorName = $reply->user->name ?? ($isUserReply ? 'Usuario' : 'Admin');
                            $messageClass = $isUserReply ? 'user-message' : 'admin-message'; // Clase para diferenciar
                        @endphp
                        {{-- Aplicamos la clase correspondiente y quitamos el estilo en línea --}}
                        <div class="message mb-3 p-3 rounded {{ $textAlign }} {{ $messageClass }}">
                             {{-- Añadida clase 'message-body' para posible estilo específico --}}
                            <p class="message-body mb-1">{!! nl2br(e($reply->message)) !!}</p>
                            <small class="text-muted">
                                Enviado por {{ $authorName }} el {{ $reply->created_at->format('d/m/Y H:i') }}
                                @if($reply->user && $reply->user->is_admin) <span class="badge bg-primary ms-1" style="font-size: 0.7em;">Admin</span> @endif
                            </small>
                        </div>
                    @empty
                        <p>No hay respuestas aún.</p>
                    @endforelse
                </div>
            </div>

             {{-- Formulario de Respuesta del Admin --}}
             @if ($ticket->status != 'Cerrado')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Responder al Ticket</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.soporte.reply', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="message" class="form-label">Tu Respuesta:</label>
                                <textarea name="message" id="message" rows="5" class="form-control @error('message') is-invalid @enderror" required></textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar Respuesta</button>
                        </form>
                    </div>
                </div>
             @else
                 <div class="alert alert-secondary text-center">Este ticket está cerrado.</div>
             @endif

        </div> {{-- Fin Columna Principal --}}

        {{-- Columna Lateral: Acciones / Cambiar Estado --}}
        <div class="col-md-4">
            <div class="card">
                 <div class="card-header">
                    <h5 class="mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.soporte.status', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label">Cambiar Estado:</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="Abierto" {{ $ticket->status == 'Abierto' ? 'selected' : '' }}>Abierto</option>
                                <option value="Respondido" {{ $ticket->status == 'Respondido' ? 'selected' : '' }}>Respondido</option>
                                <option value="Cerrado" {{ $ticket->status == 'Cerrado' ? 'selected' : '' }}>Cerrado</option>
                            </select>
                             @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Actualizar Estado</button>
                    </form>
                </div>
            </div>
        </div> {{-- Fin Columna Lateral --}}

    </div> {{-- Fin .row --}}

</div> {{-- Fin .container --}}
@endsection