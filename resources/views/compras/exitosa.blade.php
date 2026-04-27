{{-- resources/views/compras/exitosa.blade.php --}}
@extends('layouts.app')

{{-- Estilos específicos para la página de éxito --}}
@push('styles')
<style>
    .success-page-container {
        width: 100%;
        padding: 2rem 0;
    }

    .success-card {
        max-width: 900px;
        margin: 0 auto;
        background: rgba(10, 5, 25, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid var(--border-glow);
        border-radius: 1rem;
        padding: 3rem;
        text-align: center;
        box-shadow: var(--shadow-glow-purple);
        transform-style: preserve-3d;
        transition: transform 0.2s ease-out;
    }

    .success-header {
        margin-bottom: 2.5rem;
    }

    .success-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, var(--accent-glow-cyan), var(--success-green));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 30px var(--success-green);
        animation: icon-pop 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    @keyframes icon-pop {
        from { transform: scale(0.5); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .success-icon i {
        font-size: 3rem;
        color: var(--bg-dark-space);
    }

    .success-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--text-white);
        text-shadow: 0 0 15px var(--success-green);
    }

    .success-subtitle {
        font-size: 1.1rem;
        color: var(--text-muted);
    }

    .section-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.5rem;
        color: var(--primary-neon-purple);
        margin-bottom: 2rem;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .order-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
        text-align: left;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: rgba(0,0,0,0.2);
        padding: 1rem;
        border-radius: 0.75rem;
        border: 1px solid var(--border-glow);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .detail-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(147, 51, 234, 0.2);
    }
    .detail-item i {
        font-size: 1.25rem;
        color: var(--accent-glow-cyan);
        width: 30px;
        text-align: center;
    }
    .detail-content {
        display: flex;
        flex-direction: column;
    }
    .detail-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
    }
    .detail-value {
        font-weight: 600;
        color: var(--text-white);
    }
    .detail-value.status-delivered {
        color: var(--success-green) !important;
        font-weight: 700;
    }

    .products-list {
        display: grid;
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .product-card {
        background: rgba(0,0,0,0.2);
        border: 1px solid var(--border-glow);
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: left;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 25px rgba(147, 51, 234, 0.25);
    }
    .product-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .product-image {
        width: 80px; height: 80px;
        object-fit: cover;
        border-radius: 0.75rem;
        border: 2px solid var(--border-glow);
    }
    .product-info h4 {
        font-family: 'Orbitron', sans-serif;
        margin: 0;
        color: var(--text-white);
    }
    .product-info p {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin: 0.25rem 0 0 0;
    }

    .accounts-grid {
        display: grid;
        gap: 1rem;
    }
    .account-card {
        background: linear-gradient(145deg, rgba(0,0,0,0.4), rgba(0,0,0,0.2));
        border-radius: 0.75rem;
        padding: 1rem;
        border-left: 3px solid var(--accent-glow-cyan);
    }
    .account-field {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        font-family: 'Share Tech Mono', monospace;
    }
    .account-field strong {
        color: var(--accent-glow-cyan);
        margin-right: 1rem;
    }
    .account-value {
        color: var(--text-white);
        word-break: break-all;
    }
    
    .copy-btn {
        background: none;
        border: 1px solid var(--text-muted);
        color: var(--text-muted);
        border-radius: 5px;
        padding: 0.1rem 0.5rem;
        font-size: 0.8rem;
        cursor: none;
        transition: all 0.3s ease;
    }
    .copy-btn:hover {
        color: var(--accent-glow-cyan);
        border-color: var(--accent-glow-cyan);
        transform: scale(1.1);
    }

    .success-actions {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    .action-btn {
        display: inline-flex; align-items: center; gap: 0.75rem;
        padding: 0.8rem 1.8rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .action-btn.btn-primary {
        background: var(--primary-neon-purple);
        color: var(--text-white);
        box-shadow: var(--shadow-glow-purple);
    }
    .action-btn.btn-primary:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 0 35px var(--primary-neon-purple);
    }
    .action-btn.btn-secondary {
        background: transparent;
        color: var(--text-white);
        border-color: var(--primary-neon-purple);
    }
    .action-btn.btn-secondary:hover {
        background: var(--primary-neon-purple);
        border-color: var(--primary-neon-purple);
        box-shadow: var(--shadow-glow-purple);
    }

    @media (max-width: 768px) {
        .success-card { padding: 1.5rem; }
        .success-title { font-size: 2rem; }
        .order-details-grid { grid-template-columns: 1fr; }
        .product-header { flex-direction: column; text-align: center; }
    }
</style>
@endpush

@section('content')
<div class="success-page-container">
    <div class="success-card scroll-animate">
        <div class="success-header">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h1 class="success-title">¡Compra Exitosa!</h1>
            <p class="success-subtitle">Tus productos han sido entregados. Revisa los detalles a continuación.</p>
        </div>

        <div class="order-details-grid">
            <div class="detail-item scroll-animate"><i class="fas fa-receipt"></i><div class="detail-content"><span class="detail-label">Número de Pedido</span><span class="detail-value">NP-{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}</span></div></div>
            <div class="detail-item scroll-animate"><i class="fas fa-hashtag"></i><div class="detail-content"><span class="detail-label">ID del Pedido</span><span class="detail-value">#{{ $pedido->id }}</span></div></div>
            <div class="detail-item scroll-animate"><i class="fas fa-box"></i><div class="detail-content"><span class="detail-label">Cantidad de Items</span><span class="detail-value">{{ $pedido->item_count }}</span></div></div>
            <div class="detail-item scroll-animate"><i class="fas fa-dollar-sign"></i><div class="detail-content"><span class="detail-label">Total Pagado</span><span class="detail-value">${{ number_format($pedido->total_amount, 0, ',', '.') }}</span></div></div>
            <div class="detail-item scroll-animate"><i class="fas fa-credit-card"></i><div class="detail-content"><span class="detail-label">Método de Pago</span><span class="detail-value">{{ $pedido->payment_method }}</span></div></div>
            <div class="detail-item scroll-animate"><i class="fas fa-bolt"></i><div class="detail-content"><span class="detail-label">Estado</span><span class="detail-value status-delivered">{{ $pedido->status }}</span></div></div>
        </div>

        @if($pedido->cuentas && $pedido->cuentas->count() > 0)
        <div class="products-purchased">
            <h2 class="section-title scroll-animate">Productos Adquiridos</h2>
            <div class="products-list">
                @foreach($pedido->cuentas->groupBy('product_id') as $productId => $cuentasGroup)
                    @php $producto = $cuentasGroup->first()->producto; @endphp
                    <div class="product-card scroll-animate">
                        <div class="product-header">
                            <img src="{{ $producto->imagen_display_url ?? 'https://placehold.co/80x80/02010a/FFFFFF?text=NP' }}" 
                                 alt="{{ $producto->nombre }}" class="product-image">
                            <div class="product-info">
                                <h4>{{ $producto->nombre }} (x{{ $cuentasGroup->count() }})</h4>
                                <p>{{ $producto->descripcion_corta ?? 'Cuenta premium de alta calidad' }}</p>
                            </div>
                        </div>
                        <div class="accounts-grid">
                            @foreach($cuentasGroup as $cuenta)
                            <div class="account-card">
                                <!-- ✅ MEJORA: Credenciales visibles directamente -->
                                <div class="account-field">
                                    <span><strong>Email:</strong> <span class="account-value" data-text="{{ $cuenta->email_usuario ?? 'N/A' }}"></span></span>
                                    <button class="copy-btn" data-clipboard-text="{{ $cuenta->email_usuario ?? '' }}"><i class="far fa-copy"></i></button>
                                </div>
                                <div class="account-field">
                                    <span><strong>Contraseña:</strong> <span class="account-value" data-text="{{ $cuenta->password ?? 'N/A' }}"></span></span>
                                    <button class="copy-btn" data-clipboard-text="{{ $cuenta->password ?? '' }}"><i class="far fa-copy"></i></button>
                                </div>
                                @if($cuenta->detalles)
                                <div class="account-field">
                                    <span><strong>Detalles:</strong> <span class="account-value" data-text="{{ $cuenta->detalles }}"></span></span>
                                    <button class="copy-btn" data-clipboard-text="{{ $cuenta->detalles }}"><i class="far fa-copy"></i></button>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="success-actions scroll-animate">
            <a href="{{ route('cuenta.index') }}" class="action-btn btn-primary"><i class="fas fa-history"></i><span>Ver mis Compras</span></a>
            <a href="{{ route('tienda.index') }}" class="action-btn btn-secondary"><i class="fas fa-store"></i><span>Seguir Comprando</span></a>
            <button id="download-receipt-btn" class="action-btn btn-secondary"><i class="fas fa-download"></i><span>Descargar Recibo</span></button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- ✅ MEJORA: Se añade la librería jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function fireConfetti() {
        var duration = 3 * 1000;
        var animationEnd = Date.now() + duration;
        var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 1000, colors: ['#9333ea', '#08d9d6', '#ff2e63', '#ffffff'] };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        var interval = setInterval(function() {
            var timeLeft = animationEnd - Date.now();
            if (timeLeft <= 0) return clearInterval(interval);
            
            var particleCount = 50 * (timeLeft / duration);
            confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
            confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
        }, 250);
    }
    setTimeout(fireConfetti, 500);

    const accountValues = document.querySelectorAll('.account-value');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const text = el.dataset.text;
                let i = 0;
                el.textContent = '';
                function typeWriter() {
                    if (i < text.length) {
                        el.textContent += text.charAt(i);
                        i++;
                        setTimeout(typeWriter, 15);
                    }
                }
                typeWriter();
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.5 });

    accountValues.forEach(el => {
        observer.observe(el);
    });

    document.querySelectorAll('.copy-btn').forEach(button => {
        button.addEventListener('click', function() {
            const textToCopy = this.dataset.clipboardText;
            const originalIcon = this.innerHTML;
            
            const textArea = document.createElement('textarea');
            textArea.value = textToCopy;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                this.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => { this.innerHTML = originalIcon; }, 1500);
            } catch (err) {
                console.error('Error al copiar:', err);
                this.innerHTML = '<i class="fas fa-times"></i>';
                 setTimeout(() => { this.innerHTML = originalIcon; }, 1500);
            }
            document.body.removeChild(textArea);
        });
    });

    const card = document.querySelector('.success-card');
    if (card && window.matchMedia('(min-width: 768px)').matches) {
        card.addEventListener('mousemove', (e) => {
            const { top, left, width, height } = card.getBoundingClientRect();
            const x = e.clientX - left - width / 2;
            const y = e.clientY - top - height / 2;
            const rotateX = (y / (height / 2)) * -4;
            const rotateY = (x / (width / 2)) * 4;
            requestAnimationFrame(() => {
                card.style.transform = `perspective(1500px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1500px) rotateX(0) rotateY(0)';
        });
    }

    // ✅ MEJORA: Lógica para Descargar Recibo en PDF
    document.getElementById('download-receipt-btn').addEventListener('click', function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const now = new Date();
        const formattedDate = now.toLocaleDateString('es-CO', { year: 'numeric', month: 'long', day: 'numeric' });
        const formattedTime = now.toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' });

        let y = 20; // Posición vertical inicial

        // --- Encabezado del PDF ---
        doc.setFont('Orbitron', 'bold');
        doc.setFontSize(22);
        doc.text("NEXT PLAY STREAMING", 105, y, { align: 'center' });
        y += 8;
        doc.setFont('Poppins', 'normal');
        doc.setFontSize(12);
        doc.text("Recibo de Compra", 105, y, { align: 'center' });
        y += 10;
        doc.setFontSize(9);
        doc.setTextColor(150);
        doc.text(`Generado: ${formattedDate} - ${formattedTime}`, 105, y, { align: 'center' });
        y += 15;
        doc.setLineWidth(0.5);
        doc.line(15, y, 195, y);
        y += 10;

        // --- Detalles del Pedido ---
        doc.setFont('Orbitron', 'bold');
        doc.setFontSize(14);
        doc.setTextColor(0);
        doc.text("Detalles del Pedido", 15, y);
        y += 8;
        doc.setFont('Poppins', 'normal');
        doc.setFontSize(10);
        
        document.querySelectorAll('.detail-item').forEach(item => {
            const label = item.querySelector('.detail-label').textContent.trim();
            const value = item.querySelector('.detail-value').textContent.trim();
            doc.setFont('Poppins', 'bold');
            doc.text(`${label}:`, 20, y);
            doc.setFont('Poppins', 'normal');
            doc.text(value, 60, y);
            y += 7;
        });

        // --- Productos Adquiridos ---
        y += 10;
        doc.setLineWidth(0.2);
        doc.line(15, y, 195, y);
        y += 10;
        doc.setFont('Orbitron', 'bold');
        doc.setFontSize(14);
        doc.text("Productos Adquiridos", 15, y);
        y += 8;

        document.querySelectorAll('.product-card').forEach(product => {
            const productName = product.querySelector('h4').textContent.trim();
            doc.setFont('Poppins', 'bold');
            doc.setFontSize(11);
            doc.text(productName, 20, y);
            y += 6;
            
            product.querySelectorAll('.account-card').forEach((account, index) => {
                doc.setFont('Poppins', 'normal');
                doc.setFontSize(10);
                const email = account.querySelector('[data-text*="@"]').dataset.text;
                const password = account.querySelector('[data-text]:not([data-text*="@"])').dataset.text;
                let detailsText = '';
                const detailsEl = account.querySelector('[data-text*="Detalles"]');
                if (detailsEl) detailsText = detailsEl.dataset.text;

                doc.text(`   - Email: ${email}`, 25, y);
                y += 5;
                doc.text(`   - Contraseña: ${password}`, 25, y);
                y += 5;
                if(detailsText) {
                    doc.text(`   - Detalles: ${detailsText}`, 25, y);
                    y += 5;
                }
            });
            y += 5;
        });

        // --- Pie de página del PDF ---
        const pageCount = doc.internal.getNumberOfPages();
        for(let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(150);
            doc.text(`© ${new Date().getFullYear()} NPStreaming. Todos los derechos reservados.`, 105, 285, { align: 'center' });
            doc.text(`Página ${i} de ${pageCount}`, 195, 285, { align: 'right' });
        }

        doc.save(`recibo_NP-{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}.pdf`);
    });
});
</script>
@endpush
