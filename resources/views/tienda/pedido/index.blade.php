<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Badia Padel Tour</title>
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
        
        .pedidos-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }
        
        .pedidos-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .pedidos-title {
            font-size: 3rem;
            font-weight: 800;
            color: #C9FF00;
            margin-bottom: 12px;
        }
        
        .pedidos-subtitle {
            font-size: 1.1rem;
            color: #999999;
        }
        
        .pedidos-list {
            display: grid;
            gap: 24px;
        }
        
        .pedido-card {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.2s;
        }
        
        .pedido-card:hover {
            border-color: #C9FF00;
            box-shadow: 0 8px 16px rgba(201, 255, 0, 0.1);
        }
        
        .pedido-header {
            background-color: #1f1f1f;
            border-bottom: 1px solid #2a2a2a;
            padding: 24px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .pedido-number {
            font-size: 1.5rem;
            font-weight: 800;
            color: #C9FF00;
        }
        
        .pedido-estado {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
        }
        
        .estado-pendiente {
            background-color: rgba(255, 200, 0, 0.2);
            color: #FFC800;
        }
        
        .estado-completado {
            background-color: rgba(0, 255, 100, 0.2);
            color: #00FF64;
        }
        
        .estado-entregado {
            background-color: rgba(0, 200, 255, 0.2);
            color: #00C8FF;
        }
        
        .estado-cancelado {
            background-color: rgba(255, 50, 50, 0.2);
            color: #FF3232;
        }
        
        .pedido-body {
            padding: 32px;
        }
        
        .pedido-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 32px;
            padding-bottom: 32px;
            border-bottom: 1px solid #2a2a2a;
        }
        
        .info-row {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .info-label {
            font-size: 0.9rem;
            color: #999999;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
        }
        
        .info-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff;
        }
        
        .info-value.price {
            color: #C9FF00;
        }
        
        .productos-section {
            margin-top: 24px;
        }
        
        .productos-title {
            font-size: 1rem;
            font-weight: 800;
            color: #C9FF00;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .producto-item {
            background-color: #2a2a2a;
            border-left: 3px solid #C9FF00;
            padding: 16px;
            margin-bottom: 12px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .producto-info {
            flex: 1;
        }
        
        .producto-nombre {
            font-weight: 700;
            font-size: 1rem;
            color: #ffffff;
            margin-bottom: 8px;
        }
        
        .producto-cantidad {
            font-size: 0.9rem;
            color: #999999;
        }
        
        .producto-precio {
            font-size: 1.2rem;
            font-weight: 800;
            color: #C9FF00;
            text-align: right;
        }
        
        .empty-state {
            text-align: center;
            padding: 80px 40px;
            background-color: #1a1a1a;
            border: 2px dashed #2a2a2a;
            border-radius: 12px;
        }
        
        .empty-icon {
            font-size: 4rem;
            margin-bottom: 24px;
        }
        
        .empty-title {
            font-size: 2rem;
            font-weight: 800;
            color: #C9FF00;
            margin-bottom: 12px;
        }
        
        .empty-text {
            color: #999999;
            font-size: 1.1rem;
            margin-bottom: 32px;
        }
        
        .btn-back {
            display: inline-block;
            background-color: #C9FF00;
            color: #000000;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            text-align: center;
        }
        
        .btn-back:hover {
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(201, 255, 0, 0.2);
        }
        
        @media (max-width: 768px) {
            .pedidos-title {
                font-size: 2rem;
            }
            
            .pedido-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .pedido-info {
                grid-template-columns: 1fr;
            }
            
            .producto-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .producto-precio {
                text-align: left;
            }
        }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-header />

    <div class="pedidos-container">
        <div class="pedidos-header">
            <h1 class="pedidos-title">Mis Pedidos</h1>
            <p class="pedidos-subtitle">Historial de tus compras en Badia Padel Tour</p>
        </div>

        @forelse ($pedidos as $pedido)
            <div class="pedidos-list">
                <div class="pedido-card">
                    <div class="pedido-header">
                        <span class="pedido-number">Pedido #{{ $pedido->id }}</span>
                        <span class="pedido-estado estado-{{ $pedido->estado }}">{{ ucfirst($pedido->estado) }}</span>
                    </div>
                    
                    <div class="pedido-body">
                        <div class="pedido-info">
                            <div class="info-row">
                                <span class="info-label">Fecha del Pedido</span>
                                <span class="info-value">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Total</span>
                                <span class="info-value price">{{ number_format($pedido->total, 2, ',', '.') }} €</span>
                            </div>
                        </div>
                        
                        @if($pedido->productos->count() > 0)
                            <div class="productos-section">
                                <h3 class="productos-title">Productos Pedidos</h3>
                                @foreach($pedido->productos as $producto)
                                    <div class="producto-item">
                                        <div class="producto-info">
                                            <div class="producto-nombre">{{ $producto->nombre }}</div>
                                            <div class="producto-cantidad">Cantidad: {{ $producto->pivot->cantidad }} unidad(es) × {{ number_format($producto->pivot->precio_unitario, 2, ',', '.') }} €</div>
                                        </div>
                                        <div class="producto-precio">{{ number_format($producto->pivot->precio_unitario * $producto->pivot->cantidad, 2, ',', '.') }} €</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">🛍️</div>
                <h2 class="empty-title">No tienes pedidos</h2>
                <p class="empty-text">Realiza tu primera compra en nuestra tienda</p>
                <a href="{{ route('tienda.productos') }}" class="btn-back">Ir a la Tienda</a>
            </div>
        @endforelse
    </div>

    <x-footer />
</body>
</html>
