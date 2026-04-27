{{-- resources/views/partials/sidebar.blade.php --}}
{{-- SIDEBAR MODERNO CON EFECTOS VISUALES --}}

<style>
    :root {
        --primary: #6366f1;
        --primary-light: #818cf8;
        --primary-dark: #4f46e5;
        --secondary: #ec4899;
        --accent: #10b981;
        --bg-main: #0f172a;
        --bg-card: #1e293b;
        --bg-sidebar: #1a1f36;
        --text-white: #f8fafc;
        --text-muted: #94a3b8;
        --border-color: rgba(99, 102, 241, 0.2);
        --shadow-glow: 0 0 20px rgba(99, 102, 241, 0.3);
        --whatsapp: #25d366;
    }

    /* === CONTENEDOR PRINCIPAL === */
    .sidebar {
        width: 80px;
        min-width: 80px;
        max-width: 80px;
        height: 100vh;
        background: linear-gradient(180deg, var(--bg-sidebar) 0%, var(--bg-main) 100%);
        border-right: 2px solid var(--border-color);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1000;
        backdrop-filter: blur(20px);
        box-shadow: 
            4px 0 24px rgba(0, 0, 0, 0.3),
            inset -1px 0 0 rgba(99, 102, 241, 0.1);
    }

    /* === LOGO ANIMADO === */
    .sidebar-logo {
        margin: 1.5rem 0;
        font-size: 2rem;
        font-weight: 900;
        background: linear-gradient(135deg, #fff 0%, var(--primary-light) 50%, var(--primary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-decoration: none;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        filter: drop-shadow(0 0 8px var(--primary));
        animation: logoGlow 4s ease-in-out infinite;
    }

    .sidebar-logo::before {
        content: '';
        position: absolute;
        top: -10px;
        left: -10px;
        right: -10px;
        bottom: -10px;
        background: radial-gradient(circle, var(--primary) 0%, transparent 70%);
        opacity: 0;
        border-radius: 50%;
        transition: opacity 0.3s ease;
        z-index: -1;
    }

    .sidebar-logo:hover::before {
        opacity: 0.2;
        animation: pulse 1s ease-in-out infinite;
    }

    .sidebar-logo:hover {
        transform: scale(1.1) rotate(5deg);
        filter: drop-shadow(0 0 15px var(--primary-light));
    }

    /* === BUSCADOR MEJORADO === */
    .sidebar-search {
        width: 90%;
        position: relative;
        margin-bottom: 1rem;
    }

    .sidebar-search input {
        width: 100%;
        padding: 0.75rem 0.75rem 0.75rem 2.5rem;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-white);
        background: rgba(99, 102, 241, 0.1);
        outline: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .sidebar-search input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        background: rgba(99, 102, 241, 0.15);
    }

    .sidebar-search input::placeholder {
        color: var(--text-muted);
    }

    .sidebar-search i {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 0.9rem;
        transition: color 0.3s ease;
    }

    .sidebar-search input:focus + i {
        color: var(--primary-light);
    }

    .search-results {
        display: none;
        position: absolute;
        left: 0;
        right: 0;
        top: 105%;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 0 0 12px 12px;
        max-height: 200px;
        overflow-y: auto;
        font-size: 0.8rem;
        z-index: 1001;
        backdrop-filter: blur(20px);
        box-shadow: var(--shadow-glow);
    }

    .search-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        color: var(--text-white);
        border-bottom: 1px solid var(--border-color);
        background: none;
        border: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-item:last-child {
        border-bottom: none;
    }

    .search-item:hover {
        background: rgba(99, 102, 241, 0.2);
        color: var(--primary-light);
        transform: translateX(5px);
    }

    .search-img {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid var(--border-color);
    }

    /* === NAVEGACIÓN MEJORADA === */
    .nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
        width: 100%;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        padding: 0 0.5rem;
    }

    .nav-item {
        position: relative;
    }

    .nav-link {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1rem;
        color: var(--text-muted);
        font-size: 1.25rem;
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid transparent;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .nav-link:hover::before {
        left: 100%;
    }

    .nav-link:hover {
        color: var(--primary-light);
        background: rgba(99, 102, 241, 0.15);
        border-color: var(--border-color);
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
    }

    .nav-link.active {
        color: var(--primary-light);
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(99, 102, 241, 0.1));
        border-color: var(--primary);
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        transform: scale(1.05);
    }

    .nav-link.active::after {
        content: '';
        position: absolute;
        left: -2px;
        top: 20%;
        bottom: 20%;
        width: 3px;
        background: var(--primary);
        border-radius: 0 2px 2px 0;
        box-shadow: 0 0 8px var(--primary);
    }

    /* === ICONOS CON ANIMACIONES === */
    .nav-link i {
        transition: all 0.3s ease;
    }

    .nav-link:hover i {
        transform: scale(1.2);
        filter: drop-shadow(0 0 8px currentColor);
    }

    .nav-link.active i {
        animation: iconPulse 2s ease-in-out infinite;
    }

    /* === BOTÓN WHATSAPP MEJORADO === */
    .wa-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 50px;
        height: 50px;
        border-radius: 15px;
        margin: 1rem 0;
        background: linear-gradient(135deg, var(--whatsapp), #128c7e);
        color: white;
        font-size: 1.5rem;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        position: relative;
        overflow: hidden;
    }

    .wa-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .wa-btn:hover::before {
        left: 100%;
    }

    .wa-btn:hover {
        transform: scale(1.15) rotate(-5deg);
        box-shadow: 0 8px 25px rgba(37, 211, 102, 0.5);
        background: linear-gradient(135deg, #128c7e, #075e54);
    }

    .wa-btn i {
        animation: whatsappBounce 3s ease-in-out infinite;
    }

    /* === FOOTER MEJORADO === */
    .sidebar-footer {
        width: 100%;
        padding: 1rem 0.5rem 1.5rem;
        text-align: center;
    }

    .saldo {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1));
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 0.75rem;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
    }

    .saldo-label {
        font-size: 0.7rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .saldo-amount {
        font-size: 1rem;
        font-weight: 900;
        color: var(--primary-light);
        text-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
        animation: saldoGlow 3s ease-in-out infinite;
    }

    .logout-btn {
        width: 50px;
        height: 50px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--danger), #dc2626);
        color: white;
        font-size: 1.25rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        position: relative;
        overflow: hidden;
    }

    .logout-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .logout-btn:hover::before {
        left: 100%;
    }

    .logout-btn:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.5);
        background: linear-gradient(135deg, #dc2626, #b91c1c);
    }

    /* === ANIMACIONES === */
    @keyframes logoGlow {
        0%, 100% { 
            filter: drop-shadow(0 0 8px var(--primary)); 
        }
        50% { 
            filter: drop-shadow(0 0 15px var(--primary-light)); 
        }
    }

    @keyframes pulse {
        0%, 100% { 
            transform: scale(1); 
            opacity: 0.2; 
        }
        50% { 
            transform: scale(1.1); 
            opacity: 0.4; 
        }
    }

    @keyframes iconPulse {
        0%, 100% { 
            transform: scale(1.2); 
        }
        50% { 
            transform: scale(1.3); 
        }
    }

    @keyframes whatsappBounce {
        0%, 100% { 
            transform: translateY(0); 
        }
        50% { 
            transform: translateY(-3px); 
        }
    }

    @keyframes saldoGlow {
        0%, 100% { 
            text-shadow: 0 0 10px rgba(99, 102, 241, 0.5); 
        }
        50% { 
            text-shadow: 0 0 20px rgba(99, 102, 241, 0.8); 
        }
    }

    /* === RESPONSIVE === */
    @media (max-width: 768px) {
        .sidebar {
            width: 70px;
            min-width: 70px;
            max-width: 70px;
        }
        
        .nav-link {
            padding: 0.875rem;
            font-size: 1.1rem;
        }
        
        .wa-btn, .logout-btn {
            width: 45px;
            height: 45px;
            font-size: 1.1rem;
        }
        
        .sidebar-logo {
            font-size: 1.75rem;
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

    /* === SCROLLBAR PERSONALIZADO === */
    .search-results::-webkit-scrollbar {
        width: 4px;
    }

    .search-results::-webkit-scrollbar-track {
        background: var(--bg-main);
    }

    .search-results::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 2px;
    }

    .search-results::-webkit-scrollbar-thumb:hover {
        background: var(--primary-light);
    }
</style>

<aside class="sidebar">
    {{-- Logo Animado --}}
    <a href="{{ route('dashboard') }}" class="sidebar-logo" title="NPStreaming - Inicio">
        <i class="fas fa-play-circle"></i>
    </a>

    {{-- Buscador Mejorado --}}
    <div class="sidebar-search">
        <form action="/buscar" method="GET" autocomplete="off">
            <input id="sidebar-search-input" name="query" type="text" placeholder="Buscar...">
            <i class="fas fa-search"></i>
        </form>
        <div id="search-results" class="search-results"></div>
    </div>

    {{-- Navegación Optimizada (SIN CARRITO) --}}
    <ul class="nav-list">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" 
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
               title="Dashboard">
                <i class="fas fa-home"></i>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('tienda.index') }}" 
               class="nav-link {{ request()->routeIs('tienda*') ? 'active' : '' }}" 
               title="Tienda">
                <i class="fas fa-store"></i>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('cuenta.index') }}" 
               class="nav-link {{ request()->routeIs('cuenta.index') ? 'active' : '' }}" 
               title="Mi Cuenta">
                <i class="fas fa-user-circle"></i>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('soporte.index') }}" 
               class="nav-link {{ request()->routeIs('soporte.index') ? 'active' : '' }}" 
               title="Soporte">
                <i class="fas fa-headset"></i>
            </a>
        </li>
        
        @can('view-admin-dashboard')
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" 
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                   title="Panel Admin">
                    <i class="fas fa-tachometer-alt"></i>
                </a>
            </li>
        @endcan
    </ul>

    {{-- WhatsApp Mejorado --}}
    <a href="https://wa.me/573136968036?text={{ urlencode('Hola, necesito ayuda con NPStreaming.') }}"
       target="_blank" 
       rel="noopener" 
       class="wa-btn" 
       title="Contactar por WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    {{-- Footer con Saldo y Logout --}}
    <div class="sidebar-footer">
        <div class="saldo">
            <div class="saldo-label">Saldo</div>
            <div class="saldo-amount">
                ${{ number_format(Auth::check() ? Auth::user()->saldo : 0, 0, ',', '.') }}
            </div>
        </div>
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn" title="Cerrar Sesión">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </form>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // === BUSCADOR INTELIGENTE ===
    const searchInput = document.getElementById('sidebar-search-input');
    const searchResults = document.getElementById('search-results');
    let searchTimer;

    if (searchInput && searchResults) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                searchResults.innerHTML = '';
                return;
            }

            searchTimer = setTimeout(async () => {
                try {
                    const response = await fetch(`/productos/buscar-ajax?query=${encodeURIComponent(query)}`);
                    const products = await response.json();
                    
                    searchResults.innerHTML = '';
                    
                    if (products.length > 0) {
                        products.forEach(product => {
                            const item = document.createElement('button');
                            item.className = 'search-item';
                            item.innerHTML = `
                                <img src="${product.imagen_url || 'https://placehold.co/32x32/6366f1/FFFFFF?text=' + product.nombre[0]}" 
                                     class="search-img" 
                                     alt="${product.nombre}">
                                <div class="search-result-info">
                                    <div class="search-result-name" style="font-weight: 600; color: var(--text-white); font-size: 0.8rem;">${product.nombre}</div>
                                    <div class="search-result-price" style="font-size: 0.7rem; color: var(--primary-light);">$${Number(product.precio).toLocaleString('es-CO')}</div>
                                </div>
                            `;
                            
                            item.addEventListener('click', () => {
                                // Redirigir a la tienda o abrir modal de compra
                                if (window.shopManager && window.shopManager.processDirect) {
                                    const productData = {
                                        id: product.id,
                                        name: product.nombre,
                                        price: parseFloat(product.precio),
                                        stock: parseInt(product.stock_disponible || 1),
                                        image: product.imagen_url || 'https://placehold.co/300x200/6366f1/FFFFFF?text=' + product.nombre,
                                        description: product.descripcion_corta || 'Cuenta premium de alta calidad.',
                                        fullDescription: product.descripcion || 'Descripción completa no disponible.'
                                    };
                                    window.shopManager.processDirect(productData);
                                } else {
                                    // Fallback: redirigir a la tienda
                                    window.location.href = "{{ route('tienda.index') }}?search=" + encodeURIComponent(product.nombre);
                                }
                                
                                searchResults.style.display = 'none';
                                searchInput.value = '';
                            });
                            
                            searchResults.appendChild(item);
                        });
                    } else {
                        searchResults.innerHTML = '<div class="search-item" style="color: var(--text-muted); cursor: default;">Sin resultados</div>';
                    }
                    
                    searchResults.style.display = 'block';
                } catch (error) {
                    console.error('Error en búsqueda:', error);
                    searchResults.innerHTML = '<div class="search-item" style="color: var(--danger); cursor: default;">Error en búsqueda</div>';
                    searchResults.style.display = 'block';
                }
            }, 300);
        });

        // Cerrar resultados al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    }

    // === EFECTOS DE NAVEGACIÓN ===
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(-2px) scale(1.05)';
            }
        });
        
        link.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(0) scale(1)';
            }
        });
    });

    // === ANIMACIÓN DEL LOGO ===
    const logo = document.querySelector('.sidebar-logo');
    if (logo) {
        logo.addEventListener('click', function(e) {
            this.style.transform = 'scale(0.95) rotate(10deg)';
            setTimeout(() => {
                this.style.transform = 'scale(1.1) rotate(5deg)';
            }, 100);
            setTimeout(() => {
                this.style.transform = 'scale(1) rotate(0deg)';
            }, 200);
        });
    }

    console.log('🎨 Sidebar NPStreaming cargado exitosamente!');
});
</script>
