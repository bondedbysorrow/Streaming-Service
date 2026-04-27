{{-- resources/views/compras/index.blade.php --}}
@extends('layouts.app')

@section('content')
    {{-- Hero de Mis Compras --}}
    <section class="compras-hero">
        <div class="hero-content">
            <h1 class="hero-title">
                <i class="fas fa-shopping-bag hero-icon"></i>
                Mis Compras
            </h1>
            <p class="hero-subtitle">Historial completo de todas tus compras y pedidos realizados</p>
            <div class="hero-stats">
                <div class="stat-item">
                    <i class="fas fa-receipt"></i>
                    <span>{{ $pedidos->total() }} pedidos realizados</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-clock"></i>
                    <span>Última compra: {{ $pedidos->first()?->created_at?->diffForHumans() ?? 'Nunca' }}</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Lista de Compras --}}
    @if($pedidos->count() > 0)
        <div class="compras-grid">
            @foreach($pedidos as $pedido)
                <div class="compra-card">
                    {{-- Header de la compra --}}
                    <div class="compra-header">
                        <div class="compra-id">
                            <i class="fas fa-hashtag"></i>
                            Pedido #{{ $pedido->id }}
                        </div>
                        <div class="compra-status">
                            @switch($pedido->status)
                                @case('Completado')
                                    <span class="status-badge completado">
                                        <i class="fas fa-check-circle"></i> Completado
                                    </span>
                                    @break
                                @case('Pendiente')
                                    <span class="status-badge pendiente">
                                        <i class="fas fa-clock"></i> Pendiente
                                    </span>
                                    @break
                                @case('Procesando')
                                    <span class="status-badge procesando">
                                        <i class="fas fa-cog fa-spin"></i> Procesando
                                    </span>
                                    @break
                                @default
                                    <span class="status-badge otro">
                                        <i class="fas fa-info-circle"></i> {{ $pedido->status }}
                                    </span>
                            @endswitch
                        </div>
                    </div>

                    {{-- Contenido de la compra --}}
                    <div class="compra-content">
                        <div class="compra-info">
                            <div class="info-item">
                                <i class="fas fa-dollar-sign"></i>
                                <div>
                                    <span class="info-label">Total</span>
                                    <span class="info-value">${{ number_format($pedido->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <i class="fas fa-box"></i>
                                <div>
                                    <span class="info-label">Productos</span>
                                    <span class="info-value">{{ $pedido->item_count }} {{ $pedido->item_count == 1 ? 'producto' : 'productos' }}</span>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <i class="fas fa-credit-card"></i>
                                <div>
                                    <span class="info-label">Método de Pago</span>
                                    <span class="info-value">{{ $pedido->payment_method ?? 'Saldo' }}</span>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <i class="fas fa-calendar"></i>
                                <div>
                                    <span class="info-label">Fecha</span>
                                    <span class="info-value">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Productos del pedido --}}
                        @if($pedido->items && $pedido->items->count() > 0)
                            <div class="productos-pedido">
                                <h4 class="productos-title">
                                    <i class="fas fa-list"></i>
                                    Productos comprados
                                </h4>
                                <div class="productos-lista">
                                    @foreach($pedido->items->take(3) as $item)
                                        <div class="producto-item">
                                            <div class="producto-info">
                                                <span class="producto-email">{{ $item->email_usuario }}</span>
                                                <span class="producto-password">{{ Str::mask($item->password, '*', 2) }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($pedido->items->count() > 3)
                                        <div class="productos-mas">
                                            <i class="fas fa-plus"></i>
                                            {{ $pedido->items->count() - 3 }} más...
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Footer de la compra --}}
                    <div class="compra-footer">
                        <div class="compra-tiempo">
                            <i class="fas fa-clock"></i>
                            {{ $pedido->created_at->diffForHumans() }}
                        </div>
                        
                        <div class="compra-acciones">
                            @if($pedido->status === 'Completado')
                                <button class="btn-ver-detalles" data-pedido-id="{{ $pedido->id }}">
                                    <i class="fas fa-eye"></i>
                                    Ver Detalles
                                </button>
                            @endif
                            
                            @if($pedido->status === 'Pendiente')
                                <button class="btn-contactar-soporte" data-pedido-id="{{ $pedido->id }}">
                                    <i class="fas fa-headset"></i>
                                    Contactar Soporte
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginación Premium --}}
        @if($pedidos->hasPages())
            <div class="pagination-container">
                <div class="pagination-wrapper">
                    <ul class="pagination">
                        {{-- Botón anterior --}}
                        @if ($pedidos->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">‹‹</span></li>
                        @else
                            <li class="page-item"><a href="{{ $pedidos->previousPageUrl() }}" class="page-link">‹‹</a></li>
                        @endif

                        {{-- Números de página --}}
                        @php
                            $start = max($pedidos->currentPage() - 2, 1);
                            $end = min($start + 4, $pedidos->lastPage());
                            $start = max($end - 4, 1);
                        @endphp

                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $pedidos->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                            @else
                                <li class="page-item"><a href="{{ $pedidos->url($i) }}" class="page-link">{{ $i }}</a></li>
                            @endif
                        @endfor

                        {{-- Botón siguiente --}}
                        @if ($pedidos->hasMorePages())
                            <li class="page-item"><a href="{{ $pedidos->nextPageUrl() }}" class="page-link">››</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">››</span></li>
                        @endif
                    </ul>
                </div>
                
                <div class="pagination-info">
                    <i class="fas fa-info-circle"></i>
                    Mostrando <strong>{{ $pedidos->firstItem() ?? 0 }}</strong> - <strong>{{ $pedidos->lastItem() ?? 0 }}</strong> 
                    de <strong>{{ $pedidos->total() }}</strong> compras realizadas
                </div>
            </div>
        @endif

    @else
        {{-- Estado vacío --}}
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h3>🛍️ Aún no has realizado compras</h3>
            <p>Explora nuestra tienda y encuentra las mejores cuentas premium al mejor precio</p>
            <a href="{{ route('tienda.index') }}" class="btn-primary">
                <i class="fas fa-store"></i>
                Ir a la Tienda
            </a>
        </div>
    @endif
@endsection

@push('styles')
<style>
    /* Variables CSS */
    :root {
        --primary-purple: #8b5cf6;
        --primary-purple-dark: #7c3aed;
        --primary-purple-light: #a78bfa;
        --bg-card: #16213e;
        --text-white: #ffffff;
        --text-muted: #9ca3af;
        --border-color: rgba(139, 92, 246, 0.2);
        --shadow-purple: rgba(139, 92, 246, 0.3);
        --gradient-primary: linear-gradient(135deg, var(--primary-purple) 0%, var(--primary-purple-dark) 100%);
        --gradient-card: linear-gradient(145deg, var(--bg-card) 0%, rgba(26, 26, 46, 0.95) 100%);
    }

    /* Animaciones */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes bounce-in {
        0% { transform: scale(0.3) translateY(100px); opacity: 0; }
        50% { transform: scale(1.05) translateY(-10px); opacity: 0.8; }
        70% { transform: scale(0.95) translateY(5px); opacity: 0.9; }
        100% { transform: scale(1) translateY(0); opacity: 1; }
    }

    @keyframes shine {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    /* Hero Section */
    .compras-hero {
        text-align: center;
        margin-bottom: 4rem;
        padding: 4rem 2rem;
        background: var(--gradient-card);
        border-radius: 25px;
        border: 3px solid var(--border-color);
        position: relative;
        overflow: hidden;
        animation: bounce-in 0.8s ease-out;
    }

    .compras-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        animation: shine 3s ease-in-out infinite;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 3.2rem;
        font-weight: 900;
        color: var(--text-white);
        margin-bottom: 1.5rem;
        text-shadow: 0 4px 8px rgba(139, 92, 246, 0.3);
        background: linear-gradient(135deg, #ffffff 0%, var(--primary-purple-light) 50%, var(--primary-purple) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.2;
    }

    .hero-icon {
        color: var(--primary-purple-light);
        margin-right: 1rem;
        filter: drop-shadow(0 4px 8px rgba(139, 92, 246, 0.4));
        animation: float 3s ease-in-out infinite;
    }

    .hero-subtitle {
        font-size: 1.3rem;
        color: var(--text-muted);
        font-weight: 500;
        line-height: 1.5;
        max-width: 600px;
        margin: 0 auto 2rem auto;
    }

    .hero-stats {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(139, 92, 246, 0.1);
        padding: 0.75rem 1.5rem;
        border-radius: 20px;
        border: 1px solid var(--border-color);
        color: var(--primary-purple-light);
        font-weight: 600;
    }

    /* Grid de Compras */
    .compras-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
        animation: fadeIn 0.8s ease-out;
    }

    .compra-card {
        background: var(--gradient-card);
        border: 2px solid var(--border-color);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        position: relative;
    }

    .compra-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-primary);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .compra-card:hover {
        transform: translateY(-10px);
        border-color: var(--primary-purple);
        box-shadow: 0 20px 40px rgba(139, 92, 246, 0.4);
    }

    .compra-card:hover::before {
        opacity: 1;
    }

    .compra-header {
        padding: 1.5rem;
        background: rgba(139, 92, 246, 0.1);
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .compra-id {
        font-weight: 700;
        color: var(--text-white);
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .compra-id i {
        color: var(--primary-purple-light);
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-badge.completado {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
        border: 1px solid #22c55e;
    }

    .status-badge.pendiente {
        background: rgba(251, 191, 36, 0.2);
        color: #fbbf24;
        border: 1px solid #fbbf24;
    }

    .status-badge.procesando {
        background: rgba(59, 130, 246, 0.2);
        color: #3b82f6;
        border: 1px solid #3b82f6;
    }

    .status-badge.otro {
        background: rgba(156, 163, 175, 0.2);
        color: #9ca3af;
        border: 1px solid #9ca3af;
    }

    .compra-content {
        padding: 1.5rem;
    }

    .compra-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: rgba(139, 92, 246, 0.05);
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .info-item i {
        color: var(--primary-purple-light);
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }

    .info-label {
        display: block;
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .info-value {
        display: block;
        font-size: 0.9rem;
        color: var(--text-white);
        font-weight: 700;
    }

    .productos-pedido {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-color);
    }

    .productos-title {
        font-size: 1rem;
        color: var(--text-white);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
    }

    .productos-title i {
        color: var(--primary-purple-light);
    }

    .productos-lista {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .producto-item {
        padding: 0.75rem;
        background: rgba(139, 92, 246, 0.05);
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .producto-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
    }

    .producto-email {
        color: var(--primary-purple-light);
        font-weight: 600;
    }

    .producto-password {
        color: var(--text-muted);
        font-family: monospace;
    }

    .productos-mas {
        padding: 0.5rem;
        text-align: center;
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .compra-footer {
        padding: 1.5rem;
        background: rgba(139, 92, 246, 0.05);
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .compra-tiempo {
        color: var(--text-muted);
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .compra-acciones {
        display: flex;
        gap: 0.75rem;
    }

    .btn-ver-detalles,
    .btn-contactar-soporte {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
    }

    .btn-ver-detalles {
        background: var(--gradient-primary);
        color: var(--text-white);
    }

    .btn-ver-detalles:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px var(--shadow-purple);
    }

    .btn-contactar-soporte {
        background: transparent;
        color: var(--primary-purple-light);
        border: 1px solid var(--primary-purple);
    }

    .btn-contactar-soporte:hover {
        background: var(--primary-purple);
        color: var(--text-white);
    }

    /* Paginación */
    .pagination-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2rem;
        margin: 4rem auto 3rem auto;
        max-width: 800px;
        animation: fadeIn 0.8s ease-out;
    }

    .pagination-wrapper {
        background: var(--gradient-card);
        padding: 2rem 3rem;
        border-radius: 25px;
        border: 3px solid var(--border-color);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(20px);
        position: relative;
        overflow: hidden;
    }

    .pagination-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        animation: shine 3s ease-in-out infinite;
    }

    .pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        list-style: none;
        margin: 0;
        padding: 0;
        flex-wrap: wrap;
    }

    .pagination .page-item {
        list-style: none;
    }

    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 55px;
        height: 55px;
        border-radius: 18px;
        background: rgba(139, 92, 246, 0.1);
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.4s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .pagination .page-link:hover {
        color: var(--text-white);
        border-color: var(--primary-purple);
        transform: translateY(-6px) scale(1.15);
        box-shadow: 0 12px 30px var(--shadow-purple);
        background: var(--gradient-primary);
    }

    .pagination .page-item.active .page-link {
        background: var(--gradient-primary);
        color: var(--text-white);
        border-color: var(--primary-purple-light);
        box-shadow: 0 10px 25px var(--shadow-purple);
        transform: translateY(-4px) scale(1.1);
    }

    .pagination .page-item.disabled .page-link {
        color: rgba(156, 163, 175, 0.3);
        cursor: not-allowed;
        background: rgba(255, 255, 255, 0.03);
    }

    .pagination-info {
        text-align: center;
        color: var(--text-muted);
        font-size: 1rem;
        padding: 1.5rem 3rem;
        background: rgba(139, 92, 246, 0.12);
        border-radius: 25px;
        border: 2px solid var(--border-color);
        backdrop-filter: blur(15px);
        font-weight: 600;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .pagination-info strong {
        color: var(--primary-purple-light);
        font-weight: 800;
    }

    /* Estado vacío */
    .empty-state {
        text-align: center;
        padding: 5rem 3rem;
        background: var(--gradient-card);
        border-radius: 25px;
        border: 3px solid var(--border-color);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        animation: bounce-in 0.8s ease-out;
    }

    .empty-icon {
        font-size: 5rem;
        color: var(--primary-purple-light);
        margin-bottom: 2rem;
        filter: drop-shadow(0 4px 8px rgba(139, 92, 246, 0.3));
    }

    .empty-state h3 {
        font-size: 1.8rem;
        color: var(--text-white);
        margin-bottom: 1.5rem;
        font-weight: 800;
    }

    .empty-state p {
        color: var(--text-muted);
        margin-bottom: 2.5rem;
        font-size: 1.1rem;
        line-height: 1.6;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-primary {
        background: var(--gradient-primary);
        color: var(--text-white);
        padding: 1rem 2rem;
        border-radius: 15px;
        text-decoration: none;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px var(--shadow-purple);
    }

    .btn-primary:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 25px var(--shadow-purple);
        color: var(--text-white);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .compras-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-stats {
            flex-direction: column;
            align-items: center;
        }
        
        .compra-info {
            grid-template-columns: 1fr;
        }
        
        .compra-footer {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
        
        .pagination .page-link {
            width: 45px;
            height: 45px;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .compras-hero {
            padding: 3rem 1rem;
        }
        
        .compra-card {
            margin: 0;
        }
        
        .compra-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar botón ver detalles
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-ver-detalles')) {
            const btn = e.target.closest('.btn-ver-detalles');
            const pedidoId = btn.dataset.pedidoId;
            
            // Aquí puedes implementar la lógica para mostrar detalles
            Swal.fire({
                title: '📋 Detalles del Pedido',
                text: `Mostrando detalles del pedido #${pedidoId}`,
                icon: 'info',
                confirmButtonText: 'Entendido'
            });
        }

        // Manejar botón contactar soporte
        if (e.target.closest('.btn-contactar-soporte')) {
            const btn = e.target.closest('.btn-contactar-soporte');
            const pedidoId = btn.dataset.pedidoId;
            
            Swal.fire({
                title: '🎧 Contactar Soporte',
                text: `¿Necesitas ayuda con el pedido #${pedidoId}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ir a Soporte',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("soporte.index") }}';
                }
            });
        }
    });

    // Animación de entrada para las tarjetas
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.animation = 'fadeIn 0.6s ease-out forwards';
                }, index * 100);
            }
        });
    }, observerOptions);

    // Observar todas las tarjetas
    document.querySelectorAll('.compra-card').forEach(card => {
        observer.observe(card);
    });
});
</script>
@endpush
