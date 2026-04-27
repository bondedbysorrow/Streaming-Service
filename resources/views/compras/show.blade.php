@extends('layouts.app')

@section('content')
<div class="purchase-success-container">
    <div class="success-card">
        <div class="success-icon-wrapper">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
        </div>
        <h1 class="success-title">¡Compra Realizada con Éxito!</h1>
        <p class="success-subtitle">Tus cuentas han sido asignadas a tu perfil. Serás redirigido en <strong>5</strong> segundos...</p>
        
        <div class="purchased-accounts">
            <h3 class="accounts-title"><i class="fas fa-key"></i> Cuentas Adquiridas</h3>
            <div class="accounts-list">
                @forelse($pedido->cuentas as $cuenta)
                    <div class="account-details">
                        <div class="product-info">
                            <img src="{{ $cuenta->product->imagen_url ?? asset('images/placeholder-product.png') }}" alt="{{ $cuenta->product->nombre }}" class="product-image-small">
                            <span class="product-name">{{ $cuenta->product->nombre }}</span>
                        </div>
                        <div class="account-credentials">
                            <div><span class="credential-label">Email:</span> <span class="credential-value">{{ $cuenta->email_usuario }}</span></div>
                            <div><span class="credential-label">Clave:</span> <span class="credential-value">{{ $cuenta->password }}</span></div>
                        </div>
                    </div>
                @empty
                    <p>No se encontraron detalles de las cuentas.</p>
                @endforelse
            </div>
        </div>

        <a href="{{ route('cuenta.index') }}" class="btn-primary redirect-now-btn"><i class="fas fa-user-circle"></i> Ir a Mi Cuenta Ahora</a>
    </div>
</div>
@endsection

@push('styles')
<style>
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes icon-pop { 0% { transform: scale(0); } 80% { transform: scale(1.2); } 100% { transform: scale(1); } }
    .purchase-success-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 80vh;
        padding: 2rem;
        animation: fadeIn 0.8s ease-out;
    }
    .success-card {
        background: linear-gradient(145deg, #16213e 0%, rgba(26, 26, 46, 0.95) 100%);
        border: 2px solid rgba(139, 92, 246, 0.2);
        border-radius: 25px;
        padding: 3rem;
        text-align: center;
        max-width: 800px;
        width: 100%;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }
    .success-icon-wrapper {
        margin: 0 auto 1.5rem auto;
        width: 100px;
        height: 100px;
        background: rgba(139, 92, 246, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .success-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        animation: icon-pop 0.6s ease-out forwards;
    }
    .success-title {
        font-size: 2.5rem;
        font-weight: 900;
        color: white;
        margin-bottom: 1rem;
    }
    .success-subtitle {
        font-size: 1.1rem;
        color: #9ca3af;
        margin-bottom: 2.5rem;
    }
    .purchased-accounts {
        margin-bottom: 2.5rem;
        text-align: left;
    }
    .accounts-title {
        color: #a78bfa;
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid rgba(139, 92, 246, 0.2);
        padding-bottom: 0.75rem;
    }
    .accounts-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .account-details {
        background: rgba(139, 92, 246, 0.05);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 15px;
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .product-info { display: flex; align-items: center; gap: 1rem; font-weight: 600; color: white; }
    .product-image-small { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; }
    .account-credentials { display: flex; flex-direction: column; align-items: flex-start; gap: 0.25rem; font-size: 0.9rem; }
    .credential-label { color: #9ca3af; }
    .credential-value { color: white; font-weight: 600; }
    .redirect-now-btn {
        padding: 1rem 2rem;
        border-radius: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        border: none;
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }
    .redirect-now-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let seconds = 5;
        const countdownElement = document.querySelector('.success-subtitle strong');
        
        const interval = setInterval(() => {
            seconds--;
            if (countdownElement) {
                countdownElement.textContent = seconds;
            }
            if (seconds <= 0) {
                clearInterval(interval);
                window.location.href = "{{ route('cuenta.index') }}";
            }
        }, 1000);
    });
</script>
@endpush
