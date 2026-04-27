@extends('layouts.app')

@section('content')
<div class="tienda-container">
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title-tienda">Catálogo de Productos</h1>
            <p class="hero-subtitle-tienda">Explora nuestras cuentas premium con la mejor calidad y soporte.</p>
        </div>
    </section>

    <section class="filters-section">
        <div class="filters-container">
            <form method="GET" action="{{ route('tienda.index') }}" class="filters-form" id="filters-form">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" placeholder="Buscar productos..." value="{{ request('search') }}" id="search-input">
                </div>
                <div class="filter-controls">
                    <div class="filter-buttons">
                        <button type="button" class="filter-btn {{ !request('filter') || request('filter') == 'all' ? 'active' : '' }}" data-filter="all">
                            <i class="fas fa-th"></i><span class="desktop-text">Todos</span>
                        </button>
                        <button type="button" class="filter-btn {{ request('filter') == 'popular' ? 'active' : '' }}" data-filter="popular">
                            <i class="fas fa-fire"></i><span class="desktop-text">Populares</span>
                        </button>
                        <button type="button" class="filter-btn {{ request('filter') == 'available' ? 'active' : '' }}" data-filter="available">
                            <i class="fas fa-check-circle"></i><span class="desktop-text">Disponibles</span>
                        </button>
                    </div>
                    <div class="sort-controls">
                        <select name="sort" class="sort-select" id="sort-select">
                            <option value="">Ordenar por</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nombre A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nombre Z-A</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio menor</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio mayor</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="filter" id="filter-input" value="{{ request('filter', 'all') }}">
            </form>
        </div>
    </section>

    @if($productos && $productos->count() > 0)
    <section class="products-section">
        <div class="products-grid" id="products-grid">
            @foreach ($productos as $producto)
                @php
                    $stock = $producto->stock_disponible ?? 0;
                    $isOutOfStock = $stock <= 0;
                    $isPopular = $producto->es_popular ?? false;
                    $hasDiscount = $producto->precio_original && $producto->precio_original > $producto->precio;
                    $discountPercent = $hasDiscount ? round((($producto->precio_original - $producto->precio) / $producto->precio_original) * 100) : 0;
                @endphp
                
                <div class="product-card-wrapper">
                    <div class="product-card {{ $isOutOfStock ? 'out-of-stock' : '' }}"
                         data-id="{{ $producto->id }}" 
                         data-name="{{ $producto->nombre ?? 'Producto' }}" 
                         data-price="{{ $producto->precio ?? 0 }}"
                         data-stock="{{ $stock }}" 
                         data-image="{{ $producto->imagen_display_url ?? 'https://placehold.co/400x300/111827/FFFFFF?text=Producto' }}"
                         data-description="{{ $producto->descripcion_corta ?? 'Sin descripción disponible.' }}"
                         data-full-description="{{ $producto->descripcion ?? 'Sin descripción completa.' }}">
                        
                        <div class="product-card-inner">
                            @if($hasDiscount)
                                <div class="product-badge discount">-{{ $discountPercent }}%</div>
                            @elseif($isOutOfStock)
                                <div class="product-badge sold-out">AGOTADO</div>
                            @elseif($isPopular)
                                <div class="product-badge popular">
                                    <i class="fas fa-fire"></i>
                                </div>
                            @endif

                            <!-- IMAGEN LIBRE SIN OVERLAYS -->
                            <div class="product-image">
                                <img src="{{ $producto->imagen_display_url ?? 'https://placehold.co/400x300/111827/FFFFFF?text=Producto' }}" 
                                     alt="{{ $producto->nombre ?? '' }}" 
                                     loading="lazy"
                                     onerror="this.src='https://placehold.co/400x300/111827/FFFFFF?text=Error'">
                            </div>
                            
                            <div class="product-content">
                                <h3 class="product-title">{{ $producto->nombre ?? 'Producto sin nombre' }}</h3>
                                
                                <p class="product-description desktop-only">{{ Str::limit($producto->descripcion_corta ?? 'Sin descripción disponible.', 80) }}</p>
                                
                                <div class="price-section">
                                    <span class="price-main">${{ number_format($producto->precio ?? 0, 0, ',', '.') }}</span>
                                    @if($hasDiscount)
                                        <span class="price-original">${{ number_format($producto->precio_original, 0, ',', '.') }}</span>
                                    @endif
                                </div>

                                <!-- BOTONES DEBAJO DEL PRECIO -->
                                @if(!$isOutOfStock)
                                    <div class="actions-inline">
                                        <button type="button" class="btn-card btn-cart tutorial-target" data-action="cart" data-tutorial="add-cart">
                                            <i class="fas fa-cart-plus"></i>
                                            <span class="btn-text">Añadir</span>
                                        </button>
                                        <button type="button" class="btn-card btn-buy tutorial-target" data-action="buy-direct" data-tutorial="buy-direct">
                                            <i class="fas fa-bolt"></i>
                                            <span class="btn-text">Comprar</span>
                                        </button>
                                    </div>
                                @else
                                    <div class="actions-inline">
                                        <button type="button" class="btn-card btn-view tutorial-target" data-action="quick-view" data-tutorial="quick-view">
                                            <i class="fas fa-eye"></i>
                                            <span class="btn-text">Ver</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($productos->hasPages())
            <div class="pagination-section">
                {{ $productos->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        @endif
    </section>
    @else
        <section class="empty-state">
            <div class="empty-content">
                <div class="empty-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3>No se encontraron productos</h3>
                <p>Intenta ajustar tus filtros de búsqueda</p>
            </div>
        </section>
    @endif
</div>

<!-- Tutorial Tooltip -->
<div class="tutorial-tooltip" id="tutorial-tooltip"></div>
@endsection

@push('styles')
<style>
    :root {
        --primary: #8b5cf6;
        --primary-hover: #7c3aed;
        --secondary: #10b981;
        --secondary-hover: #059669;
        --accent: #f59e0b;
        --accent-hover: #d97706;
        --danger: #ef4444;
        --bg-main: #0f172a;
        --bg-card: rgba(30, 41, 59, 0.95);
        --border: rgba(148, 163, 184, 0.2);
        --text-primary: #f8fafc;
        --text-secondary: #cbd5e1;
        --text-muted: #64748b;
        --radius: 12px;
        --radius-sm: 8px;
        --shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 25px 50px rgba(0, 0, 0, 0.25);
        --transition: all 0.3s ease;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        background: var(--bg-main);
        color: var(--text-primary);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
    }

    .tienda-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Hero Section */
    .hero-section {
        text-align: center;
        margin-bottom: 2rem;
    }

    .hero-title-tienda {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle-tienda {
        font-size: 1.1rem;
        color: var(--text-secondary);
        margin-top: 0.5rem;
    }

    .tutorial-trigger-btn {
        background: var(--accent);
        color: #111;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        margin-top: 1rem;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tutorial-trigger-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    /* Filtros */
    .filters-container {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
        backdrop-filter: blur(10px);
    }

    .filters-form {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }

    .search-box {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    .search-box input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border-radius: var(--radius-sm);
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
        border: 1px solid var(--border);
        transition: var(--transition);
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .filter-controls {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .filter-buttons {
        display: flex;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: var(--radius-sm);
        padding: 0.25rem;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        border: none;
        background: transparent;
        color: var(--text-secondary);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-btn:hover {
        color: var(--text-primary);
        background: rgba(139, 92, 246, 0.1);
    }

    .filter-btn.active {
        background: var(--primary);
        color: white;
    }

    .sort-select {
        padding: 0.5rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
        cursor: pointer;
        transition: var(--transition);
    }

    /* Grid de Productos */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .product-card-wrapper {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .product-card-wrapper.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Tarjetas de Producto - Dimensiones Fijas */
    .product-card-inner {
        height: 480px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        transition: var(--transition);
        position: relative;
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow);
        backdrop-filter: blur(10px);
    }

    .product-card:hover .product-card-inner {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }

    .product-card.out-of-stock .product-card-inner {
        opacity: 0.6;
        filter: grayscale(0.3);
    }

    /* Product Image - Libre sin overlays */
    .product-image {
        height: 220px;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
        cursor: pointer;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    /* Product Badges */
    .product-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        padding: 0.375rem 0.75rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        z-index: 5;
        backdrop-filter: blur(5px);
    }

    .product-badge.discount {
        background: var(--danger);
    }

    .product-badge.popular {
        background: var(--accent);
    }

    .product-badge.sold-out {
        background: var(--text-muted);
    }

    /* Product Content */
    .product-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        text-align: center;
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        line-height: 1.4;
        height: 3rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-description {
        font-size: 0.875rem;
        color: var(--text-secondary);
        line-height: 1.5;
        margin-bottom: 1rem;
        flex: 1;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .price-section {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .price-main {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--secondary);
    }

    .price-original {
        font-size: 1.1rem;
        color: var(--text-muted);
        text-decoration: line-through;
    }

    /* NUEVA SECCIÓN DE BOTONES DEBAJO DEL PRECIO */
    .actions-inline {
        display: flex;
        gap: 0.75rem;
        margin-top: auto;
    }

    .btn-card {
        flex: 1;
        padding: 0.75rem 1rem;
        border: none;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-cart {
        background: var(--primary);
        color: white;
    }

    .btn-cart:hover {
        background: var(--primary-hover);
    }

    .btn-buy {
        background: var(--secondary);
        color: white;
    }

    .btn-buy:hover {
        background: var(--secondary-hover);
    }

    .btn-view {
        background: var(--accent);
        color: white;
    }

    .btn-view:hover {
        background: var(--accent-hover);
    }

    /* Carrito Flotante */
    .cart-floating-btn {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        cursor: pointer;
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
        transition: var(--transition);
        z-index: 1000;
        border: none;
    }

    .cart-floating-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(139, 92, 246, 0.6);
    }

    .cart-counter {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--danger);
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--bg-main);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--primary);
    }

    .empty-state h3 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    /* Utility Classes */
    .desktop-only {
        display: block;
    }

    .mobile-only {
        display: none;
    }

    /* Tutorial */
    .tutorial-tooltip {
        position: fixed;
        background: var(--accent);
        color: #111;
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius-sm);
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: var(--shadow-lg);
        z-index: 10002;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, transform 0.3s;
        transform: translateY(10px);
        pointer-events: none;
    }

    .tutorial-tooltip.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .tutorial-highlight {
        position: relative;
        z-index: 10001;
        box-shadow: 0 0 0 4px var(--accent);
        border-radius: inherit;
        transition: box-shadow 0.3s;
    }

    /* SweetAlert2 Styles */
    .swal2-popup {
        background: var(--bg-card) !important;
        border: 1px solid var(--border) !important;
        border-radius: var(--radius) !important;
        backdrop-filter: blur(20px) !important;
        box-shadow: var(--shadow-lg) !important;
    }

    .swal2-title {
        color: var(--text-primary) !important;
        font-weight: 700 !important;
    }

    .swal2-html-container {
        color: var(--text-primary) !important;
    }

    .swal2-confirm, .swal2-deny, .swal2-cancel {
        padding: 0.75rem 1.5rem !important;
        font-weight: 600 !important;
        border-radius: var(--radius-sm) !important;
    }

    .swal2-confirm {
        background: var(--primary) !important;
    }

    .swal2-deny {
        background: var(--secondary) !important;
    }

    .swal2-cancel {
        background: rgba(255, 255, 255, 0.1) !important;
    }

    /* Carrito Modal Styles */
    .cart-modal-item {
        display: grid;
        grid-template-columns: auto 1fr auto auto;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border);
    }

    .cart-item-image {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-sm);
        object-fit: cover;
    }

    .cart-item-info {
        text-align: left;
    }

    .cart-item-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .cart-item-price {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .cart-item-quantity {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .quantity-btn {
        width: 28px;
        height: 28px;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-primary);
        cursor: pointer;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }

    .quantity-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .cart-item-remove {
        background: transparent;
        border: none;
        color: var(--danger);
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 50%;
        transition: var(--transition);
    }

    .cart-item-remove:hover {
        background: rgba(239, 68, 68, 0.1);
    }

    .cart-total-section {
        margin-top: 1.5rem;
        border-top: 1px solid var(--border);
        padding-top: 1rem;
        text-align: right;
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--secondary);
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .tienda-container {
            padding: 1rem;
        }

        .hero-title-tienda {
            font-size: 1.75rem;
        }

        .tutorial-trigger-btn {
            font-size: 0.8rem;
            padding: 0.625rem 1.25rem;
        }

        .filters-container {
            padding: 1rem;
        }

        .filters-form {
            flex-direction: column;
            gap: 1rem;
        }

        .search-box {
            width: 100%;
        }

        .search-box input {
            font-size: 16px;
        }

        .filter-controls {
            width: 100%;
            flex-direction: column;
            gap: 1rem;
        }

        .filter-buttons {
            width: 100%;
        }

        .filter-btn {
            padding: 0.625rem;
            font-size: 0.8rem;
        }

        .desktop-text {
            display: none;
        }

        .sort-select {
            width: 100%;
            font-size: 16px;
        }

        /* GRID 2x2 FIJO */
        .products-grid {
            grid-template-columns: 1fr 1fr !important;
            gap: 0.75rem;
        }

        .product-card-inner {
            height: 320px;
        }

        .product-image {
            height: 140px;
        }

        .product-content {
            padding: 0.75rem;
        }

        .product-title {
            font-size: 0.875rem;
            text-align: left;
            height: 2.4rem;
        }

        .product-description {
            display: none;
        }

        .price-section {
            justify-content: flex-start;
            margin-bottom: 1rem;
        }

        .price-main {
            font-size: 1rem;
        }

        .actions-inline {
            flex-direction: column;
            gap: 0.5rem;
        }

        .btn-text {
            display: none;
        }

        .desktop-only {
            display: none;
        }

        .mobile-only {
            display: block;
        }

        .cart-floating-btn {
            width: 50px;
            height: 50px;
            font-size: 1.1rem;
            bottom: 1rem;
            right: 1rem;
        }

        .cart-counter {
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
        }
    }

    @media (max-width: 400px) {
        .products-grid {
            gap: 0.5rem;
        }

        .product-card-inner {
            height: 300px;
        }

        .product-image {
            height: 120px;
        }

        .product-title {
            font-size: 0.8rem;
        }

        .price-main {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Función de utilidad
    const $ = (selector) => document.querySelector(selector);
    const $$ = (selector) => document.querySelectorAll(selector);

    // Función debounce
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    class ShopManager {
        constructor() {
            this.cart = this.loadCart();
            this.isMobile = window.innerWidth <= 768;
            
            // Tutorial
            this.tutorialShown = localStorage.getItem('tutorial_shown') === 'true';
            this.currentTutorialStep = 0;
           

            this.init();
        }

        init() {
            this.createCartButton();
            this.updateCartCounter();
            this.setupEventListeners();
            
            if (!this.tutorialShown) {
                setTimeout(() => this.startInteractiveTutorial(), 1500);
            }
        }
        
        setupEventListeners() {
            // Tutorial button
            $('#start-tutorial-btn')?.addEventListener('click', () => {
                localStorage.removeItem('tutorial_shown');
                this.tutorialShown = false;
                this.startInteractiveTutorial();
            });

            // Product actions
            document.addEventListener('click', (e) => {
                const actionButton = e.target.closest('[data-action]');
                const imageClick = e.target.closest('.product-image');

                if (actionButton) {
                    const productCard = actionButton.closest('.product-card');
                    if (!productCard) return;

                    const product = this.extractProductData(productCard);
                    const action = actionButton.dataset.action;

                    e.preventDefault();
                    e.stopPropagation();

                    this.handleAction(action, product);
                } else if (imageClick) {
                    // Clic en imagen para vista rápida
                    const productCard = imageClick.closest('.product-card');
                    if (!productCard) return;

                    const product = this.extractProductData(productCard);
                    this.showQuickView(product);
                }
            });
        }

        extractProductData(card) {
            return {
                id: card.dataset.id,
                name: card.dataset.name,
                price: parseFloat(card.dataset.price),
                stock: parseInt(card.dataset.stock),
                image: card.dataset.image,
                description: card.dataset.description,
                fullDescription: card.dataset.fullDescription
            };
        }

        handleAction(action, product) {
            switch (action) {
                case 'buy-direct':
                    this.processDirect(product);
                    break;
                case 'cart':
                    this.addItem(product);
                    break;
                case 'quick-view':
                    this.showQuickView(product);
                    break;
            }
        }

        // Tutorial methods
        startInteractiveTutorial() {
            this.currentTutorialStep = 0;
            this.showTutorialStep();
        }

        showTutorialStep() {
            const step = this.tutorialSteps[this.currentTutorialStep];
            if (!step) {
                this.endTutorial();
                return;
            }

            const targetElement = $(step.target);
            if (!targetElement) {
                this.nextTutorialStep();
                return;
            }
            
            this.highlightElement(targetElement);
            this.showTooltip(targetElement, step.text);
            
            const nextStepHandler = (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.nextTutorialStep();
                targetElement.removeEventListener('click', nextStepHandler, true);
            };
            targetElement.addEventListener('click', nextStepHandler, true);
        }

        nextTutorialStep() {
            this.currentTutorialStep++;
            this.showTutorialStep();
        }

        highlightElement(element) {
            $$('.tutorial-highlight').forEach(el => el.classList.remove('tutorial-highlight'));
            element.classList.add('tutorial-highlight');
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        showTooltip(target, text) {
            const tooltip = $('#tutorial-tooltip');
            tooltip.textContent = text;
            tooltip.classList.add('active');

            const rect = target.getBoundingClientRect();
            const tooltipRect = tooltip.getBoundingClientRect();
            
            let top = rect.bottom + 10;
            let left = rect.left + rect.width / 2 - tooltipRect.width / 2;

            if (top + tooltipRect.height > window.innerHeight) {
                top = rect.top - tooltipRect.height - 10;
            }
            if (left < 0) left = 10;
            if (left + tooltipRect.width > window.innerWidth) {
                left = window.innerWidth - tooltipRect.width - 10;
            }

            tooltip.style.top = `${top}px`;
            tooltip.style.left = `${left}px`;
        }
        
        endTutorial() {
            $$('.tutorial-highlight').forEach(el => el.classList.remove('tutorial-highlight'));
            $('#tutorial-tooltip')?.classList.remove('active');
            localStorage.setItem('tutorial_shown', 'true');
            this.tutorialShown = true;
            this.showToast('🎉 ¡Tutorial completado!', 'success');
        }

        // Cart methods
        loadCart() {
            return JSON.parse(localStorage.getItem('shop_cart')) || [];
        }

        saveCart() {
            localStorage.setItem('shop_cart', JSON.stringify(this.cart));
            this.updateCartCounter();
        }

        createCartButton() {
            if ($('#cart-floating-btn')) return;
            const btnHtml = `<button class="cart-floating-btn" id="cart-floating-btn">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-counter" id="cart-counter">0</span>
            </button>`;
            document.body.insertAdjacentHTML('beforeend', btnHtml);
            $('#cart-floating-btn').addEventListener('click', () => this.showCartModal());
        }

        updateCartCounter() {
            const counter = $('#cart-counter');
            if (!counter) return;
            const count = this.cart.reduce((sum, item) => sum + item.quantity, 0);
            counter.textContent = count;
            counter.style.display = count > 0 ? 'flex' : 'none';
        }

        addItem(product, quantity = 1) {
            if (parseInt(product.stock) <= 0) {
                this.showToast('❌ Producto agotado', 'error');
                return;
            }
            
            const existingItem = this.cart.find(item => item.id === product.id);
            if (existingItem) {
                const newQty = existingItem.quantity + quantity;
                if (newQty > product.stock) {
                    this.showToast(`⚠️ Solo quedan ${product.stock} unidades`, 'warning');
                    return;
                }
                existingItem.quantity = newQty;
            } else {
                this.cart.push({ ...product, quantity });
            }
            
            this.saveCart();
            this.showToast(`✅ ${product.name} añadido`, 'success');
        }
        
        updateItemQuantity(id, change) {
            const item = this.cart.find(i => i.id === id);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    this.cart = this.cart.filter(i => i.id !== id);
                }
                this.saveCart();
                this.showCartModal();
            }
        }
        
        removeItem(id) {
            this.cart = this.cart.filter(i => i.id !== id);
            this.saveCart();
            this.showCartModal();
        }
        
        showToast(message, type = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({ icon: type, title: message });
        }

        showCartModal() {
            const formatPrice = (p) => `$${new Intl.NumberFormat('es-CL').format(p)}`;
            
            if (this.cart.length === 0) {
                Swal.fire({
                    title: '🛒 Carrito Vacío',
                    text: 'Añade productos para verlos aquí.',
                    icon: 'info',
                    confirmButtonText: 'Seguir Comprando'
                });
                return;
            }
            
            const total = this.cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            
            const itemsHtml = this.cart.map(item => `
                <div class="cart-modal-item">
                    <img src="${item.image}" class="cart-item-image" alt="${item.name}">
                    <div class="cart-item-info">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">${formatPrice(item.price)}</div>
                    </div>
                    <div class="cart-item-quantity">
                        <button class="quantity-btn" data-id="${item.id}" data-change="-1">-</button>
                        <span>${item.quantity}</span>
                        <button class="quantity-btn" data-id="${item.id}" data-change="1">+</button>
                    </div>
                    <button class="cart-item-remove" data-id="${item.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `).join('');

            Swal.fire({
                title: '🛒 Mi Carrito',
                html: `
                    <div style="text-align: left;">${itemsHtml}</div>
                    <div class="cart-total-section">Total: ${formatPrice(total)}</div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Proceder al Pago',
                cancelButtonText: 'Seguir Comprando',
                width: this.isMobile ? '95%' : '600px',
                didOpen: () => {
                    const popup = Swal.getPopup();
                    popup.addEventListener('click', (e) => {
                        const changeBtn = e.target.closest('.quantity-btn');
                        const removeBtn = e.target.closest('.cart-item-remove');
                        
                        if (changeBtn) {
                            this.updateItemQuantity(changeBtn.dataset.id, parseInt(changeBtn.dataset.change));
                        } else if (removeBtn) {
                            this.removeItem(removeBtn.dataset.id);
                        }
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.processOrder(this.cart);
                }
            });
        }
        
        async processDirect(product) {
            const formatPrice = (value) => new Intl.NumberFormat('es-CL', {
                style: 'currency',
                currency: 'CLP',
                minimumFractionDigits: 0
            }).format(value);

            const { isConfirmed } = await Swal.fire({
                title: '⚡ Compra Directa',
                html: `
                    <div style="text-align: center; color: var(--text-primary);">
                        <img src="${product.image}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 12px; margin-bottom: 1rem; border: 2px solid var(--primary);">
                        <h3 style="margin-bottom: 1rem;">${product.name}</h3>
                        <div style="color: var(--secondary); font-size: 1.5rem; font-weight: 700;">${formatPrice(product.price)}</div>
                    </div>
                `,
                width: this.isMobile ? '95%' : '500px',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-bolt"></i> Comprar Ahora',
                cancelButtonText: '<i class="fas fa-times"></i> Cancelar'
            });

            if (isConfirmed) {
                this.processOrder([{ ...product, quantity: 1 }]);
            }
        }
        
        async processOrder(orderItems) {
            Swal.fire({
                title: '⚡ Procesando compra...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });
            
            console.log("Procesando orden con:", orderItems);
            
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: '🎉 ¡Compra Exitosa!',
                    text: 'Tus productos han sido comprados exitosamente.',
                    confirmButtonText: 'Continuar'
                });
                
                // Limpiar carrito solo de los items comprados
                orderItems.forEach(orderedItem => {
                    this.cart = this.cart.filter(cartItem => cartItem.id !== orderedItem.id);
                });
                this.saveCart();
            }, 1500);
        }

        showQuickView(product) {
            const formatPrice = (value) => new Intl.NumberFormat('es-CL', {
                style: 'currency',
                currency: 'CLP',
                minimumFractionDigits: 0
            }).format(value);

            Swal.fire({
                title: '👁️ Vista Rápida',
                html: `
                    <div style="display: flex; flex-direction: column; gap: 1rem; text-align: left; color: var(--text-primary);">
                        <img src="${product.image}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px; border: 2px solid var(--primary);">
                        <h3>${product.name}</h3>
                        <p>${product.fullDescription}</p>
                        <div style="background: var(--secondary); color: white; padding: 1rem; border-radius: 8px; text-align: center; font-size: 1.5rem; font-weight: 700;">
                            ${formatPrice(product.price)}
                        </div>
                    </div>
                `,
                width: this.isMobile ? '95%' : '700px',
                showConfirmButton: !product.stock <= 0,
                showDenyButton: !product.stock <= 0,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-cart-plus"></i> Añadir al carrito',
                denyButtonText: '<i class="fas fa-bolt"></i> Comprar ahora',
                cancelButtonText: '<i class="fas fa-times"></i> Cerrar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.addItem(product);
                } else if (result.isDenied) {
                    this.processDirect(product);
                }
            });
        }
    }

    class FilterManager {
        constructor() {
            this.form = $('#filters-form');
            if (!this.form) return;
            
            $('#search-input')?.addEventListener('input', debounce(() => this.form.submit(), 500));
            $('#sort-select')?.addEventListener('change', () => this.form.submit());
            $$('.filter-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const filter = btn.dataset.filter;
                    
                    $$('.filter-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    
                    $('#filter-input').value = filter;
                    this.form.submit();
                });
            });
        }
    }

    // Initialize everything
    window.shopManager = new ShopManager();
    new FilterManager();
    
    // Intersection Observer for animations
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        $$('.product-card-wrapper').forEach((el, index) => {
            el.style.transitionDelay = `${index * 50}ms`;
            observer.observe(el);
        });
    } else {
        $$('.product-card-wrapper').forEach(el => el.classList.add('is-visible'));
    }

    // Image error handling
    $$('img[loading="lazy"]').forEach(img => {
        img.addEventListener('error', function() {
            this.src = 'https://placehold.co/400x300/111827/FFFFFF?text=Error';
        });
    });

    console.log('🎉 Tienda inicializada completamente');
});
</script>
@endpush
