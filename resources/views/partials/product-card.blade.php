{{-- resources/views/partials/product-card.blade.php --}}
{{-- Versión ULTRA MEJORADA con manejo de errores --}}

<div class="product-card fade-in" data-product-id="{{ $producto->id ?? $id }}">
    
    {{-- Badge Popular --}}
    @if(($producto->es_popular ?? $popular ?? false))
        <div class="popular-badge">
            <i class="fas fa-fire"></i> POPULAR
        </div>
    @endif

    {{-- Imagen del Producto --}}
    <div class="product-img-wrapper">
        <img class="product-img"
             src="{{ $producto->imagen_display_url ?? $imageUrl ?? asset('images/placeholder-product.png') }}"
             alt="{{ $producto->nombre ?? $title ?? 'Producto' }}"
             loading="lazy"
             onerror="this.onerror=null; this.src='{{ asset('images/placeholder-product.png') }}';">
        
        {{-- Overlay con acciones rápidas --}}
        <div class="product-overlay">
            <button type="button" class="quick-view-btn" data-product-id="{{ $producto->id ?? $id }}">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>

    {{-- Contenido del Producto --}}
    <div class="product-content">
        <h3 class="product-title">{{ $producto->nombre ?? $title ?? 'Sin título' }}</h3>
        <p class="product-desc">{{ Str::limit($producto->descripcion_corta ?? $description ?? 'Sin descripción', 80) }}</p>

        {{-- Información de Precio y Stock --}}
        <div class="product-info">
            <div class="product-price">
                ${{ number_format($producto->precio ?? $price ?? 0, 0, ',', '.') }}
            </div>
            
            @php
                $stockDisponible = $producto->stock ?? $stock ?? 0;
            @endphp
            
            <div class="product-stock {{ $stockDisponible > 0 ? 'in-stock' : 'out-of-stock' }}">
                @if($stockDisponible > 0)
                    <i class="fas fa-check-circle"></i>
                    <span>{{ $stockDisponible }} disponibles</span>
                @else
                    <i class="fas fa-times-circle"></i>
                    <span>Agotado</span>
                @endif
            </div>
        </div>

        {{-- Botones de Acción --}}
        <div class="product-card-buttons">
            @if($stockDisponible > 0)
                {{-- Botón Comprar Ahora --}}
                <button type="button" 
                        class="btn-primary product-buy-btn" 
                        data-product-id="{{ $producto->id ?? $id }}"
                        data-product-name="{{ $producto->nombre ?? $title }}"
                        data-product-price="{{ $producto->precio ?? $price }}"
                        data-product-stock="{{ $stockDisponible }}"
                        data-product-desc="{{ $producto->descripcion_corta ?? $description }}">
                    <i class="fas fa-shopping-bag"></i>
                    Comprar Ahora
                </button>
                
                {{-- Botón Agregar al Carrito --}}
                <button type="button" 
                        class="btn-outline add-to-cart-btn" 
                        data-product-id="{{ $producto->id ?? $id }}">
                    <i class="fas fa-cart-plus"></i>
                    Al Carrito
                </button>
            @else
                {{-- Botón Agotado --}}
                <button type="button" class="btn-disabled" disabled>
                    <i class="fas fa-times-circle"></i>
                    Producto Agotado
                </button>
                
                {{-- Botón Notificar cuando esté disponible --}}
                <button type="button" class="btn-outline notify-btn" data-product-id="{{ $producto->id ?? $id }}">
                    <i class="fas fa-bell"></i>
                    Notificarme
                </button>
            @endif
        </div>
    </div>
</div>
