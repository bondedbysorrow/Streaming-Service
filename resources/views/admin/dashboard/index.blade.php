{{-- resources/views/admin/dashboard/index.blade.php --}}
{{-- Versión Completa: Muestra Nombre de Producto (si columna es 'name') --}}

@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Dashboard de Administración</h1>

    {{-- Fila para las tarjetas de resumen --}}
    <div class="row mb-4">
        {{-- Tarjeta: Ingresos Hoy --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-white" style="background-color: var(--primary-purple-banner);">
                <div class="card-body">
                    <h5 class="card-title">Ingresos Hoy ({{ \Carbon\Carbon::today()->format('d/m') }})</h5>
                    <p class="card-text fs-4 fw-bold">$ {{ number_format($ingresosHoy, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        {{-- Tarjeta: Cuentas Vendidas Hoy --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-white" style="background-color: var(--success-green);">
                <div class="card-body">
                    <h5 class="card-title">Cuentas Vendidas Hoy ({{ \Carbon\Carbon::today()->format('d/m') }})</h5>
                    <p class="card-text fs-4 fw-bold">{{ $cuentasVendidasHoy }}</p>
                </div>
            </div>
        </div>
         {{-- Tarjeta: Cuentas Disponibles --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card text-white" style="background-color: var(--button-secondary-bg);">
                <div class="card-body">
                    <h5 class="card-title">Cuentas Disponibles</h5>
                    <p class="card-text fs-4 fw-bold">{{ $cuentasDisponibles }}</p>
                </div>
            </div>
        </div>
         {{-- Tarjeta: Cuentas Vendidas (Total) --}}
         <div class="col-md-6 col-lg-3 mb-3">
             <div class="card text-white" style="background-color: var(--danger-red);">
                 <div class="card-body">
                     <h5 class="card-title">Cuentas Vendidas (Total)</h5>
                     <p class="card-text fs-4 fw-bold">{{ $cuentasVendidas }}</p>
                 </div>
             </div>
         </div>
    </div> {{-- Fin .row de tarjetas --}}

    {{-- Fila para otras secciones (Top Productos y Gráfico) --}}
    <div class="row">
        {{-- Columna Top Productos --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Top 5 Productos Más Vendidos (Total)</div>
                <div class="card-body">
                    @if($topProductos && $topProductos->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($topProductos as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span> {{-- Envuelve en span para mejor manejo --}}
                                        {{-- ============================================================= --}}
                                        {{-- 👇 VERIFICA ESTA LÍNEA 👇                                     --}}
                                        {{-- Accede a la propiedad 'name' del producto relacionado      --}}
                                        {{-- Si tu columna se llama diferente (ej: 'nombre'), cambia 'name' por 'nombre' --}}
                                        {{ $item->producto->name ?? 'Producto (ID: '.$item->product_id.')' }}
                                        {{-- ============================================================= --}}
                                    </span>
                                    <span class="badge bg-primary rounded-pill">{{ $item->total_vendidas }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No hay datos de ventas de productos aún.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Columna para el Gráfico de Pastel --}}
        <div class="col-md-6">
             <div class="card">
                <div class="card-header">Estado General de Cuentas</div>
                <div class="card-body" style="min-height: 300px; display: flex; justify-content: center; align-items: center;">
                    <canvas id="cuentasStatusChart"></canvas>
                </div>
            </div>
        </div>

    </div> {{-- Fin .row de secciones --}}

</div> {{-- Fin .container --}}
@endsection

{{-- Scripts --}}
@push('scripts')
{{-- Incluir Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Inicializar gráfico --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('cuentasStatusChart');
        if (ctx) {
            const disponibles = {{ $cuentasDisponibles ?? 0 }};
            const vendidas = {{ $cuentasVendidas ?? 0 }};
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: [ 'Disponibles', 'Vendidas' ],
                    datasets: [{
                        label: 'Estado de Cuentas',
                        data: [disponibles, vendidas],
                        backgroundColor: [ 'rgb(16, 185, 129)', 'rgb(229, 62, 62)' ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { color: '#ffffff' } } }
                }
            });
        } else { console.error("Elemento canvas 'cuentasStatusChart' no encontrado."); }
    });
</script>

{{-- Estilos CSS básicos para las tarjetas (ya los tenías) --}}
<style>
    .card { border: none; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0,0,0,0.1); background-color: var(--bg-dark-accent); color: var(--text-light); margin-bottom: 1.5rem; }
    .card-header { background-color: rgba(0,0,0,0.15); border-bottom: 1px solid var(--border-color); font-weight: 600; }
    .card-title { font-size: 0.95rem; font-weight: 500; margin-bottom: 0.5rem; }
    .card-text.fs-4 { font-size: 1.75rem; }
    .card-text.fw-bold { font-weight: 700; }
    .card a.card-link { font-size: 0.85rem; text-decoration: underline; }
    .list-group-item { padding: 0.75rem 1rem; background-color: transparent !important; color: inherit !important; border-color: var(--border-color) !important; }
    .list-group-item:last-child { border-bottom: none !important; }
    /* Añadir un poco de margen si el badge se pega mucho al texto */
    .list-group-item span:first-child { margin-right: 0.5rem; }
</style>
@endpush