<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda - Badia Padel Tour</title>
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
        
        .shop-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 24px;
        }
        
        .shop-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .shop-title {
            color: #C9FF00;
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        
        .shop-count {
            color: #999999;
            font-size: 1rem;
            margin-top: 4px;
        }
        
        .cart-button {
            background-color: #C9FF00;
            color: #000000;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            position: relative;
        }
        
        .cart-button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .cart-badge {
            background-color: #ff4444;
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 10px;
            min-width: 20px;
            text-align: center;
        }
        
        .filters-container {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 40px;
        }
        
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: end;
        }
        
        .filter-group label {
            display: block;
            color: #C9FF00;
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 8px;
        }
        
        .filter-select,
        .filter-input {
            width: 100%;
            background-color: #2a2a2a;
            color: #ffffff;
            border: 1px solid #3a3a3a;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 0.95rem;
            font-family: 'Gopher', sans-serif;
            transition: all 0.2s;
        }
        
        .filter-select:focus,
        .filter-input:focus {
            outline: none;
            border-color: #C9FF00;
            box-shadow: 0 0 0 2px rgba(201, 255, 0, 0.1);
        }
        
        .filter-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ffffff;
            cursor: pointer;
            margin-bottom: 8px;
        }
        
        .filter-checkbox input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .btn-clear {
            background-color: transparent;
            color: #C9FF00;
            border: 2px solid #C9FF00;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
        }
        
        .btn-clear:hover {
            background-color: #C9FF00;
            color: #000000;
        }
        
        .categories-bar {
            display: flex;
            gap: 16px;
            margin-bottom: 32px;
            overflow-x: auto;
            padding-bottom: 8px;
        }
        
        .category-chip {
            background-color: #1a1a1a;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 24px;
            font-weight: 700;
            font-size: 0.95rem;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s;
            border: 2px solid #2a2a2a;
        }
        
        .category-chip:hover {
            border-color: #C9FF00;
            color: #C9FF00;
        }
        
        .category-chip.active {
            background-color: #C9FF00;
            color: #000000;
            border-color: #C9FF00;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }
        
        .product-card {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(201, 255, 0, 0.1);
            border-color: #C9FF00;
        }
        
        .product-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background-color: #C9FF00;
            color: #000000;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            z-index: 10;
        }
        
        .product-badge.nuevo {
            background-color: #ff4444;
            color: #ffffff;
        }
        
        .product-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            background-color: #2a2a2a;
        }
        
        .product-image-placeholder {
            width: 100%;
            height: 300px;
            background-color: #2a2a2a;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666666;
            font-size: 3rem;
        }
        
        .product-info {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        
        .product-category {
            color: #C9FF00;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }
        
        .product-name {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 8px;
            line-height: 1.3;
            min-height: 2.6rem;
        }
        
        .product-description {
            color: #999999;
            font-size: 0.9rem;
            margin-bottom: 16px;
            flex-grow: 1;
            line-height: 1.4;
        }
        
        .product-footer {
            margin-top: auto;
        }
        
        .product-price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .product-price {
            color: #C9FF00;
            font-size: 1.5rem;
            font-weight: 800;
        }
        
        .product-stock {
            color: #999999;
            font-size: 0.85rem;
        }
        
        .product-actions {
            display: flex;
            gap: 8px;
        }
        
        .btn-view {
            flex: 1;
            background-color: transparent;
            color: #C9FF00;
            border: 2px solid #C9FF00;
            padding: 10px 16px;
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
            border: none;
            padding: 10px 16px;
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
            opacity: 0.85;
        }
        
        .btn-add:disabled {
            background-color: #3a3a3a;
            color: #666666;
            cursor: not-allowed;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 16px;
        }
        
        .empty-icon {
            font-size: 4rem;
            color: #3a3a3a;
            margin-bottom: 20px;
        }
        
        .empty-text {
            color: #999999;
            font-size: 1.1rem;
            margin-bottom: 20px;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 4px;
            margin-top: 60px;
            padding: 20px 0;
        }
        
        .pagination-info {
            color: #999999;
            font-size: 0.9rem;
            margin-right: 20px;
            font-weight: 600;
        }
        
        .pagination a,
        .pagination span {
            min-width: 44px;
            height: 44px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #1a1a1a;
            color: #ffffff;
            border: 1px solid #2a2a2a;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        
        .pagination a:hover {
            background-color: #C9FF00;
            color: #000000;
            border-color: #C9FF00;
            transform: translateY(-2px);
        }
        
        .pagination .active span {
            background-color: #C9FF00;
            color: #000000;
            border-color: #C9FF00;
        }
        
        .pagination .disabled span {
            background-color: #1a1a1a;
            color: #666666;
            cursor: not-allowed;
            opacity: 0.5;
        }
        
        .pagination-nav {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            color: #C9FF00;
            min-width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.2rem;
            transition: all 0.2s;
        }
        
        .pagination-nav:hover {
            background-color: #C9FF00;
            color: #000000;
            border-color: #C9FF00;
        }
        
        .alert {
            background-color: #1a1a1a;
            border-left: 4px solid #C9FF00;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            color: #ffffff;
        }
        
        @media (max-width: 768px) {
            .shop-title {
                font-size: 2rem;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 16px;
            }
            
            .filters-grid {
                grid-template-columns: 1fr;
            }
            
            .categories-bar {
                padding: 0 4px;
            }
        }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-header />

    <div class="shop-container">
        @if(session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

        @php
            $carrito = session()->get('carrito', []);
            $carritoItems = array_sum(array_column($carrito, 'cantidad'));
        @endphp

        <div class="shop-header">
            <div>
                <h1 class="shop-title">
                    @if($categoriaSeleccionada)
                        {{ $categoriaSeleccionada->nombre }}
                    @else
                        Tienda BPT
                    @endif
                </h1>
                <p class="shop-count">
                    {{ $productos->total() }} {{ $productos->total() == 1 ? 'producto' : 'productos' }}
                </p>
            </div>
            <a href="{{ route('tienda.carrito') }}" class="cart-button">
                🛒 Ver Carrito
                @if($carritoItems > 0)
                    <span class="cart-badge">{{ $carritoItems }}</span>
                @endif
            </a>
        </div>

        <!-- Categorías principales -->
        <div class="categories-bar">
            <a href="{{ route('tienda.productos') }}" class="category-chip {{ !request('categoria') ? 'active' : '' }}">
                Todos los productos
            </a>
            @foreach($categorias as $cat)
                <a href="{{ route('tienda.productos', ['categoria' => $cat->id]) }}" 
                   class="category-chip {{ request('categoria') == $cat->id ? 'active' : '' }}">
                    {{ $cat->nombre }} ({{ $cat->productos_count }})
                </a>
            @endforeach
        </div>

        <!-- Filtros y ordenamiento -->
        <div class="filters-container">
            <form method="GET" action="{{ route('tienda.productos') }}" class="filters-grid">
                <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                
                <div class="filter-group">
                    <label>Ordenar por</label>
                    <select name="orden" class="filter-select" onchange="this.form.submit()">
                        <option value="nombre_asc" {{ request('orden') == 'nombre_asc' ? 'selected' : '' }}>Nombre (A-Z)</option>
                        <option value="nombre_desc" {{ request('orden') == 'nombre_desc' ? 'selected' : '' }}>Nombre (Z-A)</option>
                        <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                        <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                        <option value="destacados" {{ request('orden') == 'destacados' ? 'selected' : '' }}>Destacados</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Filtros especiales</label>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="destacados" {{ request('destacados') ? 'checked' : '' }} onchange="this.form.submit()">
                        Solo Destacados
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="novedades" {{ request('novedades') ? 'checked' : '' }} onchange="this.form.submit()">
                        Solo Novedades
                    </label>
                </div>

                <div class="filter-group">
                    <label>&nbsp;</label>
                    <a href="{{ route('tienda.productos') }}" class="btn-clear">
                        ✕ Limpiar Filtros
                    </a>
                </div>
            </form>
        </div>

        <!-- Grid de productos -->
        <div class="products-grid">
            @forelse ($productos as $producto)
                <div class="product-card">
                    @if($producto->novedad)
                        <span class="product-badge nuevo">NUEVO</span>
                    @elseif($producto->destacado)
                        <span class="product-badge">DESTACADO</span>
                    @endif

                    @if($producto->imagen)
                        <img src="{{ asset($producto->imagen) }}" 
                             class="product-image" 
                             alt="{{ $producto->nombre }}">
                    @else
                        <div class="product-image-placeholder">
                            🎾
                        </div>
                    @endif

                    <div class="product-info">
                        <div class="product-category">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</div>
                        <h3 class="product-name">{{ Str::limit($producto->nombre, 50) }}</h3>
                        <p class="product-description">{{ Str::limit($producto->descripcion, 80) }}</p>
                        
                        <div class="product-footer">
                            <div class="product-price-row">
                                <span class="product-price">{{ number_format($producto->precio, 2) }} €</span>
                                <span class="product-stock">Stock: {{ $producto->stock }}</span>
                            </div>
                            
                            <div class="product-actions">
                                <a href="{{ route('tienda.producto', $producto->id) }}" class="btn-view">
                                    👁 Ver
                                </a>
                                @if($producto->stock > 0)
                                    <a href="{{ route('tienda.carrito.add', $producto->id) }}" class="btn-add">
                                        + Añadir
                                    </a>
                                @else
                                    <button class="btn-add" disabled>Sin Stock</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-icon">📦</div>
                    <p class="empty-text">No hay productos disponibles con los filtros seleccionados.</p>
                    <a href="{{ route('tienda.productos') }}" class="cart-button">Ver Todos los Productos</a>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($productos->hasPages())
            <div class="pagination">
                {{ $productos->links() }}
            </div>
        @endif
    </div>

    <x-footer />
</body>
</html>
