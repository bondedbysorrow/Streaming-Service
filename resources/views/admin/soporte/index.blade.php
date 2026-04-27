{{-- resources/views/admin/soporte/index.blade.php --}}

@extends('layouts.app') {{-- Usamos el mismo layout principal --}}

@section('content')
<div class="container">

    <h1 class="mb-4">Administración de Tickets de Soporte</h1>

    @if ($tickets->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th># Ticket</th>
                        <th>Asunto</th>
                        <th>Usuario</th> {{-- Columna para saber quién lo creó --}}
                        <th>Estado</th>
                        <th>Creado</th>
                        <th>Últ. Actualización</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Usamos forelse para manejar el caso sin tickets --}}
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>#{{ $ticket->id }}</td>
                            <td>{{ $ticket->subject }}</td>
                            <td>
                                {{-- Mostramos nombre y email del usuario (si existe) --}}
                                {{ $ticket->user->name ?? 'Usuario no encontrado' }}
                                <br>
                                <small>{{ $ticket->user->email ?? '-' }}</small>
                            </td>
                            <td>
                                {{-- Badges de estado (ajusta clases si es necesario) --}}
                                @if($ticket->status == 'Abierto')
                                    <span class="badge bg-success">{{ $ticket->status }}</span>
                                @elseif($ticket->status == 'Respondido')
                                    <span class="badge bg-info text-dark">{{ $ticket->status }}</span> {{-- text-dark para mejor contraste en bg-info --}}
                                @elseif($ticket->status == 'Cerrado')
                                    <span class="badge bg-secondary">{{ $ticket->status }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $ticket->status }}</span> {{-- text-dark para mejor contraste en bg-warning --}}
                                @endif
                            </td>
                            <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $ticket->updated_at->format('d/m/Y H:i') }}</td>
                            <td>
                                {{-- Enlace para ver los detalles (ruta de admin) --}}
                                <a href="{{ route('admin.soporte.show', $ticket->id) }}" class="btn btn-sm btn-info">Ver / Responder</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            {{-- Mensaje si no hay tickets en el sistema --}}
                            <td colspan="7" class="text-center">No hay tickets de soporte registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $tickets->links() }}
        </div>

    @else
        {{-- Mensaje si la tabla está vacía --}}
         <div class="alert alert-info">
             No hay tickets de soporte registrados.
         </div>
    @endif

</div>
@endsection