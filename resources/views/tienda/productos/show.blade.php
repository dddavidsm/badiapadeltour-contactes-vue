<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $producto->nombre }} - Badia Padel Tour</title>
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

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .product-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 24px;
        }
        
        .breadcrumb {
            display: flex;
            gap: 8px;
            margin-bottom: 32px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .breadcrumb a {
            color: #999999;
            text-decoration: none;
            transition: color 0.2s;
            font-size: 0.9rem;
        }
        
        .breadcrumb a:hover {
            color: #C9FF00;
        }
        
        .breadcrumb .separator {
            color: #666666;
        }
        
        .breadcrumb .current {
            color: #C9FF00;
            font-weight: 700;
        }
        
        .alert {
            background-color: #1a1a1a;
            border-left: 4px solid #C9FF00;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            color: #ffffff;
        }
        
        .product-main {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-bottom: 80px;
        }
        
        .product-image-section {
            position: sticky;
            top: 100px;
            height: fit-content;
        }
        
        .product-image-container {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            aspect-ratio: 1 / 1;
        }
        
        .gallery-main {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        
        .gallery-main img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .gallery-thumbnails {
            display: flex;
            gap: 8px;
            justify-content: center;
            padding: 16px 0;
            margin-top: 16px;
        }
        
        .thumbnail {
            width: 80px;
            height: 80px;
            border: 2px solid #2a2a2a;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s;
            background-color: #1a1a1a;
        }
        
        .thumbnail:hover {
            border-color: #C9FF00;
            transform: scale(1.05);
        }
        
        .thumbnail.active {
            border-color: #C9FF00;
            box-shadow: 0 0 0 2px rgba(201, 255, 0, 0.2);
        }
        
        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666666;
            font-size: 5rem;
        }
        
        .product-badges {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            gap: 8px;
            flex-direction: column;
            align-items: flex-start;
        }
        
        .badge {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .badge-nuevo {
            background-color: #ff4444;
            color: #ffffff;
        }
        
        .badge-destacado {
            background-color: #C9FF00;
            color: #000000;
        }
        
        .product-info-section {
            padding: 20px 0;
        }
        
        .product-category-label {
            color: #C9FF00;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 12px;
            display: block;
        }
        
        .product-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 16px;
            line-height: 1.2;
        }
        
        .product-price {
            font-size: 3rem;
            font-weight: 800;
            color: #C9FF00;
            margin-bottom: 32px;
        }
        
        .product-description-section {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
        }
        
        .product-description-section h3 {
            color: #C9FF00;
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 12px;
        }
        
        .product-description {
            color: #cccccc;
            line-height: 1.7;
            font-size: 1rem;
        }
        
        .product-stock-info {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .stock-label {
            font-weight: 700;
            color: #ffffff;
        }
        
        .stock-badge {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.9rem;
        }
        
        .stock-success {
            background-color: #28a745;
            color: #ffffff;
        }
        
        .stock-warning {
            background-color: #ffc107;
            color: #000000;
        }
        
        .stock-danger {
            background-color: #dc3545;
            color: #ffffff;
        }
        
        .product-actions {
            display: flex;
            gap: 16px;
            margin-bottom: 32px;
        }
        
        .btn-add-cart {
            flex: 1;
            background-color: #C9FF00;
            color: #000000;
            border: none;
            padding: 18px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-add-cart:hover {
            opacity: 0.85;
            transform: translateY(-2px);
        }
        
        .btn-add-cart:disabled {
            background-color: #3a3a3a;
            color: #666666;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-back {
            background-color: transparent;
            color: #C9FF00;
            border: 2px solid #C9FF00;
            padding: 18px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-back:hover {
            background-color: #C9FF00;
            color: #000000;
        }
        
        .product-features {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 24px;
        }
        
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 0;
            border-bottom: 1px solid #2a2a2a;
        }
        
        .feature-item:last-child {
            border-bottom: none;
        }
        
        .feature-icon {
            font-size: 1.5rem;
            color: #C9FF00;
        }
        
        .feature-text {
            color: #cccccc;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        
        .related-section {
            margin-top: 80px;
        }
        
        .section-title {
            font-size: 2rem;
            font-weight: 800;
            color: #C9FF00;
            margin-bottom: 32px;
        }
        
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }
        
        .related-card {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .related-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(201, 255, 0, 0.1);
            border-color: #C9FF00;
        }
        
        .related-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background-color: #2a2a2a;
        }
        
        .related-info {
            padding: 20px;
        }
        
        .related-name {
            color: #ffffff;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.3;
        }
        
        .related-price {
            color: #C9FF00;
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 12px;
        }
        
        .btn-related {
            display: block;
            background-color: transparent;
            color: #C9FF00;
            border: 2px solid #C9FF00;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn-related:hover {
            background-color: #C9FF00;
            color: #000000;
        }
        
        @media (max-width: 1024px) {
            .product-main {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            
            .product-image-section {
                position: static;
            }
        }
        
        /* Botón del carrito flotante */
        .cart-button {
            position: fixed;
            bottom: 32px;
            right: 32px;
            width: 64px;
            height: 64px;
            background-color: #C9FF00;
            color: #000000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(201, 255, 0, 0.3);
            transition: all 0.3s;
            z-index: 999;
        }
        
        .cart-button:hover {
            transform: scale(1.1) translateY(-4px);
            box-shadow: 0 8px 20px rgba(201, 255, 0, 0.5);
            background-color: #ffffff;
        }
        
        .cart-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background-color: #ff0000;
            color: #ffffff;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 800;
            border: 2px solid #111111;
        }

        @media (max-width: 768px) {
            .product-title {
                font-size: 1.8rem;
            }
            
            .product-price {
                font-size: 2.2rem;
            }
            
            .product-actions {
                flex-direction: column;
            }
            
            .related-grid {
                grid-template-columns: 1fr;
            }

            .cart-button {
                bottom: 24px;
                right: 24px;
                width: 56px;
                height: 56px;
                font-size: 1.5rem;
            }
        }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-header />

    <div class="product-container">
        @if(session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="{{ route('tienda.index') }}">Tienda</a>
            <span class="separator">/</span>
            <a href="{{ route('tienda.productos') }}">Productos</a>
            @if($producto->categoria)
                <span class="separator">/</span>
                <a href="{{ route('tienda.productos', ['categoria' => $producto->categoria_id]) }}">{{ $producto->categoria->nombre }}</a>
            @endif
            <span class="separator">/</span>
            <span class="current">{{ $producto->nombre }}</span>
        </nav>

        <!-- Producto Principal -->
        <div class="product-main">
            <!-- Imagen del Producto -->
            <div class="product-image-section">
                <div class="product-image-container">
                    @if($producto->novedad || $producto->destacado)
                        <div class="product-badges">
                            @if($producto->novedad)
                                <span class="badge badge-nuevo">Nuevo</span>
                            @endif
                            @if($producto->destacado)
                                <span class="badge badge-destacado">Destacado</span>
                            @endif
                        </div>
                    @endif
                    
                    @if($producto->imagenes && count($producto->imagenes) > 0)
                        <!-- Galería de imágenes -->
                        <div class="gallery-main" id="galleryMain">
                            <img src="{{ asset($producto->imagenes[0]) }}" 
                                 alt="{{ $producto->nombre }}"
                                 id="mainImage">
                        </div>
                    @elseif($producto->imagen)
                        <div class="gallery-main">
                            <img src="{{ asset($producto->imagen) }}" 
                                 class="product-image" 
                                 alt="{{ $producto->nombre }}">
                        </div>
                    @else
                        <div class="product-image-placeholder">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#666666" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                    @endif
                </div>
                
                @if($producto->imagenes && count($producto->imagenes) > 1)
                    <div class="gallery-thumbnails">
                        @foreach($producto->imagenes as $index => $imagen)
                            <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" 
                                 onclick="changeImage('{{ asset($imagen) }}', this)">
                                <img src="{{ asset($imagen) }}" alt="{{ $producto->nombre }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Información del Producto -->
            <div class="product-info-section">
                @if($producto->categoria)
                    <span class="product-category-label">{{ $producto->categoria->nombre }}</span>
                @endif
                
                <h1 class="product-title">{{ $producto->nombre }}</h1>
                
                <div class="product-price">{{ number_format($producto->precio, 2) }} €</div>

                <!-- Descripción -->
                <div class="product-description-section">
                    <h3>Descripción</h3>
                    <p class="product-description">{{ $producto->descripcion }}</p>
                </div>

                <!-- Stock -->
                <div class="product-stock-info">
                    <span class="stock-label">Disponibilidad:</span>
                    <span class="stock-badge {{ $producto->stock > 10 ? 'stock-success' : ($producto->stock > 0 ? 'stock-warning' : 'stock-danger') }}">
                        @if($producto->stock > 10)
                            ✓ En Stock ({{ $producto->stock }} unidades)
                        @elseif($producto->stock > 0)
                            ⚠ Últimas unidades ({{ $producto->stock }} disponibles)
                        @else
                            ✗ Sin Stock
                        @endif
                    </span>
                </div>

                <!-- Acciones -->
                <div class="product-actions">
                    @if($producto->stock > 0)
                        <button onclick="addToCart({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->precio }}, '{{ $producto->imagen ?? '' }}')" class="btn-add-cart" id="btnAddCart">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle;">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Añadir al Carrito
                        </button>
                    @else
                        <button class="btn-add-cart" disabled>Sin Stock</button>
                    @endif
                    <a href="{{ route('tienda.productos') }}" class="btn-back">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        Volver
                    </a>
                </div>

                <!-- Características / Beneficios -->
                <div class="product-features">
                    <div class="feature-item">
                        <span class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#C9FF00" stroke-width="2">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                        </span>
                        <span class="feature-text">Envío gratuito en pedidos superiores a 50€</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#C9FF00" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </span>
                        <span class="feature-text">Garantía oficial de fabricante</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#C9FF00" stroke-width="2">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <polyline points="1 20 1 14 7 14"></polyline>
                                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                            </svg>
                        </span>
                        <span class="feature-text">Devoluciones en 30 días sin compromiso</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#C9FF00" stroke-width="2">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                        </span>
                        <span class="feature-text">Pago seguro con encriptación SSL</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos Relacionados -->
        @if($relacionados->count() > 0)
            <div class="related-section">
                <h2 class="section-title">Productos Relacionados</h2>
                
                <div class="related-grid">
                    @foreach($relacionados as $rel)
                        <div class="related-card">
                            <img src="{{ $rel->imagen ?? 'https://images.unsplash.com/photo-1622163642998-1ea32b0bbc67?w=400&h=400&fit=crop' }}" 
                                 class="related-image" 
                                 alt="{{ $rel->nombre }}">
                            
                            <div class="related-info">
                                <h3 class="related-name">{{ $rel->nombre }}</h3>
                                <div class="related-price">{{ number_format($rel->precio, 2) }} €</div>
                                <a href="{{ route('tienda.producto', $rel->id) }}" class="btn-related">Ver Detalles</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Botón del carrito flotante -->
    <a href="{{ route('tienda.carrito') }}" class="cart-button">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
        @php
            $carrito = session()->get('carrito', []);
            $totalItems = array_sum(array_column($carrito, 'cantidad'));
        @endphp
        @if($totalItems > 0)
            <span class="cart-badge">{{ $totalItems }}</span>
        @endif
    </a>

    <script>
        function changeImage(src, element) {
            // Cambiar imagen principal
            document.getElementById('mainImage').src = src;
            
            // Actualizar clases de thumbnails
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            element.classList.add('active');
        }

        function addToCart(productId, productName, productPrice, productImage) {
            const btn = document.getElementById('btnAddCart');
            btn.disabled = true;
            btn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; animation: spin 1s linear infinite;"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg> Añadiendo...';

            // Hacer la petición para añadir al carrito
            fetch(`/tienda/carrito/add/${productId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Actualizar el badge del carrito
                    const cartBadge = document.querySelector('.cart-badge');
                    if (cartBadge) {
                        const currentCount = parseInt(cartBadge.textContent) || 0;
                        cartBadge.textContent = currentCount + 1;
                    } else {
                        // Crear el badge si no existe
                        const cartButton = document.querySelector('.cart-button');
                        const badge = document.createElement('span');
                        badge.className = 'cart-badge';
                        badge.textContent = '1';
                        cartButton.appendChild(badge);
                    }
                    
                    btn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle;"><polyline points="20 6 9 17 4 12"></polyline></svg> Añadido';
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> Añadir al Carrito';
                    }, 2000);
                } else {
                    alert('Error al añadir el producto al carrito');
                    btn.disabled = false;
                    btn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> Añadir al Carrito';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al añadir el producto al carrito');
                btn.disabled = false;
                btn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> Añadir al Carrito';
            });
        }
    </script>

    <x-footer />
</body>
</html>
