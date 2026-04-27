{{-- resources/views/soporte/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="soporte-container">
    {{-- Hero Section de Soporte --}}
    <section class="soporte-hero">
        <div class="hero-background">
            <div class="hero-particles"></div>
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>
        </div>
        <div class="hero-content">
            <div class="support-icon animate-bounce-in">
                <i class="fas fa-headset"></i>
                <div class="icon-glow"></div>
            </div>
            <h1 class="hero-title animate-slide-up">Centro de Soporte</h1>
            <p class="hero-subtitle animate-slide-up delay-1">Estamos aquí para ayudarte. Contáctanos a través de cualquiera de nuestros canales de atención.</p>
            <div class="status-indicator animate-slide-up delay-2">
                <div class="status-dot online"></div>
                <span class="status-text">Soporte disponible 24/7</span>
            </div>
        </div>
    </section>

    {{-- Canales de Contacto Principales --}}
    <section class="contact-channels">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-comments"></i> Canales de Contacto
            </h2>
            <p class="section-subtitle">Elige la forma que prefieras para contactarnos</p>
        </div>

        <div class="channels-grid">
            {{-- WhatsApp --}}
            <div class="channel-card whatsapp animate-card" style="animation-delay: 0.1s">
                <div class="card-header">
                    <div class="channel-icon">
                        <i class="fab fa-whatsapp"></i>
                        <div class="icon-pulse"></div>
                    </div>
                    <div class="channel-info">
                        <h3 class="channel-title">WhatsApp</h3>
                        <p class="channel-subtitle">Respuesta inmediata</p>
                    </div>
                    <div class="status-badge online">
                        <i class="fas fa-circle"></i> En línea
                    </div>
                </div>
                <div class="card-body">
                    <p class="channel-description">Chatea con nosotros directamente por WhatsApp para una atención rápida y personalizada.</p>
                    <div class="contact-info">
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <span>+57 313 696 8036</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <span>Disponible 24/7</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="https://wa.me/573136968036?text=Hola,%20necesito%20ayuda%20con%20NPStreaming" target="_blank" class="btn-contact whatsapp-btn">
                        <i class="fab fa-whatsapp"></i>
                        <span>Abrir WhatsApp</span>
                        <div class="btn-glow"></div>
                    </a>
                </div>
                <div class="card-shine"></div>
            </div>

            {{-- Email --}}
            <div class="channel-card email animate-card" style="animation-delay: 0.2s">
                <div class="card-header">
                    <div class="channel-icon">
                        <i class="fas fa-envelope"></i>
                        <div class="icon-pulse"></div>
                    </div>
                    <div class="channel-info">
                        <h3 class="channel-title">Email</h3>
                        <p class="channel-subtitle">Soporte detallado</p>
                    </div>
                    <div class="status-badge online">
                        <i class="fas fa-circle"></i> Activo
                    </div>
                </div>
                <div class="card-body">
                    <p class="channel-description">Envíanos un email detallado con tu consulta y te responderemos a la brevedad.</p>
                    <div class="contact-info">
                        <div class="info-item">
                            <i class="fas fa-at"></i>
                            <span>soporte@dvstreaming.com</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-reply"></i>
                            <span>Respuesta en 2-4 horas</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="mailto:soporte@dvstreaming.com?subject=Consulta%20NPStreaming&body=Hola,%0D%0A%0D%0ANecesito%20ayuda%20con:%0D%0A%0D%0AGracias." class="btn-contact email-btn">
                        <i class="fas fa-envelope"></i>
                        <span>Enviar Email</span>
                        <div class="btn-glow"></div>
                    </a>
                </div>
                <div class="card-shine"></div>
            </div>

            {{-- Chat en Vivo --}}
            <div class="channel-card chat animate-card" style="animation-delay: 0.3s">
                <div class="card-header">
                    <div class="channel-icon">
                        <i class="fas fa-comments"></i>
                        <div class="icon-pulse"></div>
                    </div>
                    <div class="channel-info">
                        <h3 class="channel-title">Chat en Vivo</h3>
                        <p class="channel-subtitle">Atención instantánea</p>
                    </div>
                    <div class="status-badge online">
                        <i class="fas fa-circle"></i> Disponible
                    </div>
                </div>
                <div class="card-body">
                    <p class="channel-description">Inicia una conversación en tiempo real con nuestro equipo de soporte técnico.</p>
                    <div class="contact-info">
                        <div class="info-item">
                            <i class="fas fa-bolt"></i>
                            <span>Respuesta inmediata</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-users"></i>
                            <span>3 agentes en línea</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn-contact chat-btn" onclick="iniciarChat()">
                        <i class="fas fa-comments"></i>
                        <span>Iniciar Chat</span>
                        <div class="btn-glow"></div>
                    </button>
                </div>
                <div class="card-shine"></div>
            </div>
        </div>
    </section>

    {{-- Formulario de Contacto --}}
    <section class="contact-form-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-paper-plane"></i> Formulario de Contacto
            </h2>
            <p class="section-subtitle">Envíanos tu consulta y te responderemos pronto</p>
        </div>

        <div class="form-container">
            <form id="contactForm" class="contact-form" action="#" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i> Nombre Completo
                        </label>
                        <input type="text" id="name" name="name" class="form-input" placeholder="Tu nombre completo" required>
                        <div class="input-focus"></div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="tu@email.com" required>
                        <div class="input-focus"></div>
                    </div>

                    <div class="form-group full-width">
                        <label for="subject" class="form-label">
                            <i class="fas fa-tag"></i> Asunto
                        </label>
                        <select id="subject" name="subject" class="form-input" required>
                            <option value="">Selecciona un tema</option>
                            <option value="problema-cuenta">Problema con mi cuenta</option>
                            <option value="problema-compra">Problema con una compra</option>
                            <option value="problema-tecnico">Problema técnico</option>
                            <option value="sugerencia">Sugerencia o mejora</option>
                            <option value="otro">Otro</option>
                        </select>
                        <div class="input-focus"></div>
                    </div>

                    <div class="form-group full-width">
                        <label for="message" class="form-label">
                            <i class="fas fa-comment-alt"></i> Mensaje
                        </label>
                        <textarea id="message" name="message" class="form-input" rows="5" placeholder="Describe tu consulta o problema..." required></textarea>
                        <div class="input-focus"></div>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        <span>Enviar Mensaje</span>
                        <div class="btn-glow"></div>
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- FAQ Section --}}
    <section class="faq-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-question-circle"></i> Preguntas Frecuentes
            </h2>
            <p class="section-subtitle">Encuentra respuestas rápidas a las consultas más comunes</p>
        </div>

        <div class="faq-container">
            <div class="faq-item" data-faq="1">
                <div class="faq-question">
                    <i class="fas fa-credit-card"></i>
                    <span>¿Cómo puedo realizar una compra?</span>
                    <i class="fas fa-chevron-down faq-arrow"></i>
                </div>
                <div class="faq-answer">
                    <p>Puedes realizar compras de dos formas: agregando productos al carrito o comprando directamente desde la página del producto. Aceptamos pagos con saldo de cuenta y otros métodos de pago.</p>
                </div>
            </div>

            <div class="faq-item" data-faq="2">
                <div class="faq-question">
                    <i class="fas fa-key"></i>
                    <span>¿Cuándo recibo mis credenciales?</span>
                    <i class="fas fa-chevron-down faq-arrow"></i>
                </div>
                <div class="faq-answer">
                    <p>Las credenciales se envían inmediatamente después de confirmar el pago. Revisa tu email y la sección "Mi Cuenta" donde podrás ver todos los detalles de tus compras.</p>
                </div>
            </div>

            <div class="faq-item" data-faq="3">
                <div class="faq-question">
                    <i class="fas fa-wallet"></i>
                    <span>¿Cómo cargo saldo a mi cuenta?</span>
                    <i class="fas fa-chevron-down faq-arrow"></i>
                </div>
                <div class="faq-answer">
                    <p>Puedes cargar saldo contactándonos por WhatsApp o email. Te proporcionaremos las instrucciones para realizar la transferencia y acreditaremos el saldo inmediatamente.</p>
                </div>
            </div>

            <div class="faq-item" data-faq="4">
                <div class="faq-question">
                    <i class="fas fa-shield-alt"></i>
                    <span>¿Qué garantía tienen los productos?</span>
                    <i class="fas fa-chevron-down faq-arrow"></i>
                </div>
                <div class="faq-answer">
                    <p>Todos nuestros productos tienen garantía. Si tienes algún problema con las credenciales, contáctanos inmediatamente y lo solucionaremos o te devolveremos tu dinero.</p>
                </div>
            </div>

            <div class="faq-item" data-faq="5">
                <div class="faq-question">
                    <i class="fas fa-clock"></i>
                    <span>¿Cuáles son los horarios de atención?</span>
                    <i class="fas fa-chevron-down faq-arrow"></i>
                </div>
                <div class="faq-answer">
                    <p>Nuestro soporte está disponible 24/7 por WhatsApp y email. Para consultas urgentes, WhatsApp es la opción más rápida con respuesta inmediata.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Información Adicional --}}
    <section class="info-section">
        <div class="info-grid">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Horarios de Atención</h3>
                <p>WhatsApp: 24/7<br>Email: 24/7<br>Respuesta promedio: 15 minutos</p>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <h3>Cobertura</h3>
                <p>Atendemos clientes en toda Colombia<br>Soporte en español<br>Métodos de pago locales</p>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-award"></i>
                </div>
                <h3>Calidad</h3>
                <p>+10,000 clientes satisfechos<br>99% de satisfacción<br>Soporte especializado</p>
            </div>
        </div>
    </section>
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
    --whatsapp: #25d366;
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
.soporte-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: clamp(1rem, 4vw, 2rem);
    background: var(--bg-main) !important;
    min-height: 100vh;
}

