{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    {{-- Hero Section Mejorado --}}
    <section class="hero-section">
        <div class="hero-background">
            <div class="hero-particles"></div>
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>
        </div>
        <div class="hero-content">
            <div class="hero-badge animate-bounce-in">
                <i class="fas fa-fire"></i>
                <span>Productos Populares</span>
            </div>
            <h1 class="hero-title animate-slide-up">Pandora Streaming</h1>
            <p class="hero-subtitle animate-slide-up delay-1">Descubre las cuentas más vendidas y recomendadas por nuestra comunidad</p>
            <div class="hero-stats animate-slide-up delay-2">
                <div class="stat-item">
                    <div class="stat-number counter" data-target="{{ count($populares ?? []) }}">0</div>
                    <div class="stat-label">Productos</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-number counter" data-target="10000">0</div>
                    <div class="stat-label">Clientes</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Soporte</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Filtros Mejorados --}}
    <section class="filters-section">
        <div class="filters-container">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">
                    <i class="fas fa-fire"></i> 
                    <span>Populares</span>
                    <div class="btn-glow"></div>
                </button>
                <button class="filter-btn" data-filter="available">
                    <i class="fas fa-check-circle"></i> 
                    <span>Disponibles</span>
                    <div class="btn-glow"></div>
                </button>
                <button class="filter-btn" data-filter="new">
                    <i class="fas fa-star"></i> 
                    <span>Nuevos</span>
                    <div class="btn-glow"></div>
                </button>
            </div>
            <a href="{{ route('tienda.index') }}" class="explore-all-btn">
                <i class="fas fa-store"></i>
                <span>Ver Tienda Completa</span>
                <i class="fas fa-arrow-right arrow-move"></i>
            </a>
        </div>
    </section>

    {{-- Grid de Productos Mejorado --}}
    @if($populares && count($populares) > 0)
    <section class="products-section">
        <div class="products-grid" id="products-grid">
            @foreach ($populares as $index => $producto)
                @php
                    $stock = $producto->stock_disponible ?? 0;
                    $isOutOfStock = $stock <= 0;
                    $isPopular = $producto->es_popular ?? true;
                @endphp
                
                <div class="product-card {{ $isOutOfStock ? 'out-of-stock' : '' }} {{ $isPopular ? 'popular' : '' }}"
                     data-id="{{ $producto->id }}"
                     data-name="{{ $producto->nombre }}"
                     data-price="{{ $producto->precio }}"
                     data-stock="{{ $stock }}"
                     data-popular="{{ $isPopular ? 'true' : 'false' }}"
                     data-available="{{ !$isOutOfStock ? 'true' : 'false' }}"
                     data-image="{{ $producto->imagen_display_url ?? 'https://placehold.co/600x400/6366f1/FFFFFF?text=NPStreaming' }}"
                     data-description="{{ $producto->descripcion_corta ?? 'Cuenta premium de alta calidad.' }}"
                     data-full-description="{{ $producto->descripcion ?? 'Descripción completa no disponible.' }}"
                     style="animation-delay: {{ $index * 0.1 }}s">
                    
                    <div class="product-card-inner">
                        {{-- Efectos Mejorados --}}
                        <div class="shine-effect"></div>
                        <div class="card-glow"></div>

                        {{-- Badges Animados --}}
                        @if($index < 3 && !$isOutOfStock)
                            <div class="product-badge popular pulse-animation">
                                <i class="fas fa-crown"></i> Top {{ $index + 1 }}
                            </div>
                        @endif

                        @if($isOutOfStock)
                            <div class="product-badge sold-out">
                                <i class="fas fa-times-circle"></i> Agotado
                            </div>
                        @endif

                        {{-- Imagen con Efectos --}}
                        <div class="product-image">
                            <img src="{{ $producto->imagen_display_url ?? 'https://placehold.co/600x400/6366f1/FFFFFF?text=NPStreaming' }}" 
                                 alt="{{ $producto->nombre }}" loading="lazy">
                            
                            <div class="image-overlay">
                                <button class="quick-action-btn" data-action="quick-view">
                                    <i class="fas fa-eye"></i>
                                    <span class="tooltip">Vista Rápida</span>
                                </button>
                            </div>
                        </div>

                        {{-- Contenido Mejorado --}}
                        <div class="product-content">
                            <div class="product-category">Premium Account</div>
                            <h3 class="product-title">{{ $producto->nombre }}</h3>
                            
                            <div class="product-rating">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= 4 ? 'filled' : '' }} star-animate" style="animation-delay: {{ $i * 0.1 }}s"></i>
                                    @endfor
                                </div>
                                <span class="rating-count">({{ rand(50, 200) }})</span>
                            </div>

                            <div class="price-section">
                                <span class="price-main">${{ number_format($producto->precio, 0, ',', '.') }}</span>
                                <span class="price-currency">CLP</span>
                            </div>

                            <div class="stock-info">
                                @if($isOutOfStock)
                                    <span class="stock-tag unavailable">
                                        <i class="fas fa-times-circle"></i> Agotado
                                    </span>
                                @else
                                    <span class="stock-tag available">
                                        <i class="fas fa-check-circle"></i> {{ $stock }} disponibles
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Botones Mejorados --}}
                        <div class="product-actions">
                            @if($isOutOfStock)
                                <button class="action-btn btn-disabled" disabled>
                                    <i class="fas fa-ban"></i> Sin Stock
                                </button>
                            @else
                                <button class="action-btn btn-buy" data-action="buy-direct">
                                    
                                    <span>Comprar</span>
                                    <div class="btn-ripple"></div>
                                </button>
                                <button class="action-btn btn-cart" data-action="cart">
                                    <i class="fas fa-cart-plus"></i>
                                    <div class="btn-ripple"></div>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Tarjeta Explorar Mejorada --}}
            <a href="{{ route('tienda.index') }}" class="explore-card">
                <div class="explore-background">
                    <div class="explore-pattern"></div>
                </div>
                <div class="explore-content">
                    <div class="explore-icon">
                        <i class="fas fa-store"></i>
                        <div class="icon-pulse"></div>
                    </div>
                    <h3 class="explore-title">Explorar Tienda</h3>
                    <p class="explore-subtitle">Descubre todo nuestro catálogo</p>
                    <div class="explore-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>
        </div>
    </section>
    @else
        <section class="empty-state">
            <div class="empty-state-content">
                <div class="empty-state-icon">
                    <i class="fas fa-fire"></i>
                </div>
                <h3>Próximamente Productos Populares</h3>
                <p>Estamos seleccionando las mejores cuentas para ti</p>
                <a href="{{ route('tienda.index') }}" class="btn-explore">
                    <i class="fas fa-store"></i> Explorar Tienda
                </a>
            </div>
        </section>
    @endif
</div>
@endsection

@push('styles')
<style>
/* 🟡 FIX 1: SOLUCIÓN DE SCROLL Y LAYOUT */
/* Se asume que en tu layouts.app el contenedor principal tiene la clase .main-content */
/* Si la clase es otra (ej. content-wrapper), puedes cambiarla aquí. */
.main-content {
    height: 100vh; /* Ocupa toda la altura de la pantalla */
    overflow-y: auto; /* Permite el scroll vertical cuando el contenido es más alto */
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
    --bg-card: #1e293b;
    --bg-card-hover: #334155;
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
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: clamp(1rem, 4vw, 2rem);
    background: var(--bg-main) !important;
}

