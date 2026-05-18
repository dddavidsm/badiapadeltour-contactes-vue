<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pistas Disponibles - Badia Padel Tour</title>
    <link rel="icon" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --electric: #c9ff00; --black: #0f0f0f; --dark-green: #4a5d2f; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Gopher', 'Inter', sans-serif; color: #fff; background: #0f0f0f; }
        .container { width: min(1200px, calc(100% - 96px)); margin: 0 auto; }
        
        .pistas-section { padding: 80px 0 60px; }
        .pistas-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; gap: 32px; }
        .pistas-title { margin: 0; font-size: 56px; color: var(--electric); font-weight: 800; letter-spacing: -0.02em; line-height: 1.1; }
        
        .filters-container { background: #1a1a1a; border-radius: 12px; padding: 24px; margin-bottom: 32px; }
        .filters-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
        .filter-group { display: flex; flex-direction: column; gap: 8px; }
        .filter-label { font-size: 13px; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: 0.05em; }
        .filter-select { background: #2a2a2a; color: #fff; border: 2px solid #3a3a3a; border-radius: 8px; padding: 10px 14px; font-size: 14px; font-weight: 600; font-family: 'Gopher', 'Inter', sans-serif; cursor: pointer; transition: border-color 0.2s; }
        .filter-select:focus { outline: none; border-color: var(--electric); }
        .filter-select option { background: #2a2a2a; }
        .filters-actions { display: flex; gap: 12px; margin-top: 16px; }
        .filter-btn { background: var(--electric); color: #000; border: none; border-radius: 8px; padding: 12px 24px; font-weight: 800; font-size: 13px; text-transform: uppercase; cursor: pointer; transition: background 0.2s; }
        .filter-btn:hover { background: #b8ee00; }
        .filter-btn-reset { background: #3a3a3a; color: #ccc; }
        .filter-btn-reset:hover { background: #4a4a4a; }
        
        .location-selector { background: var(--dark-green); color: var(--electric); padding: 14px 24px; border-radius: 10px; font-size: 16px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; min-width: 240px; }
        .location-icon { font-size: 20px; }
        
        .pistas-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .pista-card { background: #1a1a1a; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 28px rgba(0,0,0,0.3); transition: transform 0.3s, box-shadow 0.3s; text-decoration: none; display: flex; flex-direction: column; }
        .pista-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(201, 255, 0, 0.2); }
        
        .pista-thumb { width: 100%; height: 220px; background-size: cover; background-position: center; background-repeat: no-repeat; background-color: #2a2a2a; }
        
        .pista-body { padding: 18px 20px 20px; flex: 1; display: flex; flex-direction: column; }
        .pista-name { margin: 0 0 8px; font-size: 18px; font-weight: 800; color: #fff; letter-spacing: 0.01em; }
        .pista-location { margin: 0 0 12px; font-size: 13px; font-weight: 600; color: #999; }
        .pista-info { margin: 0 0 4px; font-size: 13px; color: #ccc; }
        .pista-info strong { color: #fff; font-weight: 700; }
        .pista-price { margin: 8px 0 16px; font-size: 14px; color: #ccc; }
        .pista-price strong { color: var(--electric); font-size: 16px; }
        .pista-btn { background: var(--electric); color: #000; border: none; border-radius: 8px; padding: 12px 0; font-weight: 800; font-size: 13px; width: 100%; text-transform: uppercase; letter-spacing: 0.02em; margin-top: auto; cursor: pointer; transition: background 0.2s; }
        .pista-btn:hover { background: #b8ee00; }
        
        @media (max-width: 960px) {
            .pistas-grid { grid-template-columns: 1fr 1fr; }
            .pistas-title { font-size: 42px; }
            .pistas-header { flex-direction: column; }
        }
        
        @media (max-width: 640px) {
            .container { width: calc(100% - 48px); }
            .pistas-grid { grid-template-columns: 1fr; }
            .pistas-title { font-size: 36px; }
            .location-selector { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    @include('components.header')

    <section class="pistas-section">
        <div class="container">
            <div class="pistas-header">
                <h1 class="pistas-title">Pistas<br>Disponibles</h1>
                
                @if($complejo)
                    <div class="location-selector">
                        <img src="/assets/location_icon.svg" alt="location" style="height: 18px; width: auto; vertical-align: middle; margin-right: 6px;">
                        <span>{{ $complejo->nombre }}</span>
                    </div>
                @endif
            </div>
            
            <!-- Filtros -->
            <div class="filters-container">
                <form method="GET" action="{{ route('pistas.index') }}" id="filterForm">
                    <div class="filters-grid">
                        <!-- Filtro por Complejo -->
                        <div class="filter-group">
                            <label class="filter-label" for="complejo_id">Complejo</label>
                            <select name="complejo_id" id="complejo_id" class="filter-select">
                                <option value="">Todos los complejos</option>
                                @foreach($complejos as $comp)
                                    <option value="{{ $comp->id }}" {{ request('complejo_id') == $comp->id ? 'selected' : '' }}>
                                        {{ $comp->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Filtro por Tipo (Indoor/Outdoor) -->
                        <div class="filter-group">
                            <label class="filter-label" for="tipo">Tipo</label>
                            <select name="tipo" id="tipo" class="filter-select">
                                <option value="">Todos los tipos</option>
                                <option value="indoor" {{ request('tipo') == 'indoor' ? 'selected' : '' }}>Indoor</option>
                                <option value="outdoor" {{ request('tipo') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                            </select>
                        </div>
                        
                        <!-- Filtro por Precio -->
                        <div class="filter-group">
                            <label class="filter-label" for="precio_max">Precio Máximo (€/hora)</label>
                            <select name="precio_max" id="precio_max" class="filter-select">
                                <option value="">Sin límite</option>
                                <option value="39" {{ request('precio_max') == '39' ? 'selected' : '' }}>Hasta 39€</option>
                                <option value="42" {{ request('precio_max') == '42' ? 'selected' : '' }}>Hasta 42€</option>
                                <option value="45" {{ request('precio_max') == '45' ? 'selected' : '' }}>Hasta 45€</option>
                                <option value="50" {{ request('precio_max') == '50' ? 'selected' : '' }}>Hasta 50€</option>
                            </select>
                        </div>
                        
                        <!-- Filtro por Dobles -->
                        <div class="filter-group">
                            <label class="filter-label" for="dobles">Apta para Dobles</label>
                            <select name="dobles" id="dobles" class="filter-select">
                                <option value="">Todas</option>
                                <option value="1" {{ request('dobles') == '1' ? 'selected' : '' }}>Solo dobles</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="filters-actions">
                        <button type="submit" class="filter-btn">Aplicar Filtros</button>
                        <a href="{{ route('pistas.index') }}" class="filter-btn filter-btn-reset">Limpiar</a>
                    </div>
                </form>
            </div>
            
            <div class="pistas-grid">
                @forelse($pistas as $index => $pista)
                    <div class="pista-card">
                        <div class="pista-thumb" style="background-image: url('{{ $pista->imagen ? asset($pista->imagen) : 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=500' }}');"></div>
                        <div class="pista-body">
                            <h3 class="pista-name">{{ $pista->nombre }}</h3>
                            <p class="pista-location">{{ $pista->complejo->nombre ?? 'Complejo' }}</p>
                            <p class="pista-info">Tipo: <strong>{{ $pista->tipo == 'indoor' ? 'Indoor' : 'Outdoor' }}</strong></p>
                            <p class="pista-price">Precio/Hora: <strong>€{{ number_format($pista->precio_hora ?? 0, 2) }}</strong></p>
                            @if($pista->complejo->hora_apertura && $pista->complejo->hora_cierre)
                                <p class="pista-info small" style="color: #666;"><i class="bi bi-clock"></i> Horario: {{ \Carbon\Carbon::parse($pista->complejo->hora_apertura)->format('H:i') }} - {{ \Carbon\Carbon::parse($pista->complejo->hora_cierre)->format('H:i') }}</p>
                            @endif
                            <p class="pista-disponibilidad" style="margin-top: 4px; font-size: 13px; color: #ccc;">
                                Disponibilidad: 
                                @if($pista->disponible)
                                    <span style="color: #4caf50; font-weight: 700;">Disponible</span>
                                @else
                                    <span style="color: #f44336; font-weight: 700;">No Disponible</span>
                                @endif 
                            </p>
                            <br>
                            <a href="{{ route('pistas.detalle', $pista->id) }}">
                                <button class="pista-btn" type="button">Ver Pista</button>
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #999;">
                        <p>No hay pistas disponibles{{ $complejo ? ' en este complejo' : '' }}.</p>
                        @if($complejo)
                            <a href="{{ route('complejos.index') }}" style="color: var(--electric); text-decoration: underline;">Ver todos los complejos</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    @include('components.footer')
</body>
</html>