/* === HERO SECTION === */
.soporte-hero {
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

.support-icon {
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

.icon-glow {
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

.support-icon:hover .icon-glow {
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
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(255, 255, 255, 0.2) !important;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.status-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.status-dot.online {
    background: var(--success);
    box-shadow: 0 0 10px var(--success);
}

.status-text {
    font-weight: 600;
    color: var(--text-white);
}

/* === SECCIONES === */
.section-header {
    text-align: center;
    margin-bottom: 3rem;
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

/* === CANALES DE CONTACTO === */
.contact-channels {
    margin-bottom: 4rem;
}

.channels-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.channel-card {
    position: relative;
    background: var(--bg-card) !important;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: var(--transition);
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

.channel-card:hover .card-shine {
    left: 150%;
}

.channel-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}

.channel-card.whatsapp:hover {
    border-color: var(--whatsapp);
    box-shadow: 0 25px 50px rgba(37, 211, 102, 0.3);
}

.channel-card.email:hover {
    border-color: var(--primary);
    box-shadow: 0 25px 50px rgba(99, 102, 241, 0.3);
}

.channel-card.chat:hover {
    border-color: var(--secondary);
    box-shadow: 0 25px 50px rgba(236, 72, 153, 0.3);
}

.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 2rem;
    border-bottom: 1px solid var(--border-color);
}

.channel-icon {
    position: relative;
    width: 60px;
    height: 60px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    margin-right: 1rem;
}

.channel-card.whatsapp .channel-icon {
    background: linear-gradient(135deg, var(--whatsapp), #128c7e);
}

.channel-card.email .channel-icon {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
}

.channel-card.chat .channel-icon {
    background: linear-gradient(135deg, var(--secondary), #be185d);
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

.channel-card.whatsapp .icon-pulse {
    border: 2px solid var(--whatsapp);
}

.channel-card.email .icon-pulse {
    border: 2px solid var(--primary);
}

.channel-card.chat .icon-pulse {
    border: 2px solid var(--secondary);
}

.channel-info {
    flex: 1;
}

.channel-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-white);
    margin: 0 0 0.25rem 0;
}

.channel-subtitle {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin: 0;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-badge.online {
    background: rgba(34, 197, 94, 0.2) !important;
    color: var(--success);
    border: 1px solid var(--success);
}

.card-body {
    padding: 2rem;
}

.channel-description {
    color: var(--text-muted);
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-white);
    font-size: 0.875rem;
    font-weight: 500;
}

.info-item i {
    width: 16px;
    color: var(--primary-light);
}

.card-footer {
    padding: 2rem;
    background: rgba(99, 102, 241, 0.05) !important;
}

.btn-contact {
    position: relative;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    border-radius: var(--radius);
    font-weight: 700;
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

.btn-contact:hover .btn-glow {
    left: 100%;
}

.whatsapp-btn {
    background: linear-gradient(135deg, var(--whatsapp), #128c7e) !important;
    color: white;
}

.whatsapp-btn:hover {
    background: linear-gradient(135deg, #128c7e, #075e54) !important;
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(37, 211, 102, 0.4);
    color: white;
    text-decoration: none;
}

.email-btn {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark)) !important;
    color: white;
}

.email-btn:hover {
    background: linear-gradient(135deg, var(--primary-dark), #3730a3) !important;
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(99, 102, 241, 0.4);
    color: white;
    text-decoration: none;
}

.chat-btn {
    background: linear-gradient(135deg, var(--secondary), #be185d) !important;
    color: white;
}

.chat-btn:hover {
    background: linear-gradient(135deg, #be185d, #9d174d) !important;
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(236, 72, 153, 0.4);
}

/* === FORMULARIO DE CONTACTO === */
.contact-form-section {
    margin-bottom: 4rem;
}

.form-container {
    max-width: 800px;
    margin: 0 auto;
    background: var(--bg-card) !important;
    padding: 3rem;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    backdrop-filter: blur(10px);
}

.contact-form {
    width: 100%;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.form-group {
    position: relative;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-label {
    display: block;
    font-weight: 600;
    color: var(--text-white);
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.form-input {
    width: 100%;
    padding: 1rem;
    background: var(--bg-main) !important;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    color: var(--text-white);
    font-size: 1rem;
    transition: var(--transition);
    position: relative;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
}

.form-input::placeholder {
    color: var(--text-muted);
}

.input-focus {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--primary);
    transition: width 0.3s ease;
}

.form-input:focus + .input-focus {
    width: 100%;
}

.form-footer {
    text-align: center;
}

.btn-submit {
    position: relative;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark)) !important;
    color: white;
    border: none;
    padding: 1rem 3rem;
    border-radius: var(--radius);
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    overflow: hidden;
}

.btn-submit:hover {
    background: linear-gradient(135deg, var(--primary-dark), #3730a3) !important;
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(99, 102, 241, 0.4);
}

/* === FAQ SECTION === */
.faq-section {
    margin-bottom: 4rem;
}

.faq-container {
    max-width: 800px;
    margin: 0 auto;
}

.faq-item {
    background: var(--bg-card) !important;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    margin-bottom: 1rem;
    overflow: hidden;
    transition: var(--transition);
}

.faq-item:hover {
    border-color: var(--primary);
}

.faq-question {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    cursor: pointer;
    transition: var(--transition);
    background: var(--bg-card) !important;
}

.faq-question:hover {
    background: var(--bg-card-hover) !important;
}

.faq-question i:first-child {
    color: var(--primary-light);
    font-size: 1.25rem;
    width: 24px;
}

.faq-question span {
    flex: 1;
    font-weight: 600;
    color: var(--text-white);
    font-size: 1rem;
}

.faq-arrow {
    color: var(--text-muted);
    transition: var(--transition);
    font-size: 0.875rem !important;
    width: auto !important;
}

.faq-item.active .faq-arrow {
    transform: rotate(180deg);
    color: var(--primary-light);
}

.faq-answer {
    padding: 0 1.5rem;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
    background: var(--bg-main) !important;
}

.faq-item.active .faq-answer {
    padding: 1.5rem;
    max-height: 200px;
}

.faq-answer p {
    color: var(--text-muted);
    line-height: 1.6;
    margin: 0;
}

/* === INFORMACIÓN ADICIONAL === */
.info-section {
    margin-bottom: 2rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.info-card {
    background: var(--bg-card) !important;
    padding: 2rem;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    text-align: center;
    transition: var(--transition);
}

.info-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: var(--shadow-lg);
}

.info-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin: 0 auto 1.5rem auto;
}

.info-card h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-white);
    margin-bottom: 1rem;
}

.info-card p {
    color: var(--text-muted);
    line-height: 1.6;
}

/* === ANIMACIONES === */
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

@keyframes pulse {
    0%, 100% { 
        transform: scale(1); 
        opacity: 1;
    }
    50% { 
        transform: scale(1.1); 
        opacity: 0.8;
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

/* === RESPONSIVE === */
@media (max-width: 1024px) {
    .channels-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .soporte-container {
        padding: 1rem;
    }
    
    .soporte-hero {
        padding: 3rem 1.5rem;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .support-icon {
        width: 100px;
        height: 100px;
        font-size: 3rem;
    }
    
    .form-container {
        padding: 2rem;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .channel-icon {
        margin-right: 0;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .form-container {
        padding: 1.5rem;
    }
    
    .btn-contact {
        padding: 0.875rem 1.5rem;
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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // === INTERSECTION OBSERVER PARA ANIMACIONES ===
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, observerOptions);
    
    // Observar elementos animados
    document.querySelectorAll('.animate-card, .animate-slide-up').forEach(el => {
        observer.observe(el);
    });
    
    // === FAQ INTERACTIVO ===
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', function() {
            const faqItem = this.parentElement;
            const isActive = faqItem.classList.contains('active');
            
            // Cerrar todos los FAQs
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Abrir el clickeado si no estaba activo
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });
    
    // === FORMULARIO DE CONTACTO ===
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Obtener datos del formulario
            const formData = new FormData(this);
            const name = formData.get('name');
            const email = formData.get('email');
            const subject = formData.get('subject');
            const message = formData.get('message');
            
            // Validación básica
            if (!name || !email || !subject || !message) {
                Swal.fire({
                    title: '⚠️ Campos Requeridos',
                    text: 'Por favor completa todos los campos del formulario.',
                    icon: 'warning',
                    background: 'var(--bg-card)',
                    color: 'var(--text-white)',
                    confirmButtonColor: 'var(--warning)',
                    confirmButtonText: 'Entendido'
                });
                return;
            }
            
            // Simular envío (aquí integrarías con tu backend)
            Swal.fire({
                title: '📧 Enviando Mensaje...',
                text: 'Por favor espera mientras procesamos tu consulta.',
                background: 'var(--bg-card)',
                color: 'var(--text-white)',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Simular delay de envío
            setTimeout(() => {
                Swal.fire({
                    title: '✅ ¡Mensaje Enviado!',
                    html: `
                        <div style="text-align: center; padding: 1rem;">
                            <div style="
                                width: 80px; 
                                height: 80px; 
                                background: linear-gradient(135deg, var(--success), #16a34a); 
                                border-radius: 50%; 
                                display: flex; 
                                align-items: center; 
                                justify-content: center; 
                                margin: auto; 
                                font-size: 2rem; 
                                color: white;
                                margin-bottom: 1.5rem;
                            ">
                                <i class="fas fa-check"></i>
                            </div>
                            <p style="color: var(--text-muted); margin-bottom: 1rem;">Tu mensaje ha sido enviado exitosamente.</p>
                            <p style="color: var(--text-muted); font-size: 0.875rem;">Te responderemos a <strong style="color: var(--primary-light);">${email}</strong> en las próximas 2-4 horas.</p>
                        </div>
                    `,
                    background: 'var(--bg-card)',
                    color: 'var(--text-white)',
                    confirmButtonColor: 'var(--success)',
                    confirmButtonText: '¡Perfecto!',
                    customClass: {
                        popup: 'success-modal'
                    }
                }).then(() => {
                    // Limpiar formulario
                    contactForm.reset();
                });
            }, 2000);
        });
    }
    
    // === FUNCIÓN PARA INICIAR CHAT ===
    window.iniciarChat = function() {
        Swal.fire({
            title: '💬 Chat en Vivo',
            html: `
                <div style="text-align: center; padding: 2rem;">
                    <div style="
                        width: 80px; 
                        height: 80px; 
                        background: linear-gradient(135deg, var(--secondary), #be185d); 
                        border-radius: 50%; 
                        display: flex; 
                        align-items: center; 
                        justify-content: center; 
                        margin: auto; 
                        font-size: 2rem; 
                        color: white;
                        margin-bottom: 1.5rem;
                        animation: pulse 2s infinite;
                    ">
                        <i class="fas fa-comments"></i>
                    </div>
                    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">¡Nuestro chat en vivo estará disponible pronto!</p>
                    <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 2rem;">Mientras tanto, puedes contactarnos por WhatsApp para atención inmediata.</p>
                    <div style="
                        background: rgba(37, 211, 102, 0.1); 
                        border: 1px solid rgba(37, 211, 102, 0.2); 
                        border-radius: 1rem; 
                        padding: 1rem;
                    ">
                        <div style="font-size: 0.875rem; color: var(--whatsapp);">
                            <i class="fab fa-whatsapp"></i> WhatsApp: +57 313 696 8036
                        </div>
                    </div>
                </div>
            `,
            background: 'var(--bg-card)',
            color: 'var(--text-white)',
            showConfirmButton: true,
            confirmButtonText: '<i class="fab fa-whatsapp"></i> Ir a WhatsApp',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            confirmButtonColor: 'var(--whatsapp)',
            cancelButtonColor: 'var(--text-muted)',
            customClass: {
                popup: 'chat-modal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.open('https://wa.me/573136968036?text=Hola,%20necesito%20ayuda%20con%20NPStreaming', '_blank');
            }
        });
    };
    
    // === EFECTOS DE HOVER MEJORADOS ===
    document.querySelectorAll('.btn-contact').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.02)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    console.log('🎉 Centro de Soporte NPStreaming cargado exitosamente!');
});
</script>
@endpush
