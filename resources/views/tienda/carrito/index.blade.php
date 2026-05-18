<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carrito - Badia Padel Tour</title>
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
        
        .cart-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }
        
        .cart-header {
            margin-bottom: 40px;
        }
        
        .cart-title {
            font-size: 3rem;
            font-weight: 800;
            color: #C9FF00;
            margin-bottom: 8px;
        }
        
        .cart-subtitle {
            color: #999999;
            font-size: 1rem;
        }
        
        .cart-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 40px;
        }
        
        .cart-items {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .cart-item {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 24px;
            display: grid;
            grid-template-columns: 120px 1fr auto;
            gap: 24px;
            align-items: center;
            transition: all 0.3s;
        }
        
        .cart-item:hover {
            border-color: #C9FF00;
            box-shadow: 0 4px 12px rgba(201, 255, 0, 0.1);
        }
        
        .item-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            background-color: #2a2a2a;
        }
        
        .item-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .item-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
        }
        
        .item-category {
            color: #C9FF00;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .item-price {
            font-size: 1.5rem;
            font-weight: 800;
            color: #C9FF00;
            margin-top: 8px;
        }
        
        .item-actions {
            display: flex;
            flex-direction: column;
            gap: 16px;
            align-items: flex-end;
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 16px;
            background-color: #2a2a2a;
            padding: 8px 16px;
            border-radius: 8px;
        }
        
        .quantity-btn {
            background-color: transparent;
            color: #C9FF00;
            border: none;
            font-size: 1.5rem;
            font-weight: 700;
            cursor: pointer;
            padding: 4px 12px;
            transition: all 0.2s;
        }
        
        .quantity-btn:hover {
            color: #ffffff;
            transform: scale(1.2);
        }
        
        .quantity-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #000000;
            min-width: 40px;
            text-align: center;
            background-color: #ffffff;
            border: 2px solid #C9FF00;
            border-radius: 6px;
            padding: 4px 8px;
            font-family: 'Gopher', sans-serif;
        }
        
        .quantity-value:focus {
            outline: none;
            border-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(201, 255, 0, 0.2);
        }
        
        /* Ocultar spinners del input number */
        .quantity-value::-webkit-outer-spin-button,
        .quantity-value::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        .quantity-value[type=number] {
            -moz-appearance: textfield;
        }
        
        .item-subtotal {
            font-size: 1.8rem;
            font-weight: 800;
            color: #ffffff;
        }
        
        .btn-remove {
            background-color: transparent;
            color: #ff4444;
            border: 2px solid #ff4444;
            padding: 8px 20px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
        }
        
        .btn-remove:hover {
            background-color: #ff4444;
            color: #ffffff;
        }
        
        .cart-summary {
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
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #2a2a2a;
        }
        
        .summary-row:last-child {
            border-bottom: none;
            padding-top: 24px;
            margin-top: 8px;
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
            font-size: 2rem;
            font-weight: 800;
            color: #C9FF00;
        }
        
        .btn-checkout {
            width: 100%;
            background-color: #C9FF00;
            color: #000000;
            border: none;
            padding: 18px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 24px;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        
        .btn-checkout:hover {
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(201, 255, 0, 0.2);
        }
        
        .btn-continue {
            width: 100%;
            background-color: transparent;
            color: #C9FF00;
            border: 2px solid #C9FF00;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 12px;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        
        .btn-continue:hover {
            background-color: #C9FF00;
            color: #000000;
        }
        
        .btn-clear {
            width: 100%;
            background-color: transparent;
            color: #ff4444;
            border: 2px solid #ff4444;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 12px;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        
        .btn-clear:hover {
            background-color: #ff4444;
            color: #ffffff;
        }
        
        .empty-cart {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 16px;
            padding: 80px 40px;
            text-align: center;
        }
        
        .empty-icon {
            font-size: 5rem;
            color: #666666;
            margin-bottom: 24px;
        }
        
        .empty-title {
            font-size: 2rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 12px;
        }
        
        .empty-text {
            color: #999999;
            font-size: 1.1rem;
            margin-bottom: 32px;
        }
        
        .auth-notice {
            background-color: #2a2a2a;
            border: 1px solid #C9FF00;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            text-align: center;
        }
        
        .auth-notice p {
            color: #cccccc;
            margin-bottom: 12px;
        }
        
        .auth-notice a {
            color: #C9FF00;
            text-decoration: underline;
            font-weight: 700;
        }
        
        @media (max-width: 1024px) {
            .cart-layout {
                grid-template-columns: 1fr;
            }
            
            .cart-summary {
                position: static;
            }
        }
        
        @media (max-width: 768px) {
            .cart-title {
                font-size: 2rem;
            }
            
            .cart-item {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .item-image {
                margin: 0 auto;
            }
            
            .item-actions {
                align-items: center;
            }
        }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-header />

    <div class="cart-container">
        <div class="cart-header">
            <h1 class="cart-title" style="display: flex; align-items: center; gap: 12px;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M7 6H21L19 14H8L7 6Z" stroke="#C9FF00" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="9" cy="18" r="1.3" stroke="#C9FF00" stroke-width="1.4"/>
                    <circle cx="17" cy="18" r="1.3" stroke="#C9FF00" stroke-width="1.4"/>
                    <path d="M7 6L6 3H3" stroke="#C9FF00" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Mi Carrito
            </h1>
            <p class="cart-subtitle">
                @if(!empty($carrito))
                    {{ count($carrito) }} {{ count($carrito) == 1 ? 'producto' : 'productos' }} en tu carrito
                @else
                    Tu carrito está vacío
                @endif
            </p>
        </div>

        @if(!empty($carrito))
            <div class="cart-layout">
                <!-- Lista de Productos -->
                <div class="cart-items">
                    @php $total = 0; @endphp
                    @foreach($carrito as $id => $item)
                        @php 
                            $subtotal = $item['precio'] * $item['cantidad'];
                            $total += $subtotal;
                        @endphp
                        <div class="cart-item">
                            @php
                                $imagePath = $item['imagen'] ?? null;
                                if ($imagePath) {
                                    $isFullUrl = str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://');
                                    $resolvedImage = $isFullUrl ? $imagePath : asset($imagePath);
                                } else {
                                    $resolvedImage = 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=200&h=200&fit=crop';
                                }
                            @endphp
                            <img src="{{ $resolvedImage }}" 
                                 class="item-image" 
                                 alt="{{ $item['nombre'] }}">
                            
                            <div class="item-details">
                                <span class="item-category">Producto</span>
                                <h3 class="item-name">{{ $item['nombre'] }}</h3>
                                <p class="item-price">{{ number_format($item['precio'], 2) }} € / ud</p>
                            </div>
                            
                            <div class="item-actions">
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn btn-decrease" data-id="{{ $id }}" data-current="{{ $item['cantidad'] }}">−</button>
                                    
                                    <input type="number" class="quantity-value" data-id="{{ $id }}" value="{{ $item['cantidad'] }}" min="1" max="999">
                                    
                                    <button type="button" class="quantity-btn btn-increase" data-id="{{ $id }}" data-current="{{ $item['cantidad'] }}">+</button>
                                </div>
                                
                                <div class="item-subtotal" data-id="{{ $id }}" data-price="{{ $item['precio'] }}">{{ number_format($subtotal, 2) }} €</div>
                                
                                <form method="POST" action="{{ route('tienda.carrito.remove', $id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-remove">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Resumen del Pedido -->
                <aside class="cart-summary">
                    <div class="summary-card">
                        <h2 class="summary-title">Resumen del Pedido</h2>
                        
                        <div class="summary-row">
                            <span class="summary-label">Subtotal (<span id="product-count">{{ count($carrito) }}</span> productos)</span>
                            <span class="summary-value" id="subtotal-value">{{ number_format($total, 2, ',', '.') }} €</span>
                        </div>
                        
                        <div class="summary-row">
                            <span class="summary-label">Envío</span>
                            <span class="summary-value" id="shipping-value">
                                @if($total >= 50)
                                    Gratis
                                @else
                                    5,95 €
                                @endif
                            </span>
                        </div>
                        
                        <div class="summary-row">
                            <span class="summary-label">Total</span>
                            <span class="summary-total" id="total-value">{{ number_format($total >= 50 ? $total : $total + 5.95, 2, ',', '.') }} €</span>
                        </div>
                        
                        @auth
                            <a href="{{ route('tienda.checkout') }}" class="btn-checkout">
                                Proceder al Pago
                            </a>
                        @else
                            <div class="auth-notice">
                                <p>Inicia sesión para finalizar tu compra</p>
                                <a href="{{ route('login') }}">Iniciar Sesión</a> o 
                                <a href="{{ route('register') }}">Registrarse</a>
                            </div>
                            <a href="{{ route('login') }}" class="btn-checkout">
                                Iniciar Sesión
                            </a>
                        @endauth
                        
                        <a href="{{ route('tienda.productos') }}" class="btn-continue" style="display: inline-flex; align-items: center; gap: 8px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M15 18L9 12L15 6" stroke="#C9FF00" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Seguir Comprando
                        </a>
                        
                        <a href="{{ route('tienda.carrito.clear') }}" class="btn-clear">
                            Vaciar Carrito
                        </a>
                    </div>
                </aside>
            </div>
        @else
            <div class="empty-cart">
                <div class="empty-icon" style="display: inline-flex; align-items: center; justify-content: center; width: 72px; height: 72px; border-radius: 50%; background: #1a1a1a;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M7 6H21L19 14H8L7 6Z" stroke="#C9FF00" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="9" cy="18" r="1.3" stroke="#C9FF00" stroke-width="1.4"/>
                        <circle cx="17" cy="18" r="1.3" stroke="#C9FF00" stroke-width="1.4"/>
                        <path d="M7 6L6 3H3" stroke="#C9FF00" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h2 class="empty-title">Tu carrito está vacío</h2>
                <p class="empty-text">Descubre nuestros productos y añade algo especial a tu carrito</p>
                <a href="{{ route('tienda.productos') }}" class="btn-checkout" style="max-width: 400px; margin: 0 auto;">
                    Explorar Productos
                </a>
            </div>
        @endif
    </div>

    <x-footer />

    <script>
        // Optimización: Actualizar cantidad sin recargar la página
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Función para recalcular el total del carrito
        function updateCartSummary() {
            let totalPrice = 0;
            
            // Sumar todos los subtotales
            document.querySelectorAll('.item-subtotal').forEach(el => {
                const text = el.textContent.trim();
                const price = parseFloat(text.replace(/[^0-9,.-]/g, '').replace(',', '.'));
                totalPrice += isNaN(price) ? 0 : price;
            });
            
            // Actualizar subtotal
            const subtotalValue = document.getElementById('subtotal-value');
            if (subtotalValue) {
                subtotalValue.textContent = formatPrice(totalPrice) + ' €';
            }
            
            // Actualizar envío
            const shippingValue = document.getElementById('shipping-value');
            if (shippingValue) {
                shippingValue.textContent = totalPrice >= 50 ? 'Gratis' : '5,95 €';
            }
            
            // Actualizar total
            const totalValue = document.getElementById('total-value');
            if (totalValue) {
                const finalTotal = totalPrice >= 50 ? totalPrice : totalPrice + 5.95;
                totalValue.textContent = formatPrice(finalTotal) + ' €';
            }
        }
        
        // Función para formatear precios con locale español
        function formatPrice(price) {
            return price.toFixed(2).replace('.', ',');
        }
        
        // Función auxiliar para actualizar cantidad
        async function updateQuantity(id, newQty) {
            const quantityInput = document.querySelector(`.quantity-value[data-id="${id}"]`);
            const subtotalDiv = document.querySelector(`.item-subtotal[data-id="${id}"]`);
            const price = parseFloat(subtotalDiv.dataset.price);
            
            // Validar cantidad
            if (newQty < 1) {
                newQty = 1;
                quantityInput.value = 1;
            }
            
            // Enviar actualización al servidor para validar stock
            try {
                const response = await fetch(`/tienda/carrito/update/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ cantidad: newQty })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Actualización exitosa: actualizar UI
                    quantityInput.value = newQty;
                    const newSubtotal = (newQty * price).toFixed(2);
                    subtotalDiv.textContent = formatPrice(parseFloat(newSubtotal)) + ' €';
                    
                    // Recalcular resumen del carrito
                    updateCartSummary();
                } else {
                    // Error de stock u otro
                    alert('⚠️ ' + (data.error || 'No se pudo actualizar la cantidad'));
                    // Restaurar valor anterior
                    quantityInput.value = parseInt(quantityInput.value);
                }
            } catch (error) {
                console.error('Error al actualizar cantidad:', error);
                alert('Error al actualizar la cantidad: ' + error.message);
            }
        }
        
        // Manejador para botones +/-
        document.querySelectorAll('.btn-increase, .btn-decrease').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                
                const id = btn.dataset.id;
                const isIncrease = btn.classList.contains('btn-increase');
                const quantityInput = document.querySelector(`.quantity-value[data-id="${id}"]`);
                
                let currentQty = parseInt(quantityInput.value);
                let newQty = isIncrease ? currentQty + 1 : Math.max(1, currentQty - 1);
                
                await updateQuantity(id, newQty);
            });
        });
        
        // Manejador para cambios directos en el input
        document.querySelectorAll('.quantity-value').forEach(input => {
            input.addEventListener('change', async (e) => {
                const id = input.dataset.id;
                let newQty = parseInt(input.value) || 1;
                
                // Validar que sea un número válido
                if (newQty < 1) {
                    newQty = 1;
                }
                if (newQty > 999) {
                    newQty = 999;
                }
                
                await updateQuantity(id, newQty);
            });
            
            // Prevenir entrada de caracteres no numéricos
            input.addEventListener('keypress', (e) => {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>