/* === HERO SECTION MEJORADO Y COMPACTO === */
.hero-section {
    position: relative;
    
    /* 🔴 FIX 2: ESTILOS DEL CONTENEDOR ELIMINADOS */
    background: transparent !important; 
    backdrop-filter: none;
    -webkit-backdrop-filter: none;
    border: none;
    animation: none;
    border-radius: 0;

    /* Se mantiene el padding y margen para no afectar el layout interno */
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

/* El resto del CSS permanece igual... */

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

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.2) !important;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.hero-title {
    font-size: clamp(2.5rem, 8vw, 3.5rem);
    font-weight: 900;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, #fff 0%, rgba(255, 255, 255, 0.8) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: clamp(1rem, 3vw, 1.25rem);
    opacity: 0.9;
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: clamp(1rem, 4vw, 2rem);
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
    background: rgba(255, 255, 255, 0.1) !important;
    padding: 1.5rem;
    border-radius: var(--radius);
    backdrop-filter: blur(10px);
    min-width: 120px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: var(--transition);
}

.stat-item:hover {
    transform: translateY(-5px) scale(1.05);
    background: rgba(255, 255, 255, 0.15) !important;
}

.stat-number {
    font-size: clamp(1.5rem, 4vw, 2rem);
    font-weight: 900;
    display: block;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-divider {
    width: 1px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
}

/* === FILTROS MEJORADOS === */
.filters-section {
    margin-bottom: 2rem;
    background: var(--bg-main) !important;
}

.filters-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--bg-card) !important;
    padding: 1.5rem;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    flex-wrap: wrap;
    gap: 1rem;
    backdrop-filter: blur(10px);
}

.filter-buttons {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.filter-btn {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid var(--border-color);
    border-radius: var(--radius);
    color: var(--text-muted);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    overflow: hidden;
}

.btn-glow {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.filter-btn:hover .btn-glow {
    left: 100%;
}

.filter-btn:hover,
.filter-btn.active {
    background: var(--primary) !important;
    color: var(--text-white);
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: var(--shadow-glow);
}

.explore-all-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    background: linear-gradient(135deg, var(--secondary), #f472b6) !important;
    color: white;
    text-decoration: none;
    border-radius: var(--radius);
    font-weight: 600;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.explore-all-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.arrow-move {
    transition: var(--transition);
}

.explore-all-btn:hover .arrow-move {
    transform: translateX(5px);
}

/* === PRODUCTOS GRID MEJORADO === */
.products-section {
    background: var(--bg-main) !important;
    padding: 0;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(320px, 100%), 1fr));
    gap: clamp(1rem, 3vw, 2rem);
    background: var(--bg-main) !important;
    padding: 0;
}

.product-card {
    position: relative;
    animation: cardSlideUp 0.6s ease-out forwards;
    opacity: 0;
    transform: translateY(30px);
    background: transparent !important;
}

.product-card-inner {
    background: var(--bg-card) !important;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: var(--transition);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.card-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    opacity: 0;
    transition: var(--transition);
    z-index: -1;
    border-radius: var(--radius);
    filter: blur(20px);
}

.product-card:hover .product-card-inner {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
}

.product-card:hover .card-glow {
    opacity: 0.3;
}

.product-card.out-of-stock .product-card-inner {
    opacity: 0.7;
    filter: grayscale(0.3);
}

/* === EFECTOS MEJORADOS === */
.shine-effect {
    position: absolute;
    top: 0;
    left: -150%;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, transparent 0%, rgba(255, 255, 255, 0.3) 50%, transparent 100%);
    transform: skewX(-25deg);
    transition: left 0.7s ease-in-out;
    z-index: 2;
    pointer-events: none;
}

.product-card:hover .shine-effect {
    left: 150%;
}

/* === BADGES ANIMADOS === */
.product-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    backdrop-filter: blur(10px);
}

.product-badge.popular {
    background: linear-gradient(45deg, #fbbf24, #f59e0b) !important;
    color: #1f2937;
    box-shadow: 0 4px 15px rgba(251, 191, 36, 0.4);
}

.pulse-animation {
    animation: pulse 2s infinite;
}

.product-badge.sold-out {
    background: linear-gradient(45deg, var(--danger), #dc2626) !important;
    color: white;
}

/* === IMAGEN MEJORADA === */
.product-image {
    position: relative;
    height: 250px;
    overflow: hidden;
    background: var(--bg-card) !important;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--transition);
}

.product-card:hover .image-overlay {
    opacity: 1;
}

.quick-action-btn {
    position: relative;
    background: rgba(255, 255, 255, 0.2) !important;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
}

.quick-action-btn:hover {
    background: var(--primary) !important;
    transform: scale(1.1);
}

.tooltip {
    position: absolute;
    bottom: -35px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--bg-card);
    color: var(--text-white);
    padding: 0.5rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    white-space: nowrap;
    opacity: 0;
    transition: var(--transition);
    pointer-events: none;
}

.quick-action-btn:hover .tooltip {
    opacity: 1;
}

/* === CONTENIDO MEJORADO === */
.product-content {
    padding: 1.5rem;
    flex-grow: 1;
    background: var(--bg-card) !important;
}

