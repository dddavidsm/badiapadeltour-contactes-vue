<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Complejos - Badia Padel Tour</title>
    <link rel="icon" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --electric: #c9ff00; --black: #0f0f0f; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Gopher', 'Inter', sans-serif; color: #fff; background: #0f0f0f; }
        .container { width: min(1200px, calc(100% - 96px)); margin: 0 auto; }
        
        .complejos-section { padding: 80px 0 60px; }
        .complejos-title { margin: 0 0 32px; font-size: 56px; color: var(--electric); font-weight: 800; letter-spacing: -0.02em; line-height: 1.1; }
        
        .filters-container { background: #1a1a1a; border-radius: 12px; padding: 24px; margin-bottom: 32px; }
        .filters-grid { display: grid; grid-template-columns: 1fr auto; gap: 16px; align-items: end; }
        .filter-group { display: flex; flex-direction: column; gap: 8px; }
        .filter-label { font-size: 13px; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: 0.05em; }
        .filter-select { background: #2a2a2a; color: #fff; border: 2px solid #3a3a3a; border-radius: 8px; padding: 10px 14px; font-size: 14px; font-weight: 600; font-family: 'Gopher', 'Inter', sans-serif; cursor: pointer; transition: border-color 0.2s; }
        .filter-select:focus { outline: none; border-color: var(--electric); }
        .filter-select option { background: #2a2a2a; }
        .filter-btn { background: var(--electric); color: #000; border: none; border-radius: 8px; padding: 12px 24px; font-weight: 800; font-size: 13px; text-transform: uppercase; cursor: pointer; transition: background 0.2s; height: fit-content; }
        .filter-btn:hover { background: #b8ee00; }
        .filter-btn-reset { background: #3a3a3a; color: #ccc; }
        .filter-btn-reset:hover { background: #4a4a4a; }
        
        .complex-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .complex-card { background: #1a1a1a; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 28px rgba(0,0,0,0.3); cursor: pointer; transition: transform 0.3s, box-shadow 0.3s; text-decoration: none; display: block; }
        .complex-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(201, 255, 0, 0.2); }
        
        .complex-thumb { width: 100%; height: 320px; background-size: cover; background-position: center; background-repeat: no-repeat; position: relative; display: flex; align-items: center; justify-content: center; }
        .complex-thumb::before { content: ''; position: absolute; inset: 0; background: rgba(0,0,0,0.35); }
        .complex-overlay-title { position: relative; z-index: 1; color: #fff; font-size: 32px; font-weight: 800; text-align: center; letter-spacing: 0.02em; text-transform: uppercase; }
        
        .complex-body { padding: 20px 24px 24px; }
        .complex-location { margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #999; }
        .complex-stats { margin: 0 0 16px; font-size: 14px; font-weight: 700; color: #fff; }
        .complex-btn { background: var(--electric); color: #000; border: none; border-radius: 8px; padding: 12px 0; font-weight: 800; font-size: 13px; width: 100%; text-transform: uppercase; letter-spacing: 0.02em; }
        
        @media (max-width: 960px) {
            .complex-grid { grid-template-columns: 1fr 1fr; }
            .complejos-title { font-size: 42px; }
        }
        
        @media (max-width: 640px) {
            .container { width: calc(100% - 48px); }
            .complex-grid { grid-template-columns: 1fr; }
            .complejos-title { font-size: 36px; }
            .complex-thumb { height: 240px; }
        }
    </style>
</head>
<body>
    @include('components.header')

    <section class="complejos-section">
        <div class="container">
            <h1 class="complejos-title">Nuestros Complejos</h1>
            
            <!-- Buscador de Complejos -->
            <div class="filters-container" style="margin-bottom: 32px;">
                <form method="GET" action="{{ route('complejos.index') }}" id="searchForm">
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <input 
                            type="text" 
                            name="buscar" 
                            id="buscar" 
                            class="filter-select" 
                            placeholder="Buscar complejo por nombre o dirección..."
                            value="{{ request('buscar') }}"
                            style="flex: 1; padding: 12px 16px; border-radius: 8px; background: #2a2a2a; color: #fff; border: 2px solid #3a3a3a; font-size: 14px; font-weight: 600;"
                        >
                        <button type="submit" class="filter-btn" style="padding: 12px 28px;">Buscar</button>
                        @if(request('buscar'))
                            <a href="{{ route('complejos.index') }}" class="filter-btn filter-btn-reset" style="padding: 12px 28px;">Limpiar</a>
                        @endif
                    </div>
                </form>
            </div>
            
            <div class="complex-grid">
                
                @forelse($complejos as $complejo)
                    <a href="{{ route('pistas.index', ['complejo_id' => $complejo->id]) }}" class="complex-card">
                        <div class="complex-thumb" style="background-image: url('{{ $complejo->imagen ? asset($complejo->imagen) : '/assets/complejos/complejo_badia.png' }}');">
                            <h3 class="complex-overlay-title">{{ strtoupper($complejo->nombre) }}</h3>
                        </div>
                        <div class="complex-body">
                            <div style="display: flex; align-items: flex-start; gap: 8px; margin-bottom: 8px;">
                                <img src="/assets/location_icon.svg" alt="location" style="height: 18px; width: 18px; flex-shrink: 0; margin-top: 2px;">
                                <p class="complex-location" style="margin: 0; flex: 1; line-height: 1.4;">{{ $complejo->direccion }}</p>
                            </div>
                            
                            @if($complejo->hora_apertura && $complejo->hora_cierre)
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; color: #c9ff00; font-size: 13px; font-weight: 600;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($complejo->hora_apertura)->format('H:i') }} - {{ \Carbon\Carbon::parse($complejo->hora_cierre)->format('H:i') }}</span>
                                </div>
                            @endif
                            
                            <p class="complex-stats">{{ $complejo->pistas_count }} Pistas</p>
                            <button class="complex-btn" type="button">Ver Pistas</button>
                        </div>
                    </a>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #999;">
                        <p>No hay complejos disponibles en este momento.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    @include('components.footer')
</body>
</html>
