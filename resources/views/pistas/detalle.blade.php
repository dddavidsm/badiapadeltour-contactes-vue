<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pista->nombre ?? 'Pista' }} - Badia Padel Tour</title>
    <link rel="icon" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --electric: #c9ff00; --black: #0f0f0f; --dark-green: #4a5d2f; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Gopher', 'Inter', sans-serif; color: #fff; background: #0f0f0f; }
        .container { width: min(1200px, calc(100% - 96px)); margin: 0 auto; }
        
        .pista-hero { width: 100%; height: 320px; background-size: cover; background-position: center; background-repeat: no-repeat; margin-bottom: 40px; }
        
        .pista-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; gap: 32px; }
        .pista-title { margin: 0; font-size: 48px; color: var(--electric); font-weight: 800; letter-spacing: -0.02em; line-height: 1.1; }
        .location-badge { background: var(--dark-green); color: var(--electric); padding: 14px 24px; border-radius: 10px; font-size: 16px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; }
        
        .pista-content { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; padding-bottom: 60px; }
        
        .info-card, .reservar-card { background: #1a1a1a; border-radius: 14px; padding: 32px; }
        .card-title { margin: 0 0 24px; font-size: 22px; font-weight: 800; color: #fff; }
        
        .info-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 20px; }
        .info-item { }
        .info-label { margin: 0 0 8px; font-size: 13px; font-weight: 600; color: #999; text-transform: capitalize; }
        .info-value { margin: 0; font-size: 16px; font-weight: 700; color: #fff; }
        .info-value.price { font-size: 18px; color: var(--electric); }
        .info-value.status { color: var(--electric); text-transform: uppercase; }
        
        .info-divider { border: none; border-top: 1px solid #333; margin: 24px 0; }
        
        .info-full { margin-bottom: 20px; }
        
        .reservar-text { margin: 0 0 24px; font-size: 14px; color: #999; line-height: 1.6; }
        .reservar-btn { background: var(--electric); color: #000; border: none; border-radius: 8px; padding: 14px 0; font-weight: 800; font-size: 14px; width: 100%; text-transform: uppercase; letter-spacing: 0.02em; cursor: pointer; transition: background 0.2s; text-decoration: none; display: block; text-align: center; }
        .reservar-btn:hover { background: #b8ee00; color: #000; }
        
        @media (max-width: 960px) {
            .pista-header { flex-direction: column; align-items: flex-start; }
            .pista-title { font-size: 36px; }
            .pista-content { grid-template-columns: 1fr; }
            .pista-hero { height: 240px; }
        }
        
        @media (max-width: 640px) {
            .container { width: calc(100% - 48px); }
            .pista-title { font-size: 32px; }
            .info-row { grid-template-columns: 1fr; gap: 16px; }
            .info-card, .reservar-card { padding: 24px; }
        }
    </style>
</head>
<body>
    @include('components.header')

    <div class="pista-hero" style="background-image: url('{{ $pista->imagen ? asset($pista->imagen) : 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=1200' }}');"></div>

    <section style="padding: 0 0 60px;">
        <div class="container">
            <div class="pista-header">
                <h1 class="pista-title">{{ $pista->nombre ?? 'Pista' }}</h1>
                <div class="location-badge">
                    <img src="/assets/location_icon.svg" alt="location" style="height: 18px; width: auto; vertical-align: middle; margin-right: 6px;">
                    <span>{{ $pista->complejo->nombre ?? 'Complejo' }}</span>
                </div>
            </div>
            
            <div class="pista-content">
                <div class="info-card">
                    <h2 class="card-title">Información De La Pista</h2>
                    
                    <div class="info-row">
                        <div class="info-item">
                            <p class="info-label">Tipo</p>
                            <p class="info-value">{{ ucfirst($pista->tipo ?? 'Indoor') }}</p>
                        </div>
                        <div class="info-item">
                            <p class="info-label">Para Dobles</p>
                            <p class="info-value">{{ $pista->es_dobles ? 'Sí' : 'No' }}</p>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-item">
                            <p class="info-label">Precio Por Hora</p>
                            <p class="info-value price">€{{ number_format($pista->precio_hora ?? 0, 2) }}</p>
                        </div>
                        <div class="info-item">
                            <p class="info-label">Estado</p>
                            <p class="info-value status">{{ $pista->disponible ? 'DISPONIBLE' : 'NO DISPONIBLE' }}</p>
                        </div>
                    </div>
                    
                    <hr class="info-divider">
                    
                    <div class="info-full">
                        <p class="info-label">Complejo</p>
                        <p class="info-value">{{ $pista->complejo->nombre ?? 'Complejo' }}</p>
                    </div>
                    
                    <div class="info-full">
                        <p class="info-label">Dirección</p>
                        <p class="info-value">{{ $pista->complejo->direccion ?? 'Dirección no disponible' }}</p>
                    </div>
                </div>
                
                <div class="reservar-card">
                    <h2 class="card-title">¿Quieres Reservar?</h2>
                    <p class="reservar-text">Completa el formulario y asegura tu pista</p>
                    
                    @if($pista->disponible)
                        <a href="{{ route('reservas.formulario', $pista->id) }}" class="reservar-btn">Reservar Ahora</a>
                    @else
                        <button class="reservar-btn" style="background: #666; cursor: not-allowed;" disabled>No Disponible</button>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @include('components.footer')
</body>
</html>
