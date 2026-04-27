{{-- resources/views/cuenta/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="mi-cuenta-container">
    {{-- Hero Section Mejorado --}}
    <section class="cuenta-hero">
        <div class="hero-background">
            <div class="hero-particles"></div>
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>
        </div>
        <div class="hero-content">
            <div class="user-avatar-hero animate-bounce-in">
                <i class="fas fa-user-astronaut"></i>
                <div class="avatar-glow"></div>
            </div>
            <h1 class="hero-title animate-slide-up">¡Hola, {{ $usuario->name ?? 'Usuario' }}!</h1>
            <p class="hero-subtitle animate-slide-up delay-1">Bienvenido a tu centro de control. Aquí puedes gestionar tu perfil y ver tu historial de compras.</p>
            <div class="hero-actions animate-slide-up delay-2">
                <a href="{{ route('tienda.index') }}" class="btn-primary">
                    <i class="fas fa-store"></i> 
                    <span>Explorar Tienda</span>
                    <div class="btn-glow"></div>
                </a>
                <a href="{{ route('soporte.index') }}" class="btn-outline">
                    <i class="fas fa-wallet"></i> 
                    <span>Cargar Saldo</span>
                    <div class="btn-glow"></div>
                </a>
            </div>
        </div>
    </section>

    {{-- Grid de Estadísticas Mejorado --}}
    <div class="stats-grid">
        <div class="stat-card animate-card" style="animation-delay: 0.1s">
            <div class="stat-icon completado">
                <i class="fas fa-check-circle"></i>
                <div class="icon-pulse"></div>
            </div>
            <div class="stat-details">
                <p class="stat-value counter" data-target="{{ $pedidos->where('status', 'Completado')->count() ?? 0 }}">0</p>
                <p class="stat-label">Pedidos Completados</p>
            </div>
            <div class="card-shine"></div>
        </div>
        
        <div class="stat-card animate-card" style="animation-delay: 0.2s">
            <div class="stat-icon pendiente">
                <i class="fas fa-clock"></i>
                <div class="icon-pulse"></div>
            </div>
            <div class="stat-details">
                <p class="stat-value counter" data-target="{{ $pedidos->where('status', 'Pendiente')->count() ?? 0 }}">0</p>
                <p class="stat-label">Pedidos Pendientes</p>
            </div>
            <div class="card-shine"></div>
        </div>
        
        <div class="stat-card animate-card" style="animation-delay: 0.3s">
            <div class="stat-icon total">
                <i class="fas fa-receipt"></i>
                <div class="icon-pulse"></div>
            </div>
            <div class="stat-details">
                <p class="stat-value counter" data-target="{{ $pedidos->total() ?? 0 }}">0</p>
                <p class="stat-label">Pedidos Totales</p>
            </div>
            <div class="card-shine"></div>
        </div>
        
        <div class="stat-card animate-card" style="animation-delay: 0.4s">
            <div class="stat-icon saldo">
                <i class="fas fa-wallet"></i>
                <div class="icon-pulse"></div>
            </div>
            <div class="stat-details">
                <p class="stat-value">${{ number_format(Auth::user()->saldo ?? 0, 0, ',', '.') }}</p>
                <p class="stat-label">Saldo Disponible</p>
            </div>
            <div class="card-shine"></div>
        </div>
    </div>

    {{-- Sección de Historial de Pedidos Mejorada --}}
    <div class="orders-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-history"></i> Historial de Pedidos
            </h2>
            <div class="section-subtitle">Gestiona y revisa todas tus compras</div>
        </div>

        @if($pedidos && $pedidos->count() > 0)
            <div class="orders-grid">
                @foreach ($pedidos as $index => $pedido)
                    <div class="order-card animate-card" style="animation-delay: {{ $index * 0.1 }}s">
                        <div class="order-header">
                            <div class="order-id">
                                <i class="fas fa-hashtag"></i>
                                Pedido #{{ $pedido->id }}
                            </div>
                            <div class="order-status status-{{ Str::slug($pedido->status ?? 'desconocido') }}">
                                <i class="fas {{ $pedido->status === 'Completado' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                                <span>{{ $pedido->status ?? 'Desconocido' }}</span>
                            </div>
                        </div>
                        
                        <div class="order-body">
                            <div class="order-info-item">
                                <div class="info-icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Fecha</span>
                                    <span class="info-value">{{ $pedido->created_at ? $pedido->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                </div>
                            </div>
                            
                            <div class="order-info-item">
                                <div class="info-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Productos</span>
                                    <span class="info-value">{{ $pedido->item_count ?? $pedido->items?->count() ?? 1 }} {{ Str::plural('producto', $pedido->item_count ?? $pedido->items?->count() ?? 1) }}</span>
                                </div>
                            </div>
                            
                            <div class="order-info-item">
                                <div class="info-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Método</span>
                                    <span class="info-value">{{ $pedido->payment_method ?? 'Saldo' }}</span>
                                </div>
                            </div>
                            
                            <div class="order-total">
                                <span class="total-label">Total:</span>
                                <span class="total-value">${{ number_format($pedido->total_amount ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <div class="order-footer">
                            <button type="button" class="btn-ver-detalles" data-pedido-id="{{ $pedido->id }}">
                                <i class="fas fa-eye"></i> 
                                <span>Ver Detalles</span>
                                <div class="btn-ripple"></div>
                            </button>
                        </div>
                        
                        <div class="card-shine"></div>
                    </div>
                @endforeach
            </div>

            {{-- Paginación Premium Mejorada --}}
            @if ($pedidos->hasPages())
                <div class="pagination-section">
                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            <i class="fas fa-info-circle"></i>
                            Mostrando <strong>{{ $pedidos->firstItem() ?? 0 }}</strong> - <strong>{{ $pedidos->lastItem() ?? 0 }}</strong> 
                            de <strong>{{ $pedidos->total() }}</strong> pedidos
                        </div>
                        
                        <nav class="pagination-nav" aria-label="Navegación de páginas">
                            <div class="pagination-links">
                                {{-- Primera página --}}
                                @if($pedidos->currentPage() > 1)
                                    <a href="{{ $pedidos->url(1) }}" class="pagination-link first" title="Primera página">
                                        <i class="fas fa-angle-double-left"></i>
                                    </a>
                                @endif

                                {{-- Página anterior --}}
                                @if($pedidos->previousPageUrl())
                                    <a href="{{ $pedidos->previousPageUrl() }}" class="pagination-link prev" title="Página anterior">
                                        <i class="fas fa-angle-left"></i>
                                        <span>Anterior</span>
                                    </a>
                                @endif

                                {{-- Números de página --}}
                                @php
                                    $start = max(1, $pedidos->currentPage() - 2);
                                    $end = min($pedidos->lastPage(), $pedidos->currentPage() + 2);
                                @endphp

                                @if($start > 1)
                                    <a href="{{ $pedidos->url(1) }}" class="pagination-link">1</a>
                                    @if($start > 2)
                                        <span class="pagination-dots">...</span>
                                    @endif
                                @endif

                                @for($i = $start; $i <= $end; $i++)
                                    @if($i == $pedidos->currentPage())
                                        <span class="pagination-link current">{{ $i }}</span>
                                    @else
                                        <a href="{{ $pedidos->url($i) }}" class="pagination-link">{{ $i }}</a>
                                    @endif
                                @endfor

                                @if($end < $pedidos->lastPage())
                                    @if($end < $pedidos->lastPage() - 1)
                                        <span class="pagination-dots">...</span>
                                    @endif
                                    <a href="{{ $pedidos->url($pedidos->lastPage()) }}" class="pagination-link">{{ $pedidos->lastPage() }}</a>
                                @endif

                                {{-- Página siguiente --}}
                                @if($pedidos->nextPageUrl())
                                    <a href="{{ $pedidos->nextPageUrl() }}" class="pagination-link next" title="Página siguiente">
                                        <span>Siguiente</span>
                                        <i class="fas fa-angle-right"></i>
                                    </a>
                                @endif

                                {{-- Última página --}}
                                @if($pedidos->currentPage() < $pedidos->lastPage())
                                    <a href="{{ $pedidos->url($pedidos->lastPage()) }}" class="pagination-link last" title="Última página">
                                        <i class="fas fa-angle-double-right"></i>
                                    </a>
                                @endif
                            </div>
                        </nav>
                    </div>
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-content">
                    <div class="empty-icon">
                        <i class="fas fa-shopping-bag"></i>
                        <div class="icon-float"></div>
                    </div>
                    <h3>Aún no has realizado compras</h3>
                    <p>¡Tu historial de compras aparecerá aquí una vez que realices tu primer pedido!</p>
                    <a href="{{ route('tienda.index') }}" class="btn-primary">
                        <i class="fas fa-store"></i>
                        <span>Ir a la Tienda</span>
                        <div class="btn-glow"></div>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
/* === RESET Y CONFIGURACIÓN BASE === */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    min-height: 100vh;
    scroll-behavior: smooth;
}

.app, .main-content, .content-wrapper, .container, .container-fluid,
.row, .col, .col-12, .col-md-12, .col-lg-12, .content,
.page-content, .main, .wrapper, .layout-wrapper, .app-content, .main-wrapper {
}

/* === VARIABLES MEJORADAS === */
:root {
    --primary: #6366f1;
    --primary-light: #818cf8;
    --primary-dark: #4f46e5;
    --secondary: #ec4899;
    --accent: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --success: #22c55e;
    

    --text-white: #f8fafc;
    --text-muted: #94a3b8;
    --border-color: rgba(99, 102, 241, 0.2);
    --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    --shadow-glow: 0 0 20px rgba(99, 102, 241, 0.3);
    --radius: 1rem;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-bounce: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

/* === ESTILOS GLOBALES === */
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    color: var(--text-white);
    line-height: 1.6;
    overflow-x: hidden;
}

/* === CONTENEDOR PRINCIPAL === */
.mi-cuenta-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: clamp(1rem, 4vw, 2rem);
    background: var(--bg-main) !important;
    min-height: 100vh;
}

/* === HERO SECTION MEJORADO === */
.cuenta-hero {
    position: relative;
    /* ✅ MEJORA: Fondo de cristal para ver las constelaciones */
    background: var(--bg-glass) !important; 
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);

    /* ✅ MEJORA: Borde de neón sutil y animado */
    border: 1px solid var(--border-glow);
    animation: subtle-glow 4s ease-in-out infinite;

    border-radius: 1rem; /* Usamos la variable --radius si está definida o un valor fijo */
    padding: clamp(2rem, 8vw, 4rem) clamp(1rem, 4vw, 2rem);
    margin-bottom: 3rem;
    text-align: center;
    overflow: hidden;
    transition: all 0.3s ease;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.hero-particles {
    position: absolute;
    width: 100%;
    height: 100%;
    background-image: 
        radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
        radial-gradient(circle at 40% 60%, rgba(255, 255, 255, 0.05) 2px, transparent 2px);
    background-size: 50px 50px, 30px 30px, 80px 80px;
    animation: particleFloat 20s linear infinite;
}

.floating-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: floatShape 15s ease-in-out infinite;
}

.shape-1 {
    width: 100px;
    height: 100px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 60px;
    height: 60px;
    top: 70%;
    right: 20%;
    animation-delay: 5s;
}

.shape-3 {
    width: 80px;
    height: 80px;
    bottom: 20%;
    left: 70%;
    animation-delay: 10s;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.user-avatar-hero {
    position: relative;
    width: 140px;
    height: 140px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1)) !important;
    border-radius: 50%;
    margin: 0 auto 2rem auto;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: var(--text-white);
    border: 4px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.avatar-glow {
    position: absolute;
    top: -4px;
    left: -4px;
    right: -4px;
    bottom: -4px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-light), var(--secondary));
    opacity: 0;
    transition: var(--transition);
    z-index: -1;
    filter: blur(15px);
}

.user-avatar-hero:hover .avatar-glow {
    opacity: 0.6;
}

.hero-title {
    font-size: clamp(2.5rem, 8vw, 4rem);
    font-weight: 900;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, #fff 0%, rgba(255, 255, 255, 0.8) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: clamp(1rem, 3vw, 1.25rem);
    opacity: 0.9;
    margin-bottom: 2.5rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.hero-actions {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

/* === BOTONES MEJORADOS === */
.btn-primary, .btn-outline {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    border-radius: var(--radius);
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    overflow: hidden;
}

.btn-glow {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
}

.btn-primary:hover .btn-glow,
.btn-outline:hover .btn-glow {
    left: 100%;
}

.btn-primary {
    background: rgba(255, 255, 255, 0.2) !important;
    color: var(--text-white);
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.btn-primary:hover {
    background: rgba(255, 255, 255, 0.3) !important;
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(255, 255, 255, 0.2);
}

.btn-outline {
    background: transparent !important;
    color: var(--text-white);
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.btn-outline:hover {
    background: rgba(255, 255, 255, 0.1) !important;
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
}

/* === GRID DE ESTADÍSTICAS MEJORADO === */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.stat-card {
    position: relative;
    background: var(--bg-card) !important;
    padding: 2rem;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 2rem;
    transition: var(--transition);
    overflow: hidden;
    backdrop-filter: blur(10px);
}

.card-shine {
    position: absolute;
    top: 0;
    left: -150%;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, transparent 0%, rgba(255, 255, 255, 0.1) 50%, transparent 100%);
    transform: skewX(-25deg);
    transition: left 0.7s ease-in-out;
    z-index: 1;
    pointer-events: none;
}

.stat-card:hover .card-shine {
    left: 150%;
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
}

.stat-icon {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    box-shadow: var(--shadow);
}

.icon-pulse {
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    border-radius: var(--radius);
    opacity: 0;
    animation: iconPulse 2s infinite;
}

.stat-icon.completado {
    background: linear-gradient(135deg, var(--success), #16a34a);
}

.stat-icon.completado .icon-pulse {
    border: 2px solid var(--success);
}

.stat-icon.pendiente {
    background: linear-gradient(135deg, var(--warning), #d97706);
}

.stat-icon.pendiente .icon-pulse {
    border: 2px solid var(--warning);
}

.stat-icon.total {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
}

.stat-icon.total .icon-pulse {
    border: 2px solid var(--primary);
}

.stat-icon.saldo {
    background: linear-gradient(135deg, var(--secondary), #be185d);
}

.stat-icon.saldo .icon-pulse {
    border: 2px solid var(--secondary);
}

.stat-details {
    flex: 1;
    z-index: 2;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 900;
    color: var(--text-white);
    margin: 0 0 0.5rem 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.stat-label {
    font-size: 1rem;
    color: var(--text-muted);
    margin: 0;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* === SECCIÓN DE PEDIDOS MEJORADA === */
.orders-section {
    background: var(--bg-card) !important;
    padding: 3rem;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    backdrop-filter: blur(10px);
}

.section-header {
    margin-bottom: 3rem;
    text-align: center;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 900;
    color: var(--text-white);
    margin: 0 0 1rem 0;
    background: linear-gradient(135deg, var(--primary-light), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.section-subtitle {
    font-size: 1.1rem;
    color: var(--text-muted);
    font-weight: 500;
}

/* === GRID DE PEDIDOS MEJORADO === */
.orders-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.order-card {
    position: relative;
    background: var(--bg-main) !important;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: var(--transition);
    backdrop-filter: blur(10px);
}

.order-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: rgba(99, 102, 241, 0.1) !important;
    border-bottom: 1px solid var(--border-color);
}

.order-id {
    font-weight: 800;
    color: var(--text-white);
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.order-status {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    backdrop-filter: blur(10px);
}

.status-completado { 
    background: rgba(34, 197, 94, 0.2) !important; 
    color: var(--success); 
    border: 1px solid var(--success);
}

.status-pendiente { 
    background: rgba(251, 191, 36, 0.2) !important; 
    color: var(--warning); 
    border: 1px solid var(--warning);
}

.order-body {
    padding: 2rem;
}

.order-info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

.order-info-item:last-child {
    border-bottom: none;
}

.info-icon {
    width: 40px;
    height: 40px;
    background: rgba(99, 102, 241, 0.1) !important;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-light);
    border: 1px solid var(--border-color);
}

.info-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-label {
    font-size: 0.8rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
}

.info-value {
    font-size: 0.95rem;
    color: var(--text-white);
    font-weight: 600;
}

.order-total {
    margin-top: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1)) !important;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    text-align: center;
}

.total-label {
    color: var(--text-muted);
    font-size: 1rem;
    font-weight: 600;
    display: block;
    margin-bottom: 0.5rem;
}

.total-value {
    font-size: 2rem;
    font-weight: 900;
    color: var(--accent);
    text-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
}

.order-footer {
    padding: 1.5rem;
    background: rgba(99, 102, 241, 0.05) !important;
    text-align: center;
}

.btn-ver-detalles {
    position: relative;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark)) !important;
    color: var(--text-white);
    border: none;
    padding: 0.875rem 2rem;
    border-radius: var(--radius);
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    overflow: hidden;
}

.btn-ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
}

.btn-ver-detalles:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary)) !important;
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

/* === PAGINACIÓN MEJORADA === */
.pagination-section {
    margin-top: 3rem;
    background: var(--bg-main) !important;
}

.pagination-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
    background: var(--bg-card) !important;
    padding: 2rem;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    backdrop-filter: blur(10px);
}

.pagination-info {
    color: var(--text-muted);
    font-size: 0.875rem;
    font-weight: 500;
    text-align: center;
    order: 1;
}

.pagination-info strong {
    color: var(--primary-light);
    font-weight: 700;
}

.pagination-nav {
    order: 2;
}

.pagination-links {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: center;
}

.pagination-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 45px;
    height: 45px;
    padding: 0 0.75rem;
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    color: var(--text-white);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    gap: 0.5rem;
}

.pagination-link:hover {
    background: var(--primary) !important;
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: var(--shadow);
    color: white;
    text-decoration: none;
}

.pagination-link.current {
    background: var(--primary) !important;
    border-color: var(--primary);
    color: white;
    font-weight: 700;
}

.pagination-dots {
    color: var(--text-muted);
    padding: 0 0.5rem;
    font-weight: 700;
}

/* === ESTADO VACÍO MEJORADO === */
.empty-state {
    text-align: center;
    padding: 5rem 3rem;
    background: var(--bg-main) !important;
    border-radius: var(--radius);
    border: 2px dashed var(--border-color);
}

.empty-state-content {
    max-width: 400px;
    margin: 0 auto;
}

.empty-icon {
    position: relative;
    font-size: 5rem;
    color: var(--primary-light);
    margin-bottom: 2rem;
    display: inline-block;
}

.icon-float {
    position: absolute;
    top: -4px;
    left: -4px;
    right: -4px;
    bottom: -4px;
    border: 2px solid var(--primary);
    border-radius: 50%;
    opacity: 0;
    animation: iconPulse 2s infinite;
}

.empty-state h3 {
    font-size: 2rem;
    color: var(--text-white);
    margin-bottom: 1.5rem;
    font-weight: 800;
}

.empty-state p {
    color: var(--text-muted);
    margin-bottom: 2.5rem;
    font-size: 1.1rem;
    line-height: 1.6;
}

/* === ANIMACIONES MEJORADAS === */
@keyframes particleFloat {
    0%, 100% { 
        transform: translateY(0px) rotate(0deg); 
    }
    50% { 
        transform: translateY(-10px) rotate(180deg); 
    }
}

@keyframes floatShape {
    0%, 100% { 
        transform: translateY(0px) rotate(0deg); 
    }
    33% { 
        transform: translateY(-20px) rotate(120deg); 
    }
    66% { 
        transform: translateY(10px) rotate(240deg); 
    }
}

@keyframes iconPulse {
    0% { 
        opacity: 0; 
        transform: scale(1); 
    }
    50% { 
        opacity: 0.5; 
        transform: scale(1.1); 
    }
    100% { 
        opacity: 0; 
        transform: scale(1.2); 
    }
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

/* === ANIMACIONES DE ENTRADA === */
.animate-bounce-in {
    animation: bounceIn 0.8s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out;
}

.animate-card {
    animation: cardSlideUp 0.6s ease-out forwards;
    opacity: 0;
    transform: translateY(30px);
}

.delay-1 {
    animation-delay: 0.2s;
    opacity: 0;
    animation-fill-mode: forwards;
}

.delay-2 {
    animation-delay: 0.4s;
    opacity: 0;
    animation-fill-mode: forwards;
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3) translateY(50px);
    }
    50% {
        opacity: 1;
        transform: scale(1.05) translateY(-10px);
    }
    70% {
        transform: scale(0.9) translateY(0);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes slideUp {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes cardSlideUp {
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

/* === RESPONSIVE MEJORADO === */
@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .orders-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .mi-cuenta-container {
        padding: 1rem;
    }
    
    .cuenta-hero {
        padding: 3rem 1.5rem;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .user-avatar-hero {
        width: 100px;
        height: 100px;
        font-size: 3rem;
    }
    
    .hero-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .orders-section {
        padding: 2rem;
    }
    
    .pagination-links {
        flex-wrap: wrap;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .stat-card {
        padding: 1.5rem;
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .stat-icon {
        width: 70px;
        height: 70px;
        font-size: 2rem;
    }
    
    .stat-value {
        font-size: 2rem;
    }
    
    .order-info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .pagination-link.prev span,
    .pagination-link.next span {
        display: none;
    }
}

/* === MEJORAS ADICIONALES === */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* === LOADING STATES === */
.loading {
    opacity: 0.7;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid var(--primary);
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* === ESTILOS PARA MODALES === */
.swal2-popup {
    background: linear-gradient(145deg, var(--bg-card) 0%, var(--bg-main) 100%) !important;
    color: var(--text-white) !important;
    border: 2px solid var(--primary) !important;
    border-radius: var(--radius) !important;
    box-shadow: var(--shadow-glow) !important;
    backdrop-filter: blur(10px) !important;
}

.swal2-title {
    color: var(--text-white) !important;
    background: linear-gradient(135deg, var(--primary-light), var(--secondary));
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    background-clip: text !important;
    font-weight: 900 !important;
}

.swal2-html-container {
    color: var(--text-muted) !important;
}

.swal2-confirm {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark)) !important;
    border: none !important;
    border-radius: 0.5rem !important;
    font-weight: 700 !important;
}

.pedido-detalle {
    text-align: left;
}

.pedido-detalle h4 {
    color: var(--primary-light);
    margin-top: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 0.75rem;
    font-size: 1.2rem;
    font-weight: 700;
}

.pedido-productos-list {
    max-height: 300px;
    overflow-y: auto;
    padding-right: 10px;
}

.producto-item {
    background: rgba(99, 102, 241, 0.1) !important;
    padding: 1.5rem;
    border-radius: var(--radius);
    margin-bottom: 1rem;
    border-left: 4px solid var(--primary);
}

.producto-item strong {
    color: var(--text-white);
}

.account-details-section {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px dashed var(--border-color);
}

.account-details-section p {
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.pedido-resumen {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 2px solid var(--border-color);
    text-align: right;
}

.pedido-total-modal {
    font-size: 1.8rem;
    font-weight: 900;
    color: var(--accent);
    text-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // === CONTADOR ANIMADO ===
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseInt(counter.dataset.target);
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    counter.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(current).toLocaleString();
                }
            }, 16);
        });
    }
    
    // === EFECTOS DE RIPPLE ===
    function createRipple(event) {
        const button = event.currentTarget;
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('btn-ripple');
        
        button.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
    
    // === INTERSECTION OBSERVER PARA ANIMACIONES ===
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
                if (entry.target.classList.contains('counter')) {
                    animateCounters();
                }
            }
        });
    }, observerOptions);
    
    // Observar elementos animados
    document.querySelectorAll('.animate-card, .counter, .animate-slide-up').forEach(el => {
        observer.observe(el);
    });
    
    // === AGREGAR EFECTOS DE RIPPLE A BOTONES ===
    document.querySelectorAll('.btn-ver-detalles').forEach(btn => {
        btn.addEventListener('click', createRipple);
    });
    
    // === MANEJO DE DETALLES DE PEDIDOS ===
    document.body.addEventListener('click', function(e) {
        if (e.target.matches('.btn-ver-detalles, .btn-ver-detalles *')) {
            const button = e.target.closest('.btn-ver-detalles');
            const pedidoId = button.dataset.pedidoId;

            Swal.fire({
                title: '🔄 Cargando Detalles...',
                text: 'Por favor espera mientras obtenemos la información.',
                background: 'var(--bg-card)',
                color: 'var(--text-white)',
                confirmButtonColor: 'var(--primary)',
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: {
                    popup: 'loading-modal'
                },
                didOpen: () => {
                    Swal.showLoading();
                    const detailsUrl = "{{ route('cuenta.pedidoDetailsAjax', ':pedidoId') }}".replace(':pedidoId', pedidoId);

                    fetch(detailsUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => {
                        Swal.hideLoading();
                        if (!response.ok) {
                             return response.json().then(errorData => {
                                throw new Error(errorData.message || 'Error HTTP: ' + response.statusText);
                            }).catch(() => {
                                throw new Error('Error HTTP: ' + response.statusText);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        let productsHtml = '<div class="pedido-productos-list">';
                        if (data.items && data.items.length > 0) {
                            data.items.forEach((item, index) => {
                                const productName = item.product_name || 'N/A';
                                const quantity = item.quantity || 1;

                                productsHtml += `
                                    <div class="producto-item" style="animation: slideInRight 0.3s ease-out ${index * 0.1}s both;">
                                        <p><strong>📦 Producto:</strong> ${productName}</p>
                                        <p><strong>🔢 Cantidad:</strong> ${quantity}</p>

                                        <div class="account-details-section">
                                            <h5 style="color: var(--accent); margin-bottom: 10px; font-size: 1.1rem; font-weight: bold;">
                                                <i class="fas fa-key"></i> Datos de Acceso
                                            </h5>
                                            <p><strong>👤 Email/Usuario:</strong> <code style="background: rgba(99, 102, 241, 0.2); padding: 0.25rem 0.5rem; border-radius: 0.25rem; color: var(--primary-light);">${item.email_usuario || 'N/A'}</code></p>
                                            <p><strong>🔐 Contraseña:</strong> <code style="background: rgba(99, 102, 241, 0.2); padding: 0.25rem 0.5rem; border-radius: 0.25rem; color: var(--primary-light);">${item.password || 'N/A'}</code></p>
                                            ${item.detalles ? `<p><strong>📝 Detalles:</strong> ${item.detalles}</p>` : ''}
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            productsHtml += `
                                <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                    <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                    <p>No hay productos en este pedido.</p>
                                </div>
                            `;
                        }
                        productsHtml += '</div>';

                        const orderStatus = data.status || 'Desconocido';
                        const orderStatusSlug = orderStatus.toLowerCase().replace(/\s+/g, '-');
                        const createdAt = data.created_at_formatted || (data.created_at ? new Date(data.created_at).toLocaleDateString('es-CO') : 'N/A');
                        const updatedAt = data.updated_at_formatted || (data.updated_at ? new Date(data.updated_at).toLocaleDateString('es-CO', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : 'N/A');
                        const paymentMethod = data.payment_method || 'N/A';
                        const totalAmount = data.total_amount_formatted || new Intl.NumberFormat('es-CO', { style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(data.total_amount || 0);

                        const htmlContent = `
                            <div class="pedido-detalle">
                                <div class="pedido-info" style="
                                    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1));
                                    padding: 1.5rem;
                                    border-radius: var(--radius);
                                    margin-bottom: 2rem;
                                    border: 1px solid var(--border-color);
                                ">
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                                        <p><strong>📊 Estado:</strong> <span class="order-status status-${orderStatusSlug}" style="margin-left: 0.5rem;">${orderStatus}</span></p>
                                        <p><strong>📅 Fecha:</strong> ${createdAt}</p>
                                        <p><strong>🔄 Actualizado:</strong> ${updatedAt}</p>
                                        <p><strong>💳 Método:</strong> ${paymentMethod}</p>
                                    </div>
                                </div>
                                <div class="pedido-productos">
                                     <h4><i class="fas fa-shopping-bag"></i> Productos Adquiridos</h4>
                                     ${productsHtml}
                                </div>
                                 <div class="pedido-resumen" style="
                                    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
                                    padding: 1.5rem;
                                    border-radius: var(--radius);
                                    border: 1px solid rgba(16, 185, 129, 0.2);
                                    text-align: center;
                                ">
                                    <p style="margin-bottom: 0.5rem; color: var(--text-muted);"><strong>💰 Total del Pedido</strong></p>
                                    <p class="pedido-total-modal">$${totalAmount} CLP</p>
                                </div>
                            </div>
                        `;

                        Swal.fire({
                            title: `📋 Detalles del Pedido #${pedidoId}`,
                            html: htmlContent,
                            background: 'var(--bg-card)',
                            color: 'var(--text-white)',
                            confirmButtonColor: 'var(--primary)',
                            showConfirmButton: true,
                            confirmButtonText: '<i class="fas fa-times"></i> Cerrar',
                            width: 800,
                            customClass: {
                                popup: 'details-modal',
                                confirmButton: 'animated-btn'
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching order details:', error);
                        Swal.fire({
                            title: '❌ Error',
                            html: `
                                <div style="text-align: center; padding: 2rem;">
                                    <div style="
                                        width: 80px; 
                                        height: 80px; 
                                        background: linear-gradient(135deg, var(--danger), #dc2626); 
                                        border-radius: 50%; 
                                        display: flex; 
                                        align-items: center; 
                                        justify-content: center; 
                                        margin: auto; 
                                        font-size: 2rem; 
                                        color: white;
                                        margin-bottom: 1.5rem;
                                    ">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <p style="color: var(--text-muted); margin-bottom: 1rem;">${error.message || 'No se pudieron cargar los detalles del pedido.'}</p>
                                    <p style="color: var(--text-muted); font-size: 0.875rem;">Por favor intenta nuevamente o contacta soporte.</p>
                                </div>
                            `,
                            background: 'var(--bg-card)',
                            color: 'var(--text-white)',
                            confirmButtonColor: 'var(--danger)',
                            showConfirmButton: true,
                            confirmButtonText: '<i class="fas fa-redo"></i> Reintentar',
                            showCancelButton: true,
                            cancelButtonText: 'Cerrar',
                            cancelButtonColor: 'var(--text-muted)',
                            customClass: {
                                popup: 'error-modal'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Reintentar carga de detalles
                                button.click();
                            }
                        });
                    });
                }
            });
        }
    });
    
    // === INICIALIZACIÓN FINAL ===
    setTimeout(() => {
        animateCounters();
    }, 500);

    console.log('🎉 Mi Cuenta NPStreaming cargada exitosamente!');
});
</script>
@endpush