.product-category {
    font-size: 0.75rem;
    color: var(--primary-light);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.product-title {
    font-size: clamp(1.125rem, 3vw, 1.25rem);
    font-weight: 700;
    color: var(--text-white);
    margin-bottom: 1rem;
    line-height: 1.3;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.stars {
    display: flex;
    gap: 0.125rem;
}

.stars .fa-star {
    color: #374151;
    font-size: 0.875rem;
    transition: var(--transition);
}

.stars .fa-star.filled {
    color: #fbbf24;
}

.star-animate {
    animation: starGlow 2s ease-in-out infinite;
}

.rating-count {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.price-section {
    display: flex;
    align-items: baseline;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.price-main {
    font-size: clamp(1.5rem, 4vw, 1.75rem);
    font-weight: 900;
    color: var(--primary-light);
}

.price-currency {
    font-size: 0.875rem;
    color: var(--text-muted);
}

.stock-info {
    margin-bottom: 1rem;
}

.stock-tag {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.375rem 0.75rem;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    backdrop-filter: blur(10px);
}

.stock-tag.available {
    background: rgba(16, 185, 129, 0.1) !important;
    color: var(--accent);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.stock-tag.unavailable {
    background: rgba(239, 68, 68, 0.1) !important;
    color: var(--danger);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

/* === BOTONES MEJORADOS === */
.product-actions {
    padding: 0 1.5rem 1.5rem;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 0.75rem;
    background: var(--bg-card) !important;
}

.action-btn {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem;
    border: none;
    border-radius: var(--radius);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
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

.btn-buy {
    background: linear-gradient(135deg, var(--accent), #059669) !important;
    color: white;
}

.btn-buy:hover {
    background: linear-gradient(135deg, #059669, #047857) !important;
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.btn-cart {
    background: rgba(255, 255, 255, 0.05) !important;
    color: var(--text-muted);
    border: 1px solid var(--border-color);
    width: 50px;
}

.btn-cart:hover {
    background: var(--secondary) !important;
    color: white;
    border-color: var(--secondary);
}

.btn-disabled {
    background: rgba(239, 68, 68, 0.2) !important;
    color: var(--danger);
    cursor: not-allowed;
    opacity: 0.7;
}

/* === TARJETA EXPLORAR MEJORADA === */
.explore-card {
    position: relative;
    background: var(--bg-card) !important;
    border-radius: var(--radius);
    border: 2px dashed var(--border-color);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: var(--text-muted);
    text-decoration: none;
    transition: var(--transition);
    min-height: 450px;
    overflow: hidden;
}

.explore-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: var(--transition);
}

.explore-pattern {
    width: 100%;
    height: 100%;
    background-image: 
        linear-gradient(45deg, rgba(99, 102, 241, 0.05) 25%, transparent 25%),
        linear-gradient(-45deg, rgba(99, 102, 241, 0.05) 25%, transparent 25%);
    background-size: 20px 20px;
    animation: patternMove 10s linear infinite;
}

.explore-card:hover {
    border-color: var(--primary);
    color: var(--text-white);
    background: rgba(99, 102, 241, 0.05) !important;
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}

.explore-card:hover .explore-background {
    opacity: 1;
}

.explore-content {
    position: relative;
    z-index: 1;
}

.explore-icon {
    position: relative;
    font-size: 3rem;
    width: 100px;
    height: 100px;
    border: 2px solid var(--border-color);
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2rem;
    transition: var(--transition);
}

.icon-pulse {
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    border: 2px solid var(--primary);
    border-radius: var(--radius);
    opacity: 0;
    animation: iconPulse 2s infinite;
}

.explore-card:hover .explore-icon {
    background: var(--primary) !important;
    color: var(--text-white);
    border-color: var(--primary);
    transform: rotate(-10deg) scale(1.1);
}

.explore-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.explore-subtitle {
    font-size: 1rem;
    margin-bottom: 2rem;
}

.explore-arrow {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05) !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    transition: var(--transition);
}

.explore-card:hover .explore-arrow {
    background: var(--primary) !important;
    transform: translateX(10px) scale(1.1);
}

/* === ESTADO VACÍO === */
.empty-state {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
    text-align: center;
    background: var(--bg-main) !important;
    grid-column: 1 / -1;
}

.empty-state-content {
    max-width: 400px;
}

.empty-state-icon {
    font-size: 4rem;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
    animation: bounce 2s infinite;
}

.empty-state h3 {
    font-size: 1.5rem;
    color: var(--text-white);
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--text-muted);
    margin-bottom: 2rem;
}

.btn-explore {
    background: var(--primary) !important;
    color: white;
    border: none;
    padding: 0.875rem 1.5rem;
    border-radius: var(--radius);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.btn-explore:hover {
    background: var(--primary-dark) !important;
    transform: translateY(-2px);
}

/* === CARRITO FLOTANTE MEJORADO === */
.cart-floating-btn {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, var(--secondary), #f472b6) !important;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.75rem;
    cursor: pointer;
    box-shadow: 0 15px 35px rgba(236, 72, 153, 0.4);
    transition: var(--transition);
    z-index: 9999;
    animation: floatCart 3s ease-in-out infinite;
    border: 3px solid rgba(255, 255, 255, 0.2);
}

.cart-floating-btn:hover {
    transform: scale(1.15);
    box-shadow: 0 20px 40px rgba(236, 72, 153, 0.6);
}

.cart-counter {
    position: absolute;
    top: -10px;
    right: -10px;
    background: linear-gradient(135deg, var(--danger), #dc2626) !important;
    color: white;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 700;
    border: 3px solid white;
    animation: pulse 2s infinite;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
}

/* === ANIMACIONES MEJORADAS === */
@keyframes cardSlideUp {
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

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

@keyframes pulse {
    0%, 100% { 
        transform: scale(1); 
        box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.7);
    }
    50% { 
        transform: scale(1.05); 
        box-shadow: 0 0 0 10px rgba(251, 191, 36, 0);
    }
}

@keyframes starGlow {
    0%, 100% { 
        transform: scale(1); 
        filter: brightness(1);
    }
    50% { 
        transform: scale(1.1); 
        filter: brightness(1.3);
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

@keyframes patternMove {
    0% { 
        transform: translateX(0) translateY(0); 
    }
    100% { 
        transform: translateX(20px) translateY(20px); 
    }
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

@keyframes floatCart {
    0%, 100% { 
        transform: translateY(0px); 
    }
    50% { 
        transform: translateY(-10px); 
    }
}

@keyframes cardSlideDown {
    to { 
        opacity: 0; 
        transform: translateY(-30px) scale(0.95); 
    }
}

@keyframes successPulse {
    0% { 
        transform: scale(0); 
        opacity: 0; 
    }
    50% { 
        transform: scale(1.1); 
        opacity: 1; 
    }
    100% { 
        transform: scale(1); 
        opacity: 1; 
    }
}

@keyframes errorShake {
    0%, 100% { 
        transform: translateX(0); 
    }
    25% { 
        transform: translateX(-5px); 
    }
    75% { 
        transform: translateX(5px); 
    }
}

/* === ANIMACIONES DE ENTRADA === */
.animate-bounce-in {
    animation: bounceIn 0.8s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out;
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

/* === RESPONSIVE MEJORADO === */
@media (max-width: 1024px) {
    .filters-container {
        flex-direction: column;
        align-items: stretch;
    }
    
    .hero-stats {
        flex-direction: column;
        align-items: center;
    }
    
    .stat-divider {
        width: 40px;
        height: 1px;
    }
}

@media (max-width: 768px) {
    /* --- AJUSTES GENERALES DEL CONTENEDOR --- */
    .tienda-container {
        padding: 0.75rem;
    }

    /* --- OCULTAR ELEMENTOS NO ESENCIALES EN MÓVIL --- */
    .results-info {
        display: none;
    }

    /* --- GRID DE PRODUCTOS MEJORADO --- */
    .products-grid {
        /* ✨ ESTE ES EL CAMBIO CLAVE ✨ */
        /* Forzamos una cuadrícula de 2 columnas de igual tamaño */
        grid-template-columns: repeat(2, 1fr); 
        
        /* Mantenemos un espacio adecuado entre tarjetas */
        gap: 0.75rem; 
    }

    .product-card-inner {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    /* --- CONTENIDO DE LA TARJETA --- */
    .product-content {
        padding: 1rem;
    }
    .product-title {
        font-size: 0.9rem; /* Un poco más pequeño para que no se corte */
        margin-bottom: 0.5rem;
        line-height: 1.3; /* Mejor interlineado */
        height: 2.6rem; /* Altura fija para 2 líneas y alinear tarjetas */
        overflow: hidden; /* Oculta el texto que no quepa */
    }
    .price-main {
        font-size: 1.4rem;
    }

    /* --- MEJORA DE BOTONES (CORRECCIÓN FINAL) --- */
    .product-actions {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 0.5rem;
        padding: 0.75rem; /* Padding reducido para dar más espacio */
        align-items: center;
    }

    /* ✨ ESTA ES LA MEJORA CLAVE PARA TODOS LOS BOTONES ✨ */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border-radius: 0.75rem;
        font-weight: 700;
        transition: transform 0.2s ease;
    }
    
    /* El botón principal de compra */
    .action-btn.btn-buy {
        padding: 0.8rem;
        font-size: 0.8rem; /* Ajustado para el nuevo tamaño */
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    /* El botón del carrito */
    .action-btn.btn-cart {
        width: 45px; /* Un poco más pequeño */
        height: 45px;
        font-size: 1rem;
        padding: 0;
    }
    
    .action-btn:active {
        transform: scale(0.97);
    }

    /* --- OTROS AJUSTES RESPONSIVE --- */
    .pagination-links, .filter-buttons {
        justify-content: center;
        flex-wrap: wrap;
    }

    .cart-floating-btn {
        width: 60px;
        height: 60px;
        bottom: 1.5rem;
        right: 1.5rem;
    }
}

@media (max-width: 480px) {
    .hero-section {
        padding: 2rem 1rem;
    }
    
    .product-card-inner {
        margin: 0;
    }
    
    .filters-container {
        padding: 1rem;
    }
    
    .filter-btn {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
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
.animated-toast {
    border-radius: 1rem !important;
    backdrop-filter: blur(10px) !important;
}

.animated-modal {
    border-radius: 1rem !important;
    backdrop-filter: blur(10px) !important;
}

.animated-btn {
    border-radius: 0.75rem !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
}

.animated-btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
}

.animated-btn-secondary {
    border-radius: 0.75rem !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
}

.animated-btn-success {
    border-radius: 0.75rem !important;
    font-weight: 600 !important;
    background: linear-gradient(135deg, var(--accent), #059669) !important;
}

.animated-btn-error {
    border-radius: 0.75rem !important;
    font-weight: 600 !important;
    background: linear-gradient(135deg, var(--danger), #dc2626) !important;
}

.processing-modal {
    border-radius: 1rem !important;
}

.success-modal {
    border-radius: 1rem !important;
}

.error-modal {
    border-radius: 1rem !important;
}

.quick-view-modal {
    border-radius: 1rem !important;
}

.purchase-modal {
    border-radius: 1rem !important;
}
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // === OBTENER ID DE USUARIO ACTUAL ===
    const getCurrentUserId = () => {
        const userMeta = document.querySelector('meta[name="user-id"]');
        return userMeta ? userMeta.getAttribute('content') : 'guest_' + Date.now();
    };

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
    document.querySelectorAll('.product-card, .counter, .animate-slide-up').forEach(el => {
        observer.observe(el);
    });
    
    // === FUNCIONES DE UTILIDAD ===
    const formatPrice = (value) => new Intl.NumberFormat('es-CL', { 
        style: 'currency', 
        currency: 'CLP', 
        minimumFractionDigits: 0 
    }).format(value);
    
    const getCsrfToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // === CLASE PRINCIPAL PARA LA TIENDA MEJORADA ===
    class ShopManager {
        constructor() {
            this.userId = getCurrentUserId();
            this.cartKey = `npstreaming_cart_${this.userId}`;
            this.cart = this.loadCart();
            this.createCartButton();
            this.updateCartCounter();
            this.initEventListeners();
            
            console.log(`🛒 Carrito inicializado para usuario: ${this.userId}`);
        }
        
        // === GESTIÓN DE CARRITO POR USUARIO ===
        loadCart() {
            try {
                const cartData = localStorage.getItem(this.cartKey);
                const cart = cartData ? JSON.parse(cartData) : [];
                console.log(`📦 Carrito cargado:`, cart);
                return cart;
            } catch (error) {
                console.error('Error cargando carrito:', error);
                return [];
            }
        }

        saveCart() {
            try {
                if (this.cart.length > 0) {
                    localStorage.setItem(this.cartKey, JSON.stringify(this.cart));
                    console.log(`💾 Carrito guardado:`, this.cart);
                } else {
                    localStorage.removeItem(this.cartKey);
                    console.log(`🗑️ Carrito vacío eliminado del localStorage`);
                }
                this.updateCartCounter();
            } catch (error) {
                console.error('Error guardando carrito:', error);
            }
        }

        clearCart() { 
            console.log(`🧹 Limpiando carrito completo para usuario: ${this.userId}`);
            
            // Limpiar el array del carrito
            this.cart = []; 
            
            // Eliminar completamente del localStorage
            localStorage.removeItem(this.cartKey);
            
            // Actualizar el contador visual
            this.updateCartCounter();
            
            // Verificar que se eliminó correctamente
            const verification = localStorage.getItem(this.cartKey);
            console.log(`✅ Verificación de limpieza:`, verification === null ? 'Eliminado correctamente' : 'Error en eliminación');
        }
        
        initEventListeners() {
            // Agregar efectos de ripple a botones
            document.querySelectorAll('.action-btn').forEach(btn => {
                btn.addEventListener('click', createRipple);
            });
        }

        addItem(product, quantity = 1) {
            console.log(`➕ Agregando producto:`, product);
            
            const existing = this.cart.find(item => item.id === product.id);
            const stock = parseInt(product.stock);
            
            if (existing) {
                const newQuantity = Math.min(existing.quantity + quantity, stock);
                existing.quantity = newQuantity;
                console.log(`📈 Cantidad actualizada a: ${newQuantity}`);
            } else {
                this.cart.push({ ...product, quantity });
                console.log(`🆕 Producto nuevo agregado`);
            }
            
            this.saveCart();
            
            // Notificación mejorada
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '¡Agregado al carrito!',
                text: `${product.name} añadido exitosamente`,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: 'var(--bg-card)',
                color: 'var(--text-white)',
                customClass: {
                    popup: 'animated-toast'
                }
            });
        }
        
        removeItem(productId) { 
            console.log(`❌ Eliminando producto ID: ${productId}`);
            
            const initialLength = this.cart.length;
            const productToRemove = this.cart.find(item => item.id === productId.toString());
            
            if (productToRemove) {
                this.cart = this.cart.filter(item => item.id !== productId.toString()); 
                console.log(`🗑️ Producto "${productToRemove.name}" eliminado`);
                console.log(`📊 Productos antes: ${initialLength}, después: ${this.cart.length}`);
                
                this.saveCart();
                
                // Mostrar notificación de eliminación
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Producto eliminado',
                    text: `${productToRemove.name} removido del carrito`,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    background: 'var(--bg-card)',
                    color: 'var(--text-white)',
                    customClass: {
                        popup: 'animated-toast'
                    }
                });
                
                return true;
            } else {
                console.error(`❌ No se encontró producto con ID: ${productId}`);
                return false;
            }
        }
        
        updateQuantity(productId, quantity) { 
            console.log(`🔢 Actualizando cantidad del producto ${productId} a ${quantity}`);
            
            const item = this.cart.find(i => i.id === productId.toString()); 
            if(item){ 
                const newQuantity = Math.max(1, Math.min(quantity, parseInt(item.stock)));
                item.quantity = newQuantity;
                console.log(`✅ Nueva cantidad: ${newQuantity}`);
                this.saveCart();
                return true;
            } else {
                console.error(`❌ No se encontró producto con ID: ${productId}`);
                return false;
            }
        }
        
        getTotal() { 
            return this.cart.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0); 
        }
        
        getItemCount() { 
            return this.cart.reduce((sum, item) => sum + item.quantity, 0); 
        }
        
        createCartButton() { 
            // Remover botón existente si existe
            const existingBtn = document.querySelector('.cart-floating-btn');
            if (existingBtn) {
                existingBtn.remove();
            }
            
            const btn = document.createElement('div');
            btn.className = 'cart-floating-btn';
            
            btn.innerHTML = `
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-counter">0</span>
            `; 
            
            btn.addEventListener('click', () => this.showCartModal()); 
            btn.addEventListener('mouseenter', () => {
                btn.style.transform = 'scale(1.15)';
                btn.style.boxShadow = '0 20px 40px rgba(236, 72, 153, 0.6)';
            });
            btn.addEventListener('mouseleave', () => {
                btn.style.transform = 'scale(1)';
                btn.style.boxShadow = '0 15px 35px rgba(236, 72, 153, 0.4)';
            });
            
            document.body.appendChild(btn); 
        }

        updateCartCounter() { 
            const counter = document.querySelector('.cart-counter'); 
            if (counter) { 
                const count = this.getItemCount(); 
                counter.textContent = count; 
                counter.style.display = count > 0 ? 'flex' : 'none'; 
                console.log(`🔢 Contador actualizado: ${count}`);
            } 
        }
        
        refreshCartModal() { 
            if (Swal.isVisible()) { 
                Swal.close(); 
                setTimeout(() => this.showCartModal(), 150); 
            } 
        }

        showCartModal() {
            console.log(`👁️ Mostrando carrito con ${this.cart.length} productos`);
            
            if (this.cart.length === 0) { 
                Swal.fire({ 
                    title: '🛒 Carrito Vacío', 
                    html: `
                        <div style="text-align: center; padding: 2rem;">
                            <div style="
                                width: 80px; 
                                height: 80px; 
                                background: linear-gradient(135deg, var(--primary), var(--primary-light)); 
                                border-radius: 50%; 
                                display: flex; 
                                align-items: center; 
                                justify-content: center; 
                                margin: auto; 
                                font-size: 2rem; 
                                color: white;
                                margin-bottom: 1.5rem;
                            ">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <p style="color: var(--text-muted); margin-bottom: 1rem;">No tienes productos en tu carrito</p>
                            <p style="color: var(--text-muted);">¡Explora nuestra tienda y encuentra increíbles ofertas!</p>
                        </div>
                    `,
                    icon: 'info', 
                    background: 'var(--bg-card)', 
                    color: 'var(--text-white)', 
                    confirmButtonText: '<i class="fas fa-store"></i> Seguir Comprando', 
                    confirmButtonColor: 'var(--primary)',
                    customClass: {
                        popup: 'animated-modal'
                    }
                }); 
                return; 
            }
            
            const itemsHtml = this.cart.map((item, index) => `
                <div class="cart-item" id="cart-item-${item.id}" style="
                    display: flex; 
                    align-items: center; 
                    gap: 1rem; 
                    padding: 1.5rem; 
                    background: rgba(255,255,255,0.03); 
                    border-radius: 1rem; 
                    margin-bottom: 1rem;
                    border: 1px solid var(--border-color);
                    transition: var(--transition);
                    animation: slideInRight 0.3s ease-out ${index * 0.1}s both;
                " onmouseover="this.style.background='rgba(255,255,255,0.06)'" onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                    <img src="${item.image}" alt="${item.name}" style="
                        width: 70px; 
                        height: 70px; 
                        border-radius: 0.75rem; 
                        object-fit: cover;
                        border: 1px solid var(--border-color);
                    ">
                    <div class="cart-item-details" style="flex: 1; text-align: left;">
                        <h4 style="font-size: 1.1rem; font-weight: 600; color: var(--text-white); margin: 0 0 0.5rem 0;">${item.name}</h4>
                        <div style="font-size: 1rem; color: var(--primary-light); font-weight: 600;">${formatPrice(item.price)}</div>
                    </div>
                    <div class="cart-item-controls" style="display: flex; align-items: center; gap: 1.5rem;">
                        <div class="quantity-controls" style="display: flex; align-items: center; gap: 0.75rem;">
                            <button class="qty-btn-decrease" onclick="shopManager.updateQuantityAndRefresh('${item.id}', ${item.quantity - 1})" style="
                                width: 35px; 
                                height: 35px; 
                                border-radius: 50%; 
                                border: 1px solid var(--border-color); 
                                background: var(--bg-main); 
                                color: var(--text-white); 
                                cursor: pointer;
                                transition: var(--transition);
                                font-weight: 600;
                                ${item.quantity <= 1 ? 'opacity: 0.5; cursor: not-allowed;' : ''}
                            " ${item.quantity <= 1 ? 'disabled' : ''} onmouseover="if(!this.disabled) this.style.background='var(--primary)'" onmouseout="if(!this.disabled) this.style.background='var(--bg-main)'">&minus;</button>
                            <span class="quantity-display" style="
                                min-width: 35px; 
                                text-align: center; 
                                font-weight: 700; 
                                color: var(--text-white);
                                font-size: 1.1rem;
                            ">${item.quantity}</span>
                            <button class="qty-btn-increase" onclick="shopManager.updateQuantityAndRefresh('${item.id}', ${item.quantity + 1})" style="
                                width: 35px; 
                                height: 35px; 
                                border-radius: 50%; 
                                border: 1px solid var(--border-color); 
                                background: var(--bg-main); 
                                color: var(--text-white); 
                                cursor: pointer;
                                transition: var(--transition);
                                font-weight: 600;
                            " onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--bg-main)'">&plus;</button>
                        </div>
                        <div class="cart-item-total" style="font-weight: 700; color: var(--accent); min-width: 100px; text-align: right; font-size: 1.1rem;">${formatPrice(parseFloat(item.price) * item.quantity)}</div>
                        <button class="remove-item-btn" onclick="shopManager.removeItemWithConfirmation('${item.id}', '${item.name.replace(/'/g, '\\\'')}')" style="
                            width: 35px; 
                            height: 35px; 
                            border-radius: 50%; 
                            border: 1px solid var(--danger); 
                            background: rgba(239, 68, 68, 0.1); 
                            color: var(--danger); 
                            cursor: pointer;
                            transition: var(--transition);
                        " onmouseover="this.style.background='var(--danger)'; this.style.color='white'" onmouseout="this.style.background='rgba(239, 68, 68, 0.1)'; this.style.color='var(--danger)'">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `).join('');
            
            Swal.fire({
                title: `🛒 Mi Carrito (${this.getItemCount()} productos)`,
                html: `
                    <div class="cart-modal-container" style="max-height: 500px; overflow-y: auto; padding-right: 10px;">
                        <div class="user-info" style="
                            background: rgba(99, 102, 241, 0.1); 
                            border: 1px solid rgba(99, 102, 241, 0.2); 
                            border-radius: 1rem; 
                            padding: 1rem; 
                            margin-bottom: 1.5rem;
                            text-align: center;
                        ">
                            <div style="font-size: 0.875rem; color: var(--primary-light);">
                                <i class="fas fa-user"></i> Usuario: ${this.userId.includes('guest') ? 'Invitado' : this.userId}
                            </div>
                        </div>
                        <div class="cart-items-list">${itemsHtml}</div>
                        <div class="cart-summary" style="
                            border-top: 2px solid var(--border-color); 
                            padding-top: 2rem; 
                            margin-top: 2rem;
                            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1));
                            padding: 2rem;
                            border-radius: 1rem;
                            border: 1px solid var(--border-color);
                        ">
                            <div class="total-row final-total" style="
                                display: flex; 
                                justify-content: space-between; 
                                font-size: 1.5rem; 
                                font-weight: 900;
                                color: var(--accent);
                                margin-bottom: 1rem;
                            ">
                                <span>Total:</span>
                                <span>${formatPrice(this.getTotal())}</span>
                            </div>
                            <div style="font-size: 0.875rem; color: var(--text-muted); text-align: center;">
                                <i class="fas fa-shield-alt"></i> Compra 100% segura
                            </div>
                        </div>
                    </div>
                `,
                width: '900px', 
                background: 'var(--bg-card)', 
                color: 'var(--text-white)', 
                showConfirmButton: true, 
                confirmButtonText: '<i class="fas fa-credit-card"></i> Proceder al Checkout', 
                showCancelButton: true, 
                cancelButtonText: '<i class="fas fa-shopping-bag"></i> Seguir Comprando', 
                confirmButtonColor: 'var(--accent)',
                cancelButtonColor: 'var(--primary)',
                customClass: {
                    popup: 'animated-modal',
                    confirmButton: 'animated-btn',
                    cancelButton: 'animated-btn-secondary'
                }
            }).then(result => { 
                if (result.isConfirmed) {
                    this.processCartOrder(this.cart);
                }
            });
        }

        // === MÉTODOS AUXILIARES PARA EL CARRITO ===
        updateQuantityAndRefresh(productId, newQuantity) {
            console.log(`🔄 Actualizando cantidad y refrescando: ${productId} -> ${newQuantity}`);
            
            if (newQuantity < 1) {
                console.log(`⚠️ Cantidad menor a 1, no se actualiza`);
                return;
            }
            
            if (this.updateQuantity(productId, newQuantity)) {
                this.refreshCartModal();
            }
        }

        removeItemWithConfirmation(productId, productName) {
            console.log(`🤔 Solicitando confirmación para eliminar: ${productName}`);
            
            Swal.fire({
                title: '¿Eliminar producto?',
                html: `
                    <div style="text-align: center; padding: 1rem;">
                        <div style="
                            width: 60px; 
                            height: 60px; 
                            background: rgba(239, 68, 68, 0.1); 
                            border-radius: 50%; 
                            display: flex; 
                            align-items: center; 
                            justify-content: center; 
                            margin: auto; 
                            font-size: 1.5rem; 
                            color: var(--danger);
                            margin-bottom: 1rem;
                        ">
                            <i class="fas fa-trash"></i>
                        </div>
                        <p style="color: var(--text-white); margin-bottom: 0.5rem;">¿Estás seguro de que quieres eliminar</p>
                        <p style="color: var(--primary-light); font-weight: 600;">"${productName}"</p>
                        <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 1rem;">Esta acción no se puede deshacer</p>
                    </div>
                `,
                background: 'var(--bg-card)',
                color: 'var(--text-white)',
                showConfirmButton: true,
                confirmButtonText: '<i class="fas fa-trash"></i> Sí, eliminar',
                showCancelButton: true,
                cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
                confirmButtonColor: 'var(--danger)',
                cancelButtonColor: 'var(--text-muted)',
                customClass: {
                    popup: 'animated-modal',
                    confirmButton: 'animated-btn-error',
                    cancelButton: 'animated-btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(`✅ Confirmado, eliminando producto: ${productId}`);
                    if (this.removeItem(productId)) {
                        this.refreshCartModal();
                    }
                } else {
                    console.log(`❌ Cancelado, no se elimina el producto`);
                }
            });
        }
        
        async processCartOrder(items) {
            if (!items || items.length === 0) {
                console.error(`❌ No hay productos para procesar`);
                return;
            }
            
            console.log(`🚀 Procesando orden del carrito:`, items);
            
            Swal.fire({ 
                title: '🔄 Procesando Pedido', 
                html: `
                    <div class="processing-container" style="text-align: center; padding: 3rem;">
                        <div style="
                            width: 80px; 
                            height: 80px; 
                            border: 4px solid rgba(16, 185, 129, 0.2); 
                            border-top-color: var(--accent); 
                            border-radius: 50%; 
                            animation: spin 1s linear infinite; 
                            margin: auto;
                        "></div>
                        <h3 style="margin: 2rem 0 1rem 0; color: var(--text-white);">Validando tu compra...</h3>
                        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Procesando ${items.length} producto${items.length > 1 ? 's' : ''}</p>
                        <div style="
                            background: rgba(16, 185, 129, 0.1); 
                            border: 1px solid rgba(16, 185, 129, 0.2); 
                            border-radius: 1rem; 
                            padding: 1rem; 
                            font-size: 0.875rem; 
                            color: var(--accent);
                        ">
                            <i class="fas fa-clock"></i> Esto puede tomar unos segundos
                        </div>
                    </div>
                `, 
                background: 'var(--bg-card)', 
                color: 'var(--text-white)', 
                allowOutsideClick: false, 
                showConfirmButton: false,
                customClass: {
                    popup: 'processing-modal'
                }
            });
            
            try {
                const payload = { 
                    items: items.map(item => ({ 
                        producto_id: parseInt(item.id, 10), 
                        quantity: parseInt(item.quantity, 10) 
                    })) 
                };
                
                console.log(`📤 Enviando payload:`, payload);
                
                const response = await fetch("{{ route('compras.procesar.carrito') }}", {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': getCsrfToken(), 
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify(payload)
                });
                
                const result = await response.json();
                console.log(`📥 Respuesta del servidor:`, result);
                
                if (result.success) {
                    console.log(`✅ Compra exitosa, limpiando carrito`);
                    this.clearCart();
                    
                    Swal.fire({
                        title: '🎉 ¡Compra Exitosa!',
                        html: `
                            <div class="success-container" style="text-align: center; padding: 2rem;">
                                <div style="
                                    width: 100px; 
                                    height: 100px; 
                                    background: linear-gradient(135deg, var(--accent), #059669); 
                                    border-radius: 50%; 
                                    display: flex; 
                                    align-items: center; 
                                    justify-content: center; 
                                    margin: auto; 
                                    font-size: 2.5rem; 
                                    color: white;
                                    animation: successPulse 1s ease-out;
                                    margin-bottom: 2rem;
                                ">
                                    <i class="fas fa-check"></i>
                                </div>
                                <h3 style="margin-bottom: 1rem; color: var(--text-white);">Tu pedido ha sido procesado exitosamente</h3>
                                <p style="color: var(--text-muted); margin-bottom: 2rem;">
                                    Recibirás un email con los detalles de tu compra y las credenciales
                                </p>
                                <div style="
                                    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05)); 
                                    border: 1px solid rgba(16, 185, 129, 0.2); 
                                    border-radius: 1rem; 
                                    padding: 1.5rem; 
                                    margin-bottom: 2rem;
                                ">
                                    <div style="font-size: 1rem; color: var(--accent); margin-bottom: 0.5rem;">
                                        <i class="fas fa-envelope"></i> Revisa tu email
                                    </div>
                                    <div style="font-size: 0.875rem; color: var(--text-muted);">
                                        Las credenciales serán enviadas a tu email registrado
                                    </div>
                                </div>
                            </div>
                        `,
                        background: 'var(--bg-card)',
                        color: 'var(--text-white)',
                        confirmButtonText: '<i class="fas fa-receipt"></i> Ver Compra',
                        confirmButtonColor: 'var(--accent)',
                        allowOutsideClick: false,
                        customClass: {
                            popup: 'success-modal',
                            confirmButton: 'animated-btn-success'
                        }
                    }).then(() => {
                        // Redirección correcta a compras/exitosa
                        window.location.href = "{{ route('compras.exitosa') }}";
                    });
                } else {
                    throw new Error(result.message || 'Error al procesar la compra');
                }
            } catch (error) {
                console.error('❌ Error en processCartOrder:', error);
                
                Swal.fire({
                    title: '❌ Error en la Compra',
                    html: `
                        <div class="error-container" style="text-align: center; padding: 2rem;">
                            <div style="
                                width: 100px; 
                                height: 100px; 
                                background: linear-gradient(135deg, var(--danger), #dc2626); 
                                border-radius: 50%; 
                                display: flex; 
                                align-items: center; 
                                justify-content: center; 
                                margin: auto; 
                                font-size: 2.5rem; 
                                color: white;
                                animation: errorShake 0.5s ease-out;
                                margin-bottom: 2rem;
                            ">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h3 style="margin-bottom: 1rem; color: var(--text-white);">No se pudo procesar tu compra</h3>
                            <p style="color: var(--text-muted); margin-bottom: 2rem;">
                                ${error.message || 'Ha ocurrido un error inesperado'}
                            </p>
                            <div style="
                                background: rgba(239, 68, 68, 0.1); 
                                border: 1px solid rgba(239, 68, 68, 0.2); 
                                border-radius: 1rem; 
                                padding: 1.5rem;
                            ">
                                <div style="font-size: 1rem; color: var(--danger); margin-bottom: 0.5rem;">
                                    <i class="fas fa-headset"></i> Contacta Soporte
                                </div>
                                <div style="font-size: 0.875rem; color: var(--text-muted);">
                                    Por favor intenta nuevamente o contacta nuestro soporte
                                </div>
                            </div>
                        </div>
                    `,
                    background: 'var(--bg-card)',
                    color: 'var(--text-white)',
                    confirmButtonText: '<i class="fas fa-redo"></i> Intentar Nuevamente',
                    showCancelButton: true,
                    cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
                    confirmButtonColor: 'var(--danger)',
                    cancelButtonColor: 'var(--text-muted)',
                    customClass: {
                        popup: 'error-modal',
                        confirmButton: 'animated-btn-error'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.processCartOrder(items);
                    }
                });
            }
        }

        // === COMPRA DIRECTA (SIN CARRITO) ===
        async processDirect(product) {
            console.log(`🛒 Procesando compra directa:`, product);
            
            if (!product || parseInt(product.stock) <= 0) {
                Swal.fire({
                    title: '⚠️ Producto No Disponible',
                    text: 'Este producto no está disponible en este momento',
                    icon: 'warning',
                    background: 'var(--bg-card)',
                    color: 'var(--text-white)',
                    confirmButtonColor: 'var(--warning)'
                });
                return;
            }

            // Modal de confirmación de compra directa
            const result = await Swal.fire({
                title: '🛒 Confirmar Compra',
                html: `
                    <div class="purchase-confirmation" style="text-align: left; padding: 1rem;">
                        <div class="product-preview" style="
                            display: flex; 
                            align-items: center; 
                            gap: 1rem; 
                            padding: 1.5rem; 
                            background: rgba(255,255,255,0.03); 
                            border-radius: 1rem; 
                            margin-bottom: 2rem;
                            border: 1px solid var(--border-color);
                        ">
                            <img src="${product.image}" alt="${product.name}" style="
                                width: 80px; 
                                height: 80px; 
                                border-radius: 1rem; 
                                object-fit: cover;
                                border: 1px solid var(--border-color);
                            ">
                            <div style="flex: 1;">
                                <h4 style="font-size: 1.25rem; font-weight: 600; color: var(--text-white); margin-bottom: 0.5rem;">${product.name}</h4>
                                <div style="font-size: 1.125rem; color: var(--accent); font-weight: 700;">${formatPrice(product.price)}</div>
                            </div>
                        </div>
                        
                        <div class="purchase-details" style="
                            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1));
                            border: 1px solid var(--border-color);
                            border-radius: 1rem;
                            padding: 1.5rem;
                            margin-bottom: 2rem;
                        ">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                                <span style="color: var(--text-muted);">Cantidad:</span>
                                <span style="color: var(--text-white); font-weight: 600;">1</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                                <span style="color: var(--text-muted);">Subtotal:</span>
                                <span style="color: var(--text-white); font-weight: 600;">${formatPrice(product.price)}</span>
                            </div>
                            <hr style="border: none; border-top: 1px solid var(--border-color); margin: 1rem 0;">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--text-white); font-weight: 700; font-size: 1.125rem;">Total:</span>
                                <span style="color: var(--accent); font-weight: 900; font-size: 1.25rem;">${formatPrice(product.price)}</span>
                            </div>
                        </div>

                        <div style="
                            background: rgba(16, 185, 129, 0.1); 
                            border: 1px solid rgba(16, 185, 129, 0.2); 
                            border-radius: 1rem; 
                            padding: 1rem; 
                            text-align: center;
                        ">
                            <div style="font-size: 0.875rem; color: var(--accent);">
                                <i class="fas fa-shield-alt"></i> Compra 100% segura - Entrega inmediata
                            </div>
                        </div>
                    </div>
                `,
                width: '600px',
                background: 'var(--bg-card)',
                color: 'var(--text-white)',
                showConfirmButton: true,
                confirmButtonText: '<i class="fas fa-credit-card"></i> Comprar Ahora',
                showCancelButton: true,
                cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
                confirmButtonColor: 'var(--accent)',
                cancelButtonColor: 'var(--text-muted)',
                customClass: {
                    popup: 'purchase-modal',
                    confirmButton: 'animated-btn',
                    cancelButton: 'animated-btn-secondary'
                }
            });

            if (result.isConfirmed) {
                console.log(`✅ Compra directa confirmada, procesando...`);
                
                // Procesar compra directa
                Swal.fire({ 
                    title: '🔄 Procesando Compra', 
                    html: `
                        <div class="processing-container" style="text-align: center; padding: 3rem;">
                            <div style="
                                width: 80px; 
                                height: 80px; 
                                border: 4px solid rgba(16, 185, 129, 0.2); 
                                border-top-color: var(--accent); 
                                border-radius: 50%; 
                                animation: spin 1s linear infinite; 
                                margin: auto;
                            "></div>
                            <h3 style="margin: 2rem 0 1rem 0; color: var(--text-white);">Procesando tu compra...</h3>
                            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">${product.name}</p>
                            <div style="
                                background: rgba(16, 185, 129, 0.1); 
                                border: 1px solid rgba(16, 185, 129, 0.2); 
                                border-radius: 1rem; 
                                padding: 1rem; 
                                font-size: 0.875rem; 
                                color: var(--accent);
                            ">
                                <i class="fas fa-clock"></i> Validando disponibilidad...
                            </div>
                        </div>
                    `, 
                    background: 'var(--bg-card)', 
                    color: 'var(--text-white)', 
                    allowOutsideClick: false, 
                    showConfirmButton: false,
                    customClass: {
                        popup: 'processing-modal'
                    }
                });

                try {
                    const payload = { 
                        items: [{ 
                            producto_id: parseInt(product.id, 10), 
                            quantity: 1 
                        }] 
                    };
                    
                    console.log(`📤 Enviando compra directa:`, payload);
                    
                    const response = await fetch("{{ route('compras.procesar.carrito') }}", {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': getCsrfToken(), 
                            'Accept': 'application/json' 
                        },
                        body: JSON.stringify(payload)
                    });
                    
                    const result = await response.json();
                    console.log(`📥 Respuesta compra directa:`, result);
                    
                    if (result.success) {
                        console.log(`✅ Compra directa exitosa`);
                        
                        Swal.fire({
                            title: '🎉 ¡Compra Exitosa!',
                            html: `
                                <div class="success-container" style="text-align: center; padding: 2rem;">
                                    <div style="
                                        width: 100px; 
                                        height: 100px; 
                                        background: linear-gradient(135deg, var(--accent), #059669); 
                                        border-radius: 50%; 
                                        display: flex; 
                                        align-items: center; 
                                        justify-content: center; 
                                        margin: auto; 
                                        font-size: 2.5rem; 
                                        color: white;
                                        animation: successPulse 1s ease-out;
                                        margin-bottom: 2rem;
                                    ">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <h3 style="margin-bottom: 1rem; color: var(--text-white);">¡${product.name} comprado exitosamente!</h3>
                                    <p style="color: var(--text-muted); margin-bottom: 2rem;">
                                        Tu compra ha sido procesada y recibirás las credenciales por email
                                    </p>
                                    <div style="
                                        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05)); 
                                        border: 1px solid rgba(16, 185, 129, 0.2); 
                                        border-radius: 1rem; 
                                        padding: 1.5rem; 
                                        margin-bottom: 2rem;
                                    ">
                                        <div style="font-size: 1rem; color: var(--accent); margin-bottom: 0.5rem;">
                                            <i class="fas fa-envelope"></i> Revisa tu email
                                        </div>
                                        <div style="font-size: 0.875rem; color: var(--text-muted);">
                                            Las credenciales serán enviadas inmediatamente
                                        </div>
                                    </div>
                                </div>
                            `,
                            background: 'var(--bg-card)',
                            color: 'var(--text-white)',
                            confirmButtonText: '<i class="fas fa-receipt"></i> Ver Compra',
                            confirmButtonColor: 'var(--accent)',
                            allowOutsideClick: false,
                            customClass: {
                                popup: 'success-modal',
                                confirmButton: 'animated-btn-success'
                            }
                        }).then(() => {
                            // Redirección correcta a compras/exitosa
                            window.location.href = "{{ route('compras.exitosa') }}";
                        });
                    } else {
                        throw new Error(result.message || 'Error al procesar la compra');
                    }
                } catch (error) {
                    console.error('❌ Error en compra directa:', error);
                    
                    Swal.fire({
                        title: '❌ Error en la Compra',
                        html: `
                            <div class="error-container" style="text-align: center; padding: 2rem;">
                                <div style="
                                    width: 100px; 
                                    height: 100px; 
                                    background: linear-gradient(135deg, var(--danger), #dc2626); 
                                    border-radius: 50%; 
                                    display: flex; 
                                    align-items: center; 
                                    justify-content: center; 
                                    margin: auto; 
                                    font-size: 2.5rem; 
                                    color: white;
                                    animation: errorShake 0.5s ease-out;
                                    margin-bottom: 2rem;
                                ">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <h3 style="margin-bottom: 1rem; color: var(--text-white);">No se pudo procesar tu compra</h3>
                                <p style="color: var(--text-muted); margin-bottom: 2rem;">
                                    ${error.message || 'Ha ocurrido un error inesperado'}
                                </p>
                                <div style="
                                    background: rgba(239, 68, 68, 0.1); 
                                    border: 1px solid rgba(239, 68, 68, 0.2); 
                                    border-radius: 1rem; 
                                    padding: 1.5rem;
                                ">
                                    <div style="font-size: 1rem; color: var(--danger); margin-bottom: 0.5rem;">
                                        <i class="fas fa-headset"></i> Contacta Soporte
                                    </div>
                                    <div style="font-size: 0.875rem; color: var(--text-muted);">
                                        Por favor intenta nuevamente o contacta nuestro soporte
                                    </div>
                                </div>
                            </div>
                        `,
                        background: 'var(--bg-card)',
                        color: 'var(--text-white)',
                        confirmButtonText: '<i class="fas fa-redo"></i> Intentar Nuevamente',
                        showCancelButton: true,
                        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
                        confirmButtonColor: 'var(--danger)',
                        cancelButtonColor: 'var(--text-muted)',
                        customClass: {
                            popup: 'error-modal',
                            confirmButton: 'animated-btn-error'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.processDirect(product);
                        }
                    });
                }
            } else {
                console.log(`❌ Compra directa cancelada`);
            }
        }
    }

    // === GESTIÓN DE PRODUCTOS ===
    class ProductManager {
        constructor() {
            this.initProductEvents();
        }

        initProductEvents() {
            console.log(`🎯 Inicializando eventos de productos`);
            
            // Eventos para botones de compra directa
            document.querySelectorAll('[data-action="buy-direct"]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const productCard = btn.closest('.product-card');
                    const product = this.extractProductData(productCard);
                    console.log(`🛒 Compra directa solicitada:`, product);
                    shopManager.processDirect(product);
                });
            });

            // Eventos para botones de carrito
            document.querySelectorAll('[data-action="cart"]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const productCard = btn.closest('.product-card');
                    const product = this.extractProductData(productCard);
                    console.log(`🛒 Agregar al carrito solicitado:`, product);
                    shopManager.addItem(product);
                });
            });

            // Eventos para vista rápida
            document.querySelectorAll('[data-action="quick-view"]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const productCard = btn.closest('.product-card');
                    const product = this.extractProductData(productCard);
                    console.log(`👁️ Vista rápida solicitada:`, product);
                    this.showQuickView(product);
                });
            });
            
            console.log(`✅ Eventos de productos inicializados correctamente`);
        }

        extractProductData(productCard) {
            const product = {
                id: productCard.dataset.id,
                name: productCard.dataset.name,
                price: parseFloat(productCard.dataset.price),
                stock: parseInt(productCard.dataset.stock),
                image: productCard.dataset.image,
                description: productCard.dataset.description,
                fullDescription: productCard.dataset.fullDescription
            };
            
            console.log(`📦 Datos del producto extraídos:`, product);
            return product;
        }

        showQuickView(product) {
            Swal.fire({
                title: product.name,
                html: `
                    <div class="quick-view-container" style="text-align: left;">
                        <div class="quick-view-image" style="text-align: center; margin-bottom: 1.5rem;">
                            <img src="${product.image}" alt="${product.name}" style="
                                max-width: 100%; 
                                height: 200px; 
                                object-fit: cover; 
                                border-radius: 1rem;
                                border: 1px solid var(--border-color);
                            ">
                        </div>
                        <div class="quick-view-details">
                            <div class="price-section" style="
                                display: flex; 
                                align-items: center; 
                                gap: 1rem; 
                                margin-bottom: 1rem;
                                padding: 1rem;
                                background: rgba(99, 102, 241, 0.1);
                                border-radius: 1rem;
                                border: 1px solid rgba(99, 102, 241, 0.2);
                            ">
                                <div class="price-main" style="font-size: 1.5rem; font-weight: 700; color: var(--primary-light);">
                                    ${formatPrice(product.price)}
                                </div>
                                <div class="stock-info" style="margin-left: auto;">
                                    <span class="stock-tag ${parseInt(product.stock) > 0 ? 'available' : 'unavailable'}" style="
                                        font-size: 0.75rem;
                                        font-weight: 600;
                                        padding: 0.375rem 0.75rem;
                                        border-radius: 50px;
                                        display: inline-flex;
                                        align-items: center;
                                        gap: 0.25rem;
                                        backdrop-filter: blur(10px);
                                        background: ${parseInt(product.stock) > 0 ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)'};
                                        color: ${parseInt(product.stock) > 0 ? 'var(--accent)' : 'var(--danger)'};
                                        border: 1px solid ${parseInt(product.stock) > 0 ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)'};
                                    ">
                                        <i class="fas ${parseInt(product.stock) > 0 ? 'fa-check-circle' : 'fa-times-circle'}"></i>
                                        ${parseInt(product.stock) > 0 ? `${product.stock} disponibles` : 'Agotado'}
                                    </span>
                                </div>
                            </div>
                            <div class="description" style="
                                color: var(--text-muted); 
                                line-height: 1.6; 
                                margin-bottom: 1.5rem;
                                padding: 1rem;
                                background: rgba(255, 255, 255, 0.02);
                                border-radius: 1rem;
                                border: 1px solid var(--border-color);
                            ">
                                ${product.fullDescription || product.description}
                            </div>
                            <div class="features" style="
                                display: grid; 
                                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); 
                                gap: 1rem;
                                margin-bottom: 1.5rem;
                            ">
                                <div class="feature-item" style="
                                    text-align: center; 
                                    padding: 1rem; 
                                    background: rgba(16, 185, 129, 0.1); 
                                    border-radius: 1rem;
                                    border: 1px solid rgba(16, 185, 129, 0.2);
                                ">
                                    <i class="fas fa-shield-alt" style="font-size: 1.5rem; color: var(--accent); margin-bottom: 0.5rem;"></i>
                                    <div style="font-size: 0.875rem; color: var(--text-white); font-weight: 600;">Garantía</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">100% Seguro</div>
                                </div>
                                <div class="feature-item" style="
                                    text-align: center; 
                                    padding: 1rem; 
                                    background: rgba(99, 102, 241, 0.1); 
                                    border-radius: 1rem;
                                    border: 1px solid rgba(99, 102, 241, 0.2);
                                ">
                                    <i class="fas fa-clock" style="font-size: 1.5rem; color: var(--primary-light); margin-bottom: 0.5rem;"></i>
                                    <div style="font-size: 0.875rem; color: var(--text-white); font-weight: 600;">Entrega</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">Inmediata</div>
                                </div>
                                <div class="feature-item" style="
                                    text-align: center; 
                                    padding: 1rem; 
                                    background: rgba(236, 72, 153, 0.1); 
                                    border-radius: 1rem;
                                    border: 1px solid rgba(236, 72, 153, 0.2);
                                ">
                                    <i class="fas fa-headset" style="font-size: 1.5rem; color: var(--secondary); margin-bottom: 0.5rem;"></i>
                                    <div style="font-size: 0.875rem; color: var(--text-white); font-weight: 600;">Soporte</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">24/7</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `,
                width: '600px',
                background: 'var(--bg-card)',
                color: 'var(--text-white)',
                showConfirmButton: parseInt(product.stock) > 0,
                confirmButtonText: '<i class="fas fa-shopping-cart"></i> Agregar al Carrito',
                showCancelButton: true,
                cancelButtonText: 'Cerrar',
                confirmButtonColor: 'var(--primary)',
                cancelButtonColor: 'var(--text-muted)',
                customClass: {
                    popup: 'quick-view-modal',
                    confirmButton: 'animated-btn',
                    cancelButton: 'animated-btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    shopManager.addItem(product);
                }
            });
        }
    }

    // === INICIALIZACIÓN PRINCIPAL ===
    console.log(`🚀 Inicializando sistema de tienda...`);
    
    const shopManager = new ShopManager();
    const productManager = new ProductManager();
    
    // Hacer shopManager accesible globalmente
    window.shopManager = shopManager;
    
    // === INICIALIZACIÓN FINAL ===
    setTimeout(() => {
        animateCounters();
    }, 500);

    console.log(`🎉 Sistema de carrito independiente por usuario cargado exitosamente!`);
    console.log(`📊 Estado inicial del carrito:`, shopManager.cart);
});
</script>
@endpush