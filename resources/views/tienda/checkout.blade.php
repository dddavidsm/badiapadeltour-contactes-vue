<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - Badia Padel Tour</title>
    <link rel="icon" href="/favicon.png">
    
    <!-- Fonts Gopher -->
    <style>
        @font-face {
            font-family: 'Gopher';
            src: url('/fonts/Gopher/Gopher-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Gopher';
            src: url('/fonts/Gopher/Gopher-Bold.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Gopher', sans-serif;
            background-color: #111111;
            color: #ffffff;
        }
        
        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }
        
        .checkout-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .checkout-title {
            font-size: 3rem;
            font-weight: 800;
            color: #C9FF00;
            margin-bottom: 12px;
        }
        
        .checkout-steps {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 32px;
        }
        
        .step {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            background-color: #C9FF00;
            color: #000000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
        }
        
        .step-number.inactive {
            background-color: #2a2a2a;
            color: #666666;
        }
        
        .step-label {
            font-weight: 700;
            color: #ffffff;
        }
        
        .step-label.inactive {
            color: #666666;
        }
        
        .checkout-layout {
            display: grid;
            grid-template-columns: 1fr 450px;
            gap: 40px;
        }
        
        .checkout-form {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }
        
        .form-section {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 32px;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #C9FF00;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .form-grid.single {
            grid-template-columns: 1fr;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-label {
            font-weight: 700;
            color: #C9FF00;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .form-input,
        .form-select,
        .form-textarea {
            background-color: #ffffff;
            border: 1px solid #3a3a3a;
            color: #000000;
            padding: 14px 16px;
            border-radius: 8px;
            font-family: 'Gopher', sans-serif;
            font-size: 1rem;
            transition: all 0.2s;
        }
        
        .form-input::placeholder,
        .form-select::placeholder,
        .form-textarea::placeholder {
            color: #999999;
        }
        
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #C9FF00;
            box-shadow: 0 0 0 2px rgba(201, 255, 0, 0.1);
        }
        
        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .payment-methods {
            display: grid;
            gap: 16px;
        }
        
        .payment-option {
            background-color: #2a2a2a;
            border: 2px solid #3a3a3a;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .payment-option:hover {
            border-color: #C9FF00;
            background-color: #333333;
        }
        
        .payment-option.selected {
            border-color: #C9FF00;
            background-color: #1f2a0d;
        }
        
        .payment-radio {
            width: 24px;
            height: 24px;
            accent-color: #C9FF00;
        }
        
        .payment-info {
            flex: 1;
        }
        
        .payment-name {
            font-weight: 700;
            color: #ffffff;
            font-size: 1.1rem;
            margin-bottom: 4px;
        }
        
        .payment-desc {
            color: #999999;
            font-size: 0.9rem;
        }
        
        .payment-icon {
            font-size: 2rem;
        }
        
        .card-details {
            display: grid;
            gap: 20px;
            margin-top: 20px;
            padding: 20px;
            background-color: #1a1a1a;
            border-radius: 8px;
        }
        
        .card-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 16px;
        }
        
        .order-summary {
            position: sticky;
            top: 100px;
            height: fit-content;
        }
        
        .summary-card {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 32px;
        }
        
        .summary-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #C9FF00;
            margin-bottom: 24px;
        }
        
        .summary-items {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid #2a2a2a;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .item-info {
            flex: 1;
        }
        
        .item-name-small {
            color: #ffffff;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .item-qty {
            color: #999999;
            font-size: 0.85rem;
        }
        
        .item-price-small {
            color: #C9FF00;
            font-weight: 700;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
        }
        
        .summary-label {
            color: #999999;
            font-size: 1rem;
            font-weight: 600;
        }
        
        .summary-value {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 700;
        }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 24px;
            margin-top: 16px;
            border-top: 2px solid #C9FF00;
        }
        
        .total-label {
            font-size: 1.3rem;
            font-weight: 800;
            color: #ffffff;
        }
        
        .total-amount {
            font-size: 2.2rem;
            font-weight: 800;
            color: #C9FF00;
        }
        
        .btn-place-order {
            width: 100%;
            background-color: #C9FF00;
            color: #000000;
            border: none;
            padding: 20px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.2rem;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 24px;
            transition: all 0.2s;
        }
        
        .btn-place-order:hover {
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(201, 255, 0, 0.3);
        }

        .btn-back-cart {
            width: 100%;
            background-color: transparent;
            color: #C9FF00;
            border: 2px solid #C9FF00;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            cursor: pointer;
            margin-bottom: 16px;
            transition: all 0.2s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-back-cart:hover {
            background-color: #C9FF00;
            color: #000000;
            transform: translateY(-2px);
        }
        
        .security-note {
            background-color: #2a2a2a;
            border-radius: 8px;
            padding: 16px;
            margin-top: 16px;
            text-align: center;
        }
        
        .security-note p {
            color: #999999;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        @media (max-width: 1024px) {
            .checkout-layout {
                grid-template-columns: 1fr;
            }
            
            .order-summary {
                position: static;
            }
        }
        
        @media (max-width: 768px) {
            .checkout-title {
                font-size: 2rem;
            }
            
            .checkout-steps {
                flex-direction: column;
                gap: 16px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .card-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-header />

    <div class="checkout-container">
        <div class="checkout-header">
            <h1 class="checkout-title">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Finalizar Compra
            </h1>
            
            <div class="checkout-steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <span class="step-label">Datos de Envío</span>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <span class="step-label">Método de Pago</span>
                </div>
                <div class="step">
                    <div class="step-number inactive">3</div>
                    <span class="step-label inactive">Confirmación</span>
                </div>
            </div>
        </div>

        <form action="{{ route('tienda.pedido.store') }}" method="POST">
            @csrf
            <div class="checkout-layout">
                <!-- Formulario -->
                <div class="checkout-form">
                    <!-- Datos de Contacto -->
                    <section class="form-section">
                        <h2 class="section-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            Datos de Contacto
                        </h2>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Nombre *</label>
                                <input type="text" class="form-input" value="{{ auth()->user()->name ?? '' }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Apellidos *</label>
                                <input type="text" class="form-input" required>
                            </div>
                            
                            <div class="form-group full-width">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-input" value="{{ auth()->user()->email ?? '' }}" required>
                            </div>
                            
                            <div class="form-group full-width">
                                <label class="form-label">Teléfono *</label>
                                <input type="tel" class="form-input" placeholder="+34 600 000 000" required>
                            </div>
                        </div>
                    </section>

                    <!-- Dirección de Envío -->
                    <section class="form-section">
                        <h2 class="section-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                            Dirección de Envío
                        </h2>
                        
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label class="form-label">Dirección *</label>
                                <input type="text" class="form-input" placeholder="Calle, número, piso, puerta" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Código Postal *</label>
                                <input type="text" class="form-input" placeholder="07000" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Ciudad *</label>
                                <input type="text" class="form-input" placeholder="Palma de Mallorca" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Provincia *</label>
                                <select class="form-select" required>
                                    <option value="">Selecciona una provincia</option>
                                    <option value="illes-balears">Illes Balears</option>
                                    <option value="madrid">Madrid</option>
                                    <option value="barcelona">Barcelona</option>
                                    <option value="valencia">Valencia</option>
                                    <option value="sevilla">Sevilla</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">País *</label>
                                <select class="form-select" required>
                                    <option value="españa">España</option>
                                </select>
                            </div>
                            
                            <div class="form-group full-width">
                                <label class="form-label">Notas del pedido (opcional)</label>
                                <textarea class="form-textarea" placeholder="Instrucciones especiales para la entrega..."></textarea>
                            </div>
                        </div>
                    </section>

                    <!-- Método de Pago -->
                    <section class="form-section">
                        <h2 class="section-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            Método de Pago
                        </h2>
                        
                        <div class="payment-methods">
                            <label class="payment-option selected">
                                <input type="radio" name="payment" value="card" class="payment-radio" checked>
                                <div class="payment-info">
                                    <div class="payment-name">Tarjeta de Crédito / Débito</div>
                                    <div class="payment-desc">Visa, Mastercard, American Express</div>
                                </div>
                                <span class="payment-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                        <line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                </span>
                            </label>
                            
                            <label class="payment-option">
                                <input type="radio" name="payment" value="paypal" class="payment-radio">
                                <div class="payment-info">
                                    <div class="payment-name">PayPal</div>
                                    <div class="payment-desc">Pago seguro con tu cuenta PayPal</div>
                                </div>
                                <span class="payment-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M20.067 8.478c.492.88.556 2.014.3 3.327-.74 3.806-3.276 5.12-6.514 5.12h-.5a.805.805 0 0 0-.794.68l-.04.22-.63 3.993-.032.17a.804.804 0 0 1-.794.679H7.72a.483.483 0 0 1-.477-.558L9.88 7.63a.956.956 0 0 1 .942-.807h2.993c1.023 0 1.97.105 2.8.336 1.276.356 2.215 1.029 2.452 2.319z"/>
                                        <path d="M10.736 8.219c.096-.558.515-.807.942-.807h2.993c1.023 0 1.97.105 2.8.336 2.647.738 3.41 3.374 2.577 6.3-.74 3.806-3.276 5.12-6.514 5.12h-.5a.805.805 0 0 0-.794.68l-.67 4.163a.683.683 0 0 1-.674.578H7.72a.483.483 0 0 1-.477-.558l2.637-15.479c.096-.558.515-.807.942-.807h2.993" opacity="0.7"/>
                                    </svg>
                                </span>
                            </label>
                            
                            <label class="payment-option">
                                <input type="radio" name="payment" value="transfer" class="payment-radio">
                                <div class="payment-info">
                                    <div class="payment-name">Transferencia Bancaria</div>
                                    <div class="payment-desc">Recibirás las instrucciones por email</div>
                                </div>
                                <span class="payment-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                </span>
                            </label>
                        </div>
                        
                        <div class="card-details" id="card-details">
                            <div class="form-group">
                                <label class="form-label">Número de Tarjeta *</label>
                                <input type="text" class="form-input" placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>
                            
                            <div class="card-row">
                                <div class="form-group">
                                    <label class="form-label">Titular *</label>
                                    <input type="text" class="form-input" placeholder="NOMBRE APELLIDOS">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Fecha *</label>
                                    <input type="text" class="form-input" placeholder="MM/AA">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">CVV *</label>
                                    <input type="text" class="form-input" placeholder="123" maxlength="3">
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Resumen del Pedido -->
                <aside class="order-summary">
                    <div class="summary-card">
                        <h2 class="summary-title">Resumen del Pedido</h2>
                        
                        <div class="summary-items">
                            @php 
                                $carrito = session()->get('carrito', []);
                                $total = 0;
                            @endphp
                            
                            @forelse($carrito as $id => $item)
                                @php $total += $item['precio'] * $item['cantidad']; @endphp
                                <div class="summary-item">
                                    <div class="item-info">
                                        <div class="item-name-small">{{ $item['nombre'] }}</div>
                                        <div class="item-qty">Cantidad: {{ $item['cantidad'] }}</div>
                                    </div>
                                    <div class="item-price-small">{{ number_format($item['precio'] * $item['cantidad'], 2, ',', '.') }} €</div>
                                </div>
                            @empty
                                <p style="color: #999;">No hay productos en el carrito</p>
                            @endforelse
                        </div>
                        
                        <div class="summary-row">
                            <span class="summary-label">Subtotal</span>
                            <span class="summary-value">{{ number_format($total, 2, ',', '.') }} €</span>
                        </div>
                        
                        <div class="summary-row">
                            <span class="summary-label">Envío</span>
                            <span class="summary-value">
                                @if($total >= 50)
                                    Gratis
                                @else
                                    5,95 €
                                @endif
                            </span>
                        </div>
                        
                        <div class="summary-total">
                            <span class="total-label">Total</span>
                            <span class="total-amount">{{ number_format($total >= 50 ? $total : $total + 5.95, 2, ',', '.') }} €</span>
                        </div>
                        
                        <a href="{{ route('tienda.carrito') }}" class="btn-back-cart">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            Editar Carrito
                        </a>
                        
                        <button type="submit" class="btn-place-order">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            Realizar Pedido
                        </button>
                        
                        <div class="security-note">
                            <p>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                Pago 100% seguro y encriptado
                            </p>
                        </div>
                    </div>
                </aside>
            </div>
        </form>
    </div>

    <x-footer />
    
    <script>
        // Toggle card details visibility
        const paymentOptions = document.querySelectorAll('.payment-option');
        const cardDetails = document.getElementById('card-details');
        
        paymentOptions.forEach(option => {
            option.addEventListener('click', function() {
                paymentOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                
                const radio = this.querySelector('input[type="radio"]');
                if (radio.value === 'card') {
                    cardDetails.style.display = 'grid';
                } else {
                    cardDetails.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>