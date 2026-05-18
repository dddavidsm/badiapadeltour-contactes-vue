<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda BPT - Badia Padel Tour</title>
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
        
        .tienda-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px 60px;
        }
        
        .tienda-header {
            padding: 60px 0 40px;
        }
        
        .tienda-title {
            font-size: 4rem;
            font-weight: 800;
            color: #C9FF00;
            margin-bottom: 12px;
            letter-spacing: -0.03em;
        }
        
        .tienda-subtitle {
            font-size: 1.1rem;
            color: #cccccc;
            font-weight: 500;
        }
        
        .categories-section {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }
        
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 24px;
        }
        
        .category-item {
            text-align: center;
            text-decoration: none;
            transition: all 0.3s;
            padding: 16px;
            border-radius: 12px;
            background-color: #2a2a2a;
            border: 1px solid #3a3a3a;
        }
        
        .category-item:hover {
            background-color: #C9FF00;
            border-color: #C9FF00;
            transform: translateY(-4px);
        }
        
        .category-item:hover .category-icon {
            filter: brightness(0);
        }
        
        .category-item:hover .category-name {
            color: #000000;
        }
        
        .category-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 12px;
            font-size: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .category-name {
            font-size: 1rem;
            font-weight: 700;
            color: #ffffff;
            transition: all 0.3s;
        }
        
        .filters-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .filters-button {
            background-color: #111111;
            color: #C9FF00;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        
        .filters-button:hover {
            background-color: #C9FF00;
            color: #111111;
        }
        
        .results-count {
            font-size: 1.1rem;
            color: #999999;
            font-weight: 600;
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
            z-index: 1000;
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
        
        .products-section {
            margin-top: 40px;
        }
        
        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .section-badge {
            background-color: #C9FF00;
            color: #111111;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 60px;
        }
        
        .product-card {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(201, 255, 0, 0.1);
            border-color: #C9FF00;
        }
        
        .product-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            background-color: #C9FF00;
            color: #111111;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            z-index: 10;
        }
        
        .product-badge.new {
            background-color: #ff4444;
            color: #ffffff;
        }
        
        .product-image {
            width: 100%;
            height: 350px;
            object-fit: cover;
            background-color: #2a2a2a;
        }
        
        .product-info {
            padding: 24px;
        }
        
        .product-category {
            color: #C9FF00;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }
        
        .product-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 12px;
            line-height: 1.3;
            min-height: 3rem;
        }
        
        .product-description {
            color: #999999;
            font-size: 0.95rem;
            margin-bottom: 16px;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .product-price {
            font-size: 1.8rem;
            font-weight: 800;
            color: #C9FF00;
            margin-bottom: 16px;
        }
        
        .product-actions {
            display: flex;
            gap: 12px;
        }
        
        .btn-view {
            flex: 1;
            background-color: transparent;
            color: #C9FF00;
            border: 2px solid #C9FF00;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .btn-view:hover {
            background-color: #C9FF00;
            color: #000000;
        }
        
        .btn-add {
            flex: 1;
            background-color: #C9FF00;
            color: #000000;
            border: 2px solid #C9FF00;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .btn-add:hover {
            background-color: #000000;
            color: #C9FF00;
            border-color: #C9FF00;
        }
        
        .btn-catalog {
            display: inline-block;
            background-color: #111111;
            color: #C9FF00;
            padding: 16px 40px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.2s;
            margin-top: 20px;
        }
        
        .btn-catalog:hover {
            background-color: #C9FF00;
            color: #111111;
            transform: translateY(-2px);
        }
        
        .cta-section {
            background: linear-gradient(135deg, #111111 0%, #2a2a2a 100%);
            color: #ffffff;
            padding: 80px 40px;
            border-radius: 16px;
            text-align: center;
            margin-top: 60px;
        }
        
        .cta-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #C9FF00;
            margin-bottom: 16px;
        }
        
        .cta-text {
            font-size: 1.2rem;
            color: #ffffff;
            margin-bottom: 32px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
        }
        
        .empty-icon {
            font-size: 4rem;
            color: #666666;
            margin-bottom: 20px;
        }
        
        .empty-text {
            color: #999999;
            font-size: 1.1rem;
        }
        
        @media (max-width: 768px) {
            .tienda-title {
                font-size: 2.5rem;
            }
            
            .categories-grid {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
                gap: 16px;
            }
            
            .category-icon {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .cta-title {
                font-size: 1.8rem;
            }
        }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-header />

    <div class="tienda-container">
        <!-- Header -->
        <div class="tienda-header">
            <h1 class="tienda-title">Tienda</h1>
            <p class="tienda-subtitle">Descubre nuestra colección completa de palas, ropa y accesorios de pádel</p>
        </div>

        <!-- Categories -->
        <div class="categories-section">
            <div class="categories-grid">
                <a href="{{ route('tienda.productos') }}" class="category-item">
                    <div class="category-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 2a10 10 0 0 0 0 20"></path>
                        </svg>
                    </div>
                    <div class="category-name">Todas</div>
                </a>
                
                @foreach($categorias as $categoria)
                    <a href="{{ route('tienda.productos', ['categoria' => $categoria->id]) }}" class="category-item">
                        <div class="category-icon">
                            @if($categoria->nombre == 'Palas')
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="7" height="18" rx="1"></rect>
                                    <rect x="14" y="3" width="7" height="18" rx="1"></rect>
                                    <line x1="10" y1="8" x2="14" y2="8"></line>
                                    <line x1="10" y1="16" x2="14" y2="16"></line>
                                </svg>
                            @elseif($categoria->nombre === 'Ropa')
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.38 3.46L16 2a4 4 0 01-8 0L3.62 3.46a2 2 0 00-1.34 2.23l.58 3.47a1 1 0 00.99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 002-2V10h2.15a1 1 0 00.99-.84l.58-3.47a2 2 0 00-1.34-2.23z"></path>
                                </svg>
                            @elseif($categoria->nombre === 'Accesorios')
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 7h-7c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h7l2.4-5L20 7z"></path>
                                    <path d="M5 7h8v8H5a1 1 0 01-1-1V8a1 1 0 011-1z"></path>
                                    <path d="M11 21H6a2 2 0 01-2-2v-2h9"></path>
                                </svg>
                            @else
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 2a10 10 0 0 0 0 20"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="category-name">{{ $categoria->nombre }}</div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="filters-bar" style="justify-content: flex-end;">
            <span class="results-count">
                @php
                    $totalProductos = $destacados->count() + $novedades->count();
                @endphp
                {{ $totalProductos }} Productos
            </span>
        </div>

        <!-- Productos Destacados -->
        @if($destacados->count() > 0)
            <div class="products-section">
                <h2 class="section-title">
                    Productos Destacados
                    <span class="section-badge">Popular</span>
                </h2>
                
                <div class="products-grid">
                    @foreach($destacados as $producto)
                        <div class="product-card">
                            <span class="product-badge">Destacado</span>
                            
                            <img src="{{ $producto->imagen ? asset($producto->imagen) : 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=600&h=600&fit=crop' }}" 
                                 class="product-image" 
                                 alt="{{ $producto->nombre }}">
                            
                            <div class="product-info">
                                <div class="product-category">{{ $producto->categoria->nombre ?? 'Producto' }}</div>
                                <h3 class="product-name">{{ $producto->nombre }}</h3>
                                <p class="product-description">{{ Str::limit($producto->descripcion, 100) }}</p>
                                <div class="product-price">{{ number_format($producto->precio, 2) }} €</div>
                                
                                <div class="product-actions">
                                    <a href="{{ route('tienda.producto', $producto->id) }}" class="btn-view">Ver Detalles</a>
                                    @if($producto->stock > 0)
                                        <a href="{{ route('tienda.carrito.add', $producto->id) }}" class="btn-add">Añadir</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Novedades -->
        @if($novedades->count() > 0)
            <div class="products-section">
                <h2 class="section-title">
                    Novedades
                    <span class="section-badge" style="background-color: #ff4444; color: #ffffff;">Nuevo</span>
                </h2>
                
                <div class="products-grid">
                    @foreach($novedades as $producto)
                        <div class="product-card">
                            <span class="product-badge new">Nuevo</span>
                            
                            <img src="{{ $producto->imagen ? asset($producto->imagen) : 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=600&h=600&fit=crop' }}" 
                                 class="product-image" 
                                 alt="{{ $producto->nombre }}">
                            
                            <div class="product-info">
                                <div class="product-category">{{ $producto->categoria->nombre ?? 'Producto' }}</div>
                                <h3 class="product-name">{{ $producto->nombre }}</h3>
                                <p class="product-description">{{ Str::limit($producto->descripcion, 100) }}</p>
                                <div class="product-price">{{ number_format($producto->precio, 2) }} €</div>
                                
                                <div class="product-actions">
                                    <a href="{{ route('tienda.producto', $producto->id) }}" class="btn-view">Ver Detalles</a>
                                    @if($producto->stock > 0)
                                        <a href="{{ route('tienda.carrito.add', $producto->id) }}" class="btn-add">Añadir</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- CTA Section -->
        <div class="cta-section">
            <h2 class="cta-title">¿Listo para mejorar tu juego?</h2>
            <p class="cta-text">Explora nuestro catálogo completo y encuentra el equipamiento perfecto para ti</p>
            <a href="{{ route('tienda.productos') }}" class="btn-catalog">Ver Catálogo Completo</a>
        </div>
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

    <x-footer />
</body>
</html>
