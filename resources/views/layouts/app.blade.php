{{-- EDICIÓN FINAL v58: Solución de Overflow, Sidebar Polish y Pulido Final --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pandora Streaming') }} - Cuentas Premium</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@900&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    @stack('styles')

    <style>
        :root {
            --bg-dark-space: #02010a;
            --bg-glass: rgba(10, 5, 25, 0.5);
            --primary-neon-purple: #9333ea;
            --accent-glow-cyan: #08d9d6;
            --accent-hot-pink: #ff2e63;
            --text-white: #f0f2f5;
            --text-muted: #a0aec0;
            --border-glow: rgba(147, 51, 234, 0.3);
            --shadow-glow-purple: 0 0 25px rgba(147, 51, 234, 0.6);
        }

        /* --- Preloader Styles --- */
        #preloader {
            position: fixed; top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: var(--bg-dark-space);
            z-index: 10000;
            display: flex; justify-content: center; align-items: center;
            transition: opacity 0.75s ease, visibility 0.75s ease;
        }
        #preloader.loaded { opacity: 0; visibility: hidden; }
        .spinner {
            width: 60px; height: 60px;
            border: 4px solid var(--border-glow);
            border-top-color: var(--accent-glow-cyan);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* --- Global Animations --- */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes text-glow {
            0%, 100% { text-shadow: 0 0 10px var(--primary-neon-purple), 0 0 4px #fff; }
            50% { text-shadow: 0 0 20px var(--primary-neon-purple), 0 0 8px #fff; }
        }
        @keyframes border-flow {
            0% { border-image-source: linear-gradient(0deg, var(--primary-neon-purple), var(--accent-glow-cyan)); }
            25% { border-image-source: linear-gradient(90deg, var(--primary-neon-purple), var(--accent-glow-cyan)); }
            50% { border-image-source: linear-gradient(180deg, var(--primary-neon-purple), var(--accent-glow-cyan)); }
            75% { border-image-source: linear-gradient(270deg, var(--primary-neon-purple), var(--accent-glow-cyan)); }
            100% { border-image-source: linear-gradient(360deg, var(--primary-neon-purple), var(--accent-glow-cyan)); }
        }
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(147, 51, 234, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(147, 51, 234, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(147, 51, 234, 0); }
        }

        /* --- Base & Layout --- */
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-white);
            background-color: var(--bg-dark-space) !important; 
            min-height: 100vh; /* Usar min-height para permitir crecimiento */
            cursor: none;
            /* ✅ CORRECCIÓN: Eliminar overflow: hidden de body para evitar que el contenido que excede 100vh se corte. */
            /* overflow: hidden; */ 
        }
        @media (min-width: 769px) {
             /* En escritorio, el layout principal gestiona el 100vh */
            body { height: 100vh; overflow: hidden; } 
        }

        /* Ruido (Grain) */
        body::after {
            content: '';
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAMAAAAp4XiGAAAAA1BMVEX///+nxBvIAAAAIElEQVR42mP4z8BQz0ADEwM3g4gtjC4s4uLh4wMA+6sCEQknaPEAAAAASUVORK5CYII=');
            opacity: 0.05;
            z-index: 9998;
            pointer-events: none;
            animation: grain 8s steps(10) infinite;
        }
        @keyframes grain {
            0%, 100% { transform: translate(0, 0); } 10% { transform: translate(-5%, -10%); } 20% { transform: translate(-15%, 5%); } 30% { transform: translate(7%, -25%); } 40% { transform: translate(-5%, 25%); } 50% { transform: translate(-15%, 10%); } 60% { transform: translate(15%, 0%); } 70% { transform: translate(0%, 15%); } 80% { transform: translate(3%, 35%); } 90% { transform: translate(-10%, 10%); }
        }

        a:focus-visible, button:focus-visible, [tabindex]:not([tabindex="-1"]):focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.4), 0 0 15px rgba(147, 51, 234, 0.6);
            border-radius: 0.25rem;
        }

        #tsparticles {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0;
        }

        .main-container {
            position: relative; z-index: 1;
            height: 100vh; display: flex;
            background-color: transparent;
        }
        /* ✅ CORRECCIÓN: Ocultar cursor en móviles/tabletas */
        .cursor-aura, .cursor-dot { 
            position: fixed; border-radius: 50%; pointer-events: none; transform: translate(-50%, -50%); z-index: 9999; 
            transition: all 0.1s ease-out; 
        }
        .cursor-aura { width: 40px; height: 40px; background: radial-gradient(circle, rgba(147, 51, 234, 0.2) 0%, transparent 70%); border: 1px solid var(--border-glow); }
        .cursor-dot { width: 4px; height: 4px; background-color: var(--primary-neon-purple); box-shadow: var(--shadow-glow-purple); }
        
        /* --- Sidebar Styles --- */
        .sidebar {
            background: var(--bg-glass) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-right: 2px solid;
            border-image-slice: 1;
            animation: border-flow 5s linear infinite; 
            width: 280px; display: flex; flex-direction: column;
            padding: 1.5rem; flex-shrink: 0;
            perspective: 1000px;
        }
        
        .logo-box { padding: 1rem; text-decoration: none; text-align: center; }
        .logo-box svg { width: 80%; height: auto; max-width: 150px; filter: drop-shadow(0 0 10px var(--primary-neon-purple)); transition: all 0.3s ease; }
        .logo-box:hover svg { filter: drop-shadow(0 0 20px var(--primary-neon-purple)); transform: scale(1.05); }
        .logo-box .logo-text { font-family: 'Orbitron', sans-serif; font-size: 1.5rem; font-weight: 800; text-shadow: 0 0 12px var(--primary-neon-purple); }
        .logo-box .logo-subtitle { font-size: 0.7rem; letter-spacing: 4px; color: var(--text-muted); }

        .nav-pills { position: relative; }
        .nav-pills .nav-link { 
            color: var(--text-muted); font-weight: 600; padding: 1rem; border-radius: 0.5rem; transition: all 0.3s ease; 
            border: 1px solid transparent; z-index: 1; position: relative;
        }
        .nav-pills .nav-link:hover { background-color: rgba(147, 51, 234, 0.1); color: var(--text-white); }
        .nav-pills .nav-link.active { color: var(--text-white); } /* El fondo lo da el indicador */
        .nav-pills .nav-icon { width: 25px; text-align: center; }
        .nav-indicator { 
            position: absolute; left: 0; 
            top: 0; /* Se ajusta con JS */
            height: 57px; width: 100%; 
            background-color: rgba(147, 51, 234, 0.2); 
            border-left: 3px solid var(--primary-neon-purple); 
            border-radius: 0.5rem; 
            box-shadow: 0 0 15px rgba(147, 51, 234, 0.4); 
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1); 
            z-index: 0; 
            opacity: 0; /* Inicia oculto */
        }
        .nav-pills:hover .nav-indicator { opacity: 1; } /* Hacer visible al hacer hover en el contenedor */

        .user-card { 
            background: transparent; 
            border: 1px solid var(--border-glow); 
            padding: 1.5rem; 
            border-radius: 0.75rem; 
            transform-style: preserve-3d;
            transition: transform 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .user-card .user-balance span.user-balance-value { color: var(--accent-glow-cyan); font-weight: 700; text-shadow: 0 0 8px var(--accent-glow-cyan); }
        
        .btn-recharge, .btn-logout { border-radius: 0.5rem; padding: 0.75rem; font-weight: 700; transition: all 0.3s ease; border: none; text-transform: uppercase; letter-spacing: 1px; }
        .btn-recharge { background: linear-gradient(90deg, var(--accent-glow-cyan), #00c4c4); color: var(--bg-dark-space); }
        .btn-recharge:hover { box-shadow: 0 0 20px var(--accent-glow-cyan); transform: translateY(-3px); }
        .btn-logout { background: linear-gradient(90deg, var(--accent-hot-pink), #e0006f); color: var(--text-white); }
        .btn-logout:hover { box-shadow: 0 0 20px var(--accent-hot-pink); transform: translateY(-3px); }

        /* --- Content Wrapper & Scrollbar --- */
        .content-wrapper { 
            flex-grow: 1; 
            overflow-y: auto; /* ✅ CORRECCIÓN: Habilitar scroll solo en el contenido */
            display: flex; 
            flex-direction: column; 
            background: none !important; 
        }
        
        .content-wrapper::-webkit-scrollbar { width: 8px; }
        .content-wrapper::-webkit-scrollbar-track { background: transparent; }
        .content-wrapper::-webkit-scrollbar-thumb {
            background-color: var(--primary-neon-purple);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: content-box;
        }
        .content-wrapper::-webkit-scrollbar-thumb:hover { background-color: var(--accent-glow-cyan); }

        .page-header { text-align: center; padding: 1.5rem 2.5rem 0; }
        .page-title { position: relative; font-family: 'Orbitron', sans-serif; font-size: 2.25rem; font-weight: 900; color: var(--text-white); animation: text-glow 4s ease-in-out infinite; margin: 0; }
        .page-subtitle { font-size: 0.9rem; color: var(--text-muted); margin-top: 0.5rem; min-height: 1.2em; }
        .page-subtitle::after { content: '|'; animation: blink 1s step-end infinite; }
        @keyframes blink { 50% { opacity: 0; } }

        .page-content {
            padding: 2.5rem;
            flex-grow: 1;
            background: none !important;
        }
        
        .scroll-animate {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .scroll-animate.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .page-footer { 
            padding: 1rem 2.5rem; text-align: center; font-size: 0.8rem; color: var(--text-muted); 
            background: linear-gradient(to top, rgba(5, 2, 16, 0.7), transparent); 
            flex-shrink: 0; /* Asegura que el footer no se comprima */
        }
        .page-footer a { color: var(--accent-glow-cyan); text-decoration: none; font-weight: 600; }

        /* --- Mobile Styles (max-width: 768px) --- */
        @media (max-width: 768px) {
            body { cursor: auto; padding-top: 60px; height: auto; overflow-y: auto; }
            .main-container { height: auto; flex-direction: column; }
            .content-wrapper { overflow-y: visible; padding-bottom: 70px; /* Espacio para nav móvil */ }
            .cursor-aura, .cursor-dot { display: none; }
            .sidebar { display: none !important; }
            .page-header { display: none !important; }
            .page-content { padding: 1.5rem; }
            .page-footer { flex-shrink: 0; padding: 1rem 1.5rem 1.5rem; }
        }
        
        /* --- Mobile Nav --- */
        .mobile-top-nav { 
            position: fixed; bottom: 0; left: 0; right: 0; height: 60px; z-index: 1000; 
            border-top: 1px solid var(--border-glow); /* Cambiado a top para mejor look */
            background: rgba(5, 2, 16, 0.8) !important; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); 
            display: flex; /* Asegurar que se muestre en móvil */
        }
        .mobile-top-nav .nav-link { display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.7rem; transition: all 0.3s ease; height: 100%; position: relative; }
        .mobile-top-nav .nav-link .nav-icon { font-size: 1.25rem; margin-bottom: 0.1rem; transition: transform 0.3s ease; }
        .mobile-top-nav .nav-link.active { color: var(--primary-neon-purple); }
        .mobile-top-nav .nav-link.active .nav-icon { animation: pulse 1s; }
        .mobile-top-nav .nav-link.active::after { content: ''; position: absolute; top: 5px; /* Ajustado a la parte superior */ width: 5px; height: 5px; background-color: var(--primary-neon-purple); border-radius: 50%; box-shadow: var(--shadow-glow-purple); }

        /* --- Modal Styles --- */
        .modal-dialog { perspective: 1000px; }
        .modal-content {
            background: var(--bg-glass); 
            backdrop-filter: blur(15px); 
            -webkit-backdrop-filter: blur(15px); 
            border: 1px solid var(--border-glow); 
            border-radius: 1rem;
            color: var(--text-white);
        }
        .modal-header { border-bottom: none; }
        .btn-close-white { filter: invert(1); }
    </style>
</head>
<body>
    <div id="preloader">
        <div class="spinner"></div>
    </div>

    <div class="cursor-aura d-none d-md-block"></div>
    <div class="cursor-dot d-none d-md-block"></div>
    
    <div id="tsparticles"></div>

    <div class="main-container">
        <aside class="sidebar d-none d-md-flex flex-column">
            <div class="flex-grow-1">
                <div class="sidebar-header text-center mb-4">
                    <a href="{{ route('dashboard') }}" class="logo-box">
                        <svg viewBox="0 0 200 50" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="logo-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="var(--primary-neon-purple)" />
                                    <stop offset="100%" stop-color="var(--accent-hot-pink)" />
                                </linearGradient>
                            </defs>
                            <text x="50%" y="35" font-family="Orbitron, sans-serif" font-size="35" font-weight="900" fill="url(#logo-gradient)" text-anchor="middle">
                                PANDORA
                            </text>
                        </svg>
                        <div class="logo-subtitle">STREAMING</div>
                    </a>
                </div>
                <nav class="main-nav nav nav-pills flex-column">
                    <div class="nav-indicator"></div>
                    <a href="{{ route('dashboard') }}" class="nav-link text-start {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-title="Dashboard" data-subtitle="Tu centro de control principal."><i class="fas fa-home nav-icon me-3"></i>Inicio</a>
                    <a href="{{ route('tienda.index') }}" class="nav-link text-start {{ request()->routeIs('tienda.*') ? 'active' : '' }}" data-title="Tienda" data-subtitle="Explora nuestro catálogo de productos."><i class="fas fa-store nav-icon me-3"></i>Tienda</a>
                    <a href="{{ route('cuenta.index') }}" class="nav-link text-start {{ request()->routeIs('cuenta.*') ? 'active' : '' }}" data-title="Mi Cuenta" data-subtitle="Gestiona tu perfil y tus compras."><i class="fas fa-user-circle nav-icon me-3"></i>Mi Cuenta</a>
                    <a href="{{ route('soporte.index') }}" class="nav-link text-start {{ request()->routeIs('soporte.*') ? 'active' : '' }}" data-title="Soporte" data-subtitle="¿Necesitas ayuda? Estamos aquí para ti."><i class="fas fa-headset nav-icon me-3"></i>Soporte</a>
                    @can('view-admin-dashboard')
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-start {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-title="Panel de Admin" data-subtitle="Administración del sistema."><i class="fas fa-shield-halved nav-icon me-3"></i>Admin</a>
                    @endcan
                </nav>
            </div>
            <div class="my-4">
                <a href="{{ route('soporte.index') }}" class="btn btn-recharge w-100 d-flex align-items-center justify-content-center text-decoration-none recharge-button">
                    <i class="fas fa-wallet me-2"></i>
                    <span>Recargar Saldo</span>
                </a>
            </div>
            <div class="user-card text-center">
                <h5 class="user-name mb-2">{{ Auth::user()->name ?? 'Visitante' }}</h5>
                <p class="user-balance mb-3" data-balance="{{ Auth::user()->saldo ?? 0 }}">Saldo: <span class="user-balance-value">${{ number_format(Auth::user()->saldo ?? 0, 0, ',', '.') }}</span></p>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="btn btn-logout w-100">
                        <i class="fas fa-power-off me-2"></i>Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="page-header d-none d-md-block">
                <h1 class="page-title" id="page-title">Bienvenido a Pandora Streaming</h1>
                <p class="page-subtitle" id="typing-subtitle"></p>
            </div>
            <main class="page-content">
                @yield('content')
            </main>
            <footer class="page-footer">
                © {{ date('Y') }} NPStreaming. Todos los derechos reservados. | Desarrollado por <a href="#" target="_blank">VICTECH</a>
            </footer>
        </div>
    </div>

    <nav class="mobile-top-nav d-flex d-md-none">
        <a href="{{ route('dashboard') }}" class="nav-link flex-fill {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home nav-icon"></i><div>Inicio</div>
        </a>
        <a href="{{ route('tienda.index') }}" class="nav-link flex-fill {{ request()->routeIs('tienda.*') ? 'active' : '' }}">
            <i class="fas fa-store nav-icon"></i><div>Tienda</div>
        </a>
        <a href="{{ route('cuenta.index') }}" class="nav-link flex-fill {{ request()->routeIs('cuenta.*') ? 'active' : '' }}">
            <i class="fas fa-user-circle nav-icon"></i><div>Cuenta</div>
        </a>
        <a href="{{ route('soporte.index') }}" class="nav-link flex-fill {{ request()->routeIs('soporte.*') ? 'active' : '' }}">
            <i class="fas fa-headset nav-icon"></i><div>Soporte</div>
        </a>
        <a href="#" class="nav-link flex-fill" data-bs-toggle="modal" data-bs-target="#profileModal">
            <i class="fas fa-ellipsis nav-icon"></i><div>Menú</div>
        </a>
    </nav>

    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <a href="{{ route('dashboard') }}" class="logo-box d-inline-block">
                            <svg viewBox="0 0 200 50" xmlns="http://www.w3.org/2000/svg" style="max-width: 120px;">
                                <defs>
                                    <linearGradient id="logo-gradient-modal" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="var(--primary-neon-purple)" />
                                        <stop offset="100%" stop-color="var(--accent-hot-pink)" />
                                    </linearGradient>
                                </defs>
                                <text x="50%" y="35" font-family="Orbitron, sans-serif" font-size="30" font-weight="900" fill="url(#logo-gradient-modal)" text-anchor="middle">
                                    PANDORA
                                </text>
                            </svg>
                            <div class="logo-subtitle">STREAMING</div>
                        </a>
                    </div>
                    <div class="user-card text-center">
                        <h5 class="user-name mb-2">{{ Auth::user()->name ?? 'Visitante' }}</h5>
                        <p class="user-balance mb-3" data-balance="{{ Auth::user()->saldo ?? 0 }}">Saldo: <span class="user-balance-value">${{ number_format(Auth::user()->saldo ?? 0, 0, ',', '.') }}</span></p>
                        <a href="{{ route('soporte.index') }}" class="btn btn-recharge w-100 d-flex align-items-center justify-content-center text-decoration-none mb-3 recharge-button" data-bs-dismiss="modal">
                            <i class="fas fa-wallet me-2"></i>
                            <span>Recargar Saldo</span>
                        </a>
                        @can('view-admin-dashboard')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center text-decoration-none mb-3" data-bs-dismiss="modal">
                             <i class="fas fa-shield-halved nav-icon me-2"></i><span>Panel de Admin</span>
                        </a>
                        @endcan
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="btn btn-logout w-100">
                                <i class="fas fa-power-off me-2"></i>Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/tsparticles@2/tsparticles.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tone/14.7.77/Tone.js"></script>
    
    @stack('scripts')

    <script>
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.classList.add('loaded');
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            // --- AudioContext (Tone.js) Setup ---
            const sound = {
                isInitialized: false,
                hover: null,
                click: null,
                init() {
                    if (Tone.context.state !== 'running' || this.isInitialized) return; 
                    
                    try {
                         // Inicializar synths
                        this.hover = new Tone.Synth({ oscillator: { type: 'sine' }, envelope: { attack: 0.005, decay: 0.1, sustain: 0.2, release: 0.1 } }).toDestination();
                        this.click = new Tone.MembraneSynth({ octaves: 4, pitchDecay: 0.1 }).toDestination();
                        this.isInitialized = true;
                        console.log('AudioContext started and sounds initialized.');
                    } catch (e) {
                        console.error("Tone.js initialization error:", e);
                    }
                },
                play(soundName) {
                    if (!this.isInitialized) return;
                    try {
                        if (soundName === 'hover' && this.hover) this.hover.triggerAttackRelease('C5', '16n');
                        if (soundName === 'click' && this.click) this.click.triggerAttackRelease('C2', '8n');
                    } catch (e) { console.error("Tone.js playback error:", e); }
                }
            };
            
            // Iniciar AudioContext en la primera interacción del usuario (click o mousedown)
            const initializeAudio = async () => {
                if (!sound.isInitialized && Tone.context.state !== 'running') {
                    await Tone.start();
                    sound.init();
                    document.body.removeEventListener('mousedown', initializeAudio);
                    document.body.removeEventListener('click', initializeAudio); // Para asegurar en móvil
                }
            };

            document.body.addEventListener('mousedown', initializeAudio, { once: true });
            document.body.addEventListener('click', initializeAudio, { once: true });

            // --- tsParticles Background ---
            tsParticles.load("tsparticles", {
                fps_limit: 60,
                interactivity: {
                    events: {
                        onhover: { enable: true, mode: "attract" },
                        onclick: { enable: true, mode: "push" },
                        resize: true
                    },
                    modes: {
                        attract: { distance: 150, duration: 0.4, factor: 1 },
                        push: { quantity: 4 }
                    }
                },
                particles: {
                    color: { value: "#22d3ee" },
                    links: {
                        color: "#a855f7",
                        distance: 150,
                        enable: true,
                        opacity: 0.4,
                        width: 1
                    },
                    move: {
                        enable: true,
                        speed: 1,
                    },
                    number: {
                        density: { enable: true, value_area: 800 },
                        value: 80
                    },
                    opacity: { value: 0.5 },
                    shape: { type: "circle" },
                    size: {
                        random: true,
                        value: 2
                    }
                },
                // Emisor de partículas para el efecto de "estrella fugaz" o "flujo"
                /* * Nota: El código del emisor estaba correcto, pero puede impactar el rendimiento en dispositivos 
                * de gama baja. Lo mantengo, pero es bueno ser consciente de ello.
                */
                emitters: {
                    direction: "top-right",
                    rate: {
                        quantity: 1,
                        delay: 7
                    },
                    position: { x: 0, y: 50 },
                    size: { width: 0, height: 0 },
                    particles: {
                        color: { value: "#fff" },
                        size: { value: { min: 1, max: 2 } },
                        move: {
                            speed: 40,
                            direction: "top-right",
                            straight: true,
                            trail: {
                                enable: true,
                                fillColor: "#02010a",
                                length: 15
                            }
                        },
                        opacity: {
                            value: { min: 0.1, max: 1 },
                            animation: {
                                enable: true,
                                speed: 4,
                                destroy: "max",
                                startValue: "max",
                                sync: false
                            }
                        }
                    }
                }
            });
            
            // --- Custom Cursor ---
            const cursorAura = document.querySelector('.cursor-aura');
            const cursorDot = document.querySelector('.cursor-dot');
            if(cursorAura && cursorDot) {
                window.addEventListener('mousemove', e => {
                    // Usar requestAnimationFrame para optimizar el movimiento del cursor
                    requestAnimationFrame(() => {
                        cursorAura.style.left = `${e.clientX}px`;
                        cursorAura.style.top = `${e.clientY}px`;
                        cursorDot.style.left = `${e.clientX}px`;
                        cursorDot.style.top = `${e.clientY}px`;
                    });
                });
            }

            // --- Navigation & Header Logic (Desktop) ---
            const navLinks = document.querySelectorAll('.nav-pills .nav-link');
            const navIndicator = document.querySelector('.nav-indicator');
            const pageTitle = document.getElementById('page-title');
            const pageSubtitle = document.getElementById('typing-subtitle');
            let typeWriterTimeout;
            
            function updateHeader(link) {
                if(pageTitle) pageTitle.textContent = link.dataset.title || 'Pandora Streaming';
                if(pageSubtitle) {
                    const text = link.dataset.subtitle;
                    pageSubtitle.innerHTML = '';
                    clearTimeout(typeWriterTimeout);
                    let i = 0;
                    function typeWriter() {
                        if (i < text.length) {
                            pageSubtitle.innerHTML += text.charAt(i);
                            i++;
                            typeWriterTimeout = setTimeout(typeWriter, 40);
                        }
                    }
                    typeWriter();
                }
            }

            if (navIndicator) {
                const navPillsContainer = document.querySelector('.nav-pills');

                // Posicionar el indicador en el enlace activo al cargar la página
                const setActiveIndicator = () => {
                    const activeLink = document.querySelector('.nav-pills .nav-link.active');
                    if(activeLink) {
                        navIndicator.style.top = `${activeLink.offsetTop}px`;
                        navIndicator.style.opacity = '1';
                        updateHeader(activeLink);
                    } else {
                         // Fallback si no hay activo (por ejemplo, al cargar por primera vez)
                        navIndicator.style.opacity = '0';
                        if(pageTitle) pageTitle.textContent = 'Bienvenido a Pandora Streaming';
                        if(pageSubtitle) pageSubtitle.innerHTML = '¡Prepárate para la mejor experiencia de streaming!';
                    }
                };

                setActiveIndicator();
                
                navLinks.forEach(link => {
                    link.addEventListener('mouseenter', (e) => {
                        sound.play('hover');
                        navIndicator.style.top = `${e.currentTarget.offsetTop}px`;
                        navIndicator.style.opacity = '1';
                    });
                    
                    // El evento 'click' solo actualiza el estado, la navegación la hace Laravel
                    link.addEventListener('click', (e) => {
                        sound.play('click');
                        // Se actualiza el header en el click para una respuesta instantánea
                        updateHeader(e.currentTarget); 
                    });
                });
                
                // ✅ CORRECCIÓN: Mantener el indicador en el elemento activo (o desaparecer si no hay) al salir del área de navegación.
                navPillsContainer.addEventListener('mouseleave', () => {
                    setActiveIndicator();
                });
            }

            // --- Tilt 3D Effect on User Card ---
            const userCard = document.querySelector('.user-card');
            const sidebar = document.querySelector('.sidebar');
            if (userCard && sidebar) {
                sidebar.addEventListener('mousemove', (e) => {
                    const { top, left, width, height } = userCard.getBoundingClientRect();
                    // Calcular la posición relativa al centro de la tarjeta, pero basado en la posición del cursor en el sidebar
                    // Usar la posición del ratón dentro del sidebar, no solo dentro de la tarjeta
                    const sidebarRect = sidebar.getBoundingClientRect();
                    const x = e.clientX - sidebarRect.left - sidebarRect.width / 2;
                    const y = e.clientY - sidebarRect.top - sidebarRect.height / 2;
                    
                    // Normalizar y aplicar un factor de rotación
                    const rotateX = (y / (sidebarRect.height / 2)) * -8;
                    const rotateY = (x / (sidebarRect.width / 2)) * 8;
                    
                    userCard.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
                });
                
                sidebar.addEventListener('mouseleave', () => {
                    userCard.style.transform = 'rotateX(0deg) rotateY(0deg)';
                });
            }
            
            // --- Click Sound & Logout Confirmation ---
            document.body.addEventListener('click', function(e) {
                const logoutButton = e.target.closest('.btn-logout');
                const rechargeButton = e.target.closest('.recharge-button'); 
                const modalToggle = e.target.closest('[data-bs-toggle="modal"]'); // Para el modal móvil

                if (rechargeButton || modalToggle) {
                    sound.play('click');
                }
                
                if (logoutButton) {
                    e.preventDefault();
                    sound.play('click');
                    const form = logoutButton.closest('.logout-form');
                    if (form) {
                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: "Tu sesión se cerrará.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: 'var(--accent-hot-pink)',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Sí, cerrar sesión',
                            cancelButtonText: 'Cancelar',
                            background: 'var(--bg-dark-space)', 
                            color: 'var(--text-white)',
                            customClass: {
                                confirmButton: 'swal2-confirm-button',
                                cancelButton: 'swal2-cancel-button'
                            },
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    }
                }
            });
            
            // --- Scroll Animation for Page Content ---
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        obs.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '0px',
                threshold: 0.1
            });

            // Seleccionar elementos que queremos animar (podrías añadir más selectores si es necesario)
            document.querySelectorAll('.scroll-animate').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>