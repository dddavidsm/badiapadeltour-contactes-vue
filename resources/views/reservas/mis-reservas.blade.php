<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis Reservas - Badia Padel Tour</title>
    <link rel="icon" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --electric: #c9ff00; --black: #0f0f0f; --dark-green: #4a5d2f; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Gopher', 'Inter', sans-serif; color: #fff; background: #0f0f0f; }
        .container { width: min(1200px, calc(100% - 96px)); margin: 0 auto; }
        
        .reservas-section { padding: 80px 0 60px; min-height: 60vh; }
        .reservas-title { margin: 0 0 16px; font-size: 56px; color: var(--electric); font-weight: 800; letter-spacing: -0.02em; line-height: 1.1; }
        .reservas-subtitle { margin: 0 0 48px; font-size: 18px; color: #999; font-weight: 500; }
        
        .reservas-grid { display: grid; gap: 24px; }
        .reserva-card { background: #1a1a1a; border-radius: 14px; padding: 28px; border: 2px solid #2a2a2a; transition: border-color 0.3s; }
        .reserva-card:hover { border-color: var(--electric); }
        
        .reserva-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; }
        .reserva-pista { margin: 0; font-size: 24px; font-weight: 800; color: #fff; }
        .reserva-estado { padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: 700; text-transform: uppercase; }
        .reserva-estado.confirmada { background: var(--dark-green); color: var(--electric); }
        .reserva-estado.pendiente { background: #8b6914; color: #ffdd57; }
        .reserva-estado.cancelada { background: #7a2626; color: #ff6b6b; }
        
        .reserva-body { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .reserva-info { }
        .reserva-label { margin: 0 0 6px; font-size: 12px; color: #888; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .reserva-value { margin: 0; font-size: 16px; color: #fff; font-weight: 700; }
        
        .reserva-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px solid #2a2a2a; flex-wrap: wrap; gap: 16px; }
        .reserva-precio { margin: 0; font-size: 28px; font-weight: 800; color: var(--electric); }
        .reserva-btn { background: var(--electric); color: #000; border: none; border-radius: 8px; padding: 12px 24px; font-weight: 800; font-size: 13px; text-transform: uppercase; cursor: pointer; text-decoration: none; display: inline-flex; transition: background-color 0.2s; margin: 4px; }
        .reserva-btn:hover { background: #b8ee00; }
        
        .empty-state { text-align: center; padding: 80px 20px; }
        .empty-state h3 { margin: 0 0 16px; font-size: 32px; color: #666; font-weight: 700; }
        .empty-state p { margin: 0 0 32px; font-size: 16px; color: #555; }
        .empty-btn { background: var(--electric); color: #000; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 800; display: inline-flex; }
        
        @media (max-width: 960px) {
            .reservas-title { font-size: 42px; }
            .reserva-body { grid-template-columns: 1fr; }
        }
        
        @media (max-width: 640px) {
            .container { width: calc(100% - 48px); }
            .reservas-title { font-size: 36px; }
            .reserva-header { flex-direction: column; gap: 16px; }
            .reserva-footer { flex-direction: column; gap: 16px; align-items: start; }
        }
    </style>
</head>
<body>
    @include('components.header')

    <section class="reservas-section">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success mb-4" style="font-family: 'Gopher', sans-serif; background-color: #283300; color: #C9FF00; border: 2px solid #C9FF00;">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger mb-4" style="font-family: 'Gopher', sans-serif; background-color: #7a2626; color: #ff6b6b; border: 2px solid #ff6b6b;">
                    {{ session('error') }}
                </div>
            @endif

            <h1 class="reservas-title">Mis Reservas</h1>
            <p class="reservas-subtitle">Gestiona todas tus reservas de pistas de pádel</p>
            
            @if($reservas->count() > 0)
                <div class="reservas-grid">
                    @foreach($reservas as $reserva)
                        <div class="reserva-card">
                            <div class="reserva-header">
                                <h3 class="reserva-pista">{{ $reserva->pista->nombre }}</h3>
                                <span class="reserva-estado {{ $reserva->estado }}">{{ ucfirst($reserva->estado) }}</span>
                            </div>
                            
                            <div class="reserva-body">
                                <div class="reserva-info">
                                    <p class="reserva-label">Complejo</p>
                                    <p class="reserva-value">{{ $reserva->pista->complejo->nombre }}</p>
                                </div>
                                <div class="reserva-info">
                                    <p class="reserva-label">Fecha</p>
                                    <p class="reserva-value">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</p>
                                </div>
                                <div class="reserva-info">
                                    <p class="reserva-label">Horario</p>
                                    <p class="reserva-value">{{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') }}</p>
                                </div>
                                <div class="reserva-info">
                                    <p class="reserva-label">Duración</p>
                                    <p class="reserva-value">{{ \Carbon\Carbon::parse($reserva->hora_inicio)->diffInHours(\Carbon\Carbon::parse($reserva->hora_fin)) }}h</p>
                                </div>
                            </div>
                            
                            <div class="reserva-footer">
                                <p class="reserva-precio">{{ number_format($reserva->precio_total, 2) }}€</p>
                                <div class="d-flex gap-3 flex-wrap">
                                    @if($reserva->estado === 'confirmada' && $reserva->fecha_reserva >= now()->format('Y-m-d'))
                                        <a href="{{ route('reservas.editar', $reserva->id) }}" class="reserva-btn" style="background: #3B8080; min-width: 100px;">Editar</a>
                                        <form method="POST" action="{{ route('reservas.cancelar', $reserva->id) }}" class="d-inline" onsubmit="return confirm('¿Estás seguro de cancelar esta reserva?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="reserva-btn" style="background: #7a2626; color: #ff6b6b; min-width: 100px;">Cancelar</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('pistas.detalle', $reserva->pista->id) }}" class="reserva-btn" style="min-width: 110px;">Ver Pista</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <h3>No tienes reservas aún</h3>
                    <p>Reserva tu primera pista y empieza a disfrutar del pádel</p>
                    <a href="{{ route('complejos.index') }}" class="empty-btn">Explorar Complejos</a>
                </div>
            @endif
        </div>
    </section>

    @include('components.footer')
</body>
</html>
