<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis Torneos - Badia Padel Tour</title>
    <link rel="icon" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --electric: #c9ff00; --black: #0f0f0f; --dark-green: #4a5d2f; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Gopher', 'Inter', sans-serif; color: #fff; background: #0f0f0f; }
        .container { width: min(1200px, calc(100% - 96px)); margin: 0 auto; }
        
        .mis-torneos-section { padding: 80px 0 60px; }
        .mis-torneos-header { margin-bottom: 48px; }
        .mis-torneos-title { margin: 0 0 16px; font-size: 56px; color: var(--electric); font-weight: 800; }
        
        .torneos-list { display: flex; flex-direction: column; gap: 20px; }
        .torneo-item { background: #1a1a1a; border-radius: 12px; padding: 24px; display: grid; grid-template-columns: auto 1fr auto; gap: 24px; align-items: center; transition: background 0.2s; }
        .torneo-item:hover { background: #222; }
        
        .torneo-status { width: 60px; height: 60px; border-radius: 50%; background: var(--dark-green); display: flex; align-items: center; justify-content: center; font-size: 24px; }
        
        .torneo-info { flex: 1; }
        .torneo-name { margin: 0 0 8px; font-size: 20px; font-weight: 800; color: #fff; }
        .torneo-meta { margin: 0; font-size: 14px; color: #888; }
        
        .torneo-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
        .torneo-badge { background: var(--electric); color: #000; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 800; text-transform: uppercase; }
        .torneo-btn { background: #3a3a3a; color: #fff; border: none; border-radius: 8px; padding: 10px 20px; font-weight: 700; font-size: 13px; cursor: pointer; transition: background 0.2s; text-decoration: none; }
        .torneo-btn:hover { background: #4a4a4a; }
        
        @media (max-width: 960px) {
            .mis-torneos-title { font-size: 42px; }
            .torneo-item { grid-template-columns: 1fr; text-align: center; }
            .torneo-actions { align-items: center; }
        }
        
        @media (max-width: 640px) {
            .container { width: calc(100% - 48px); }
            .mis-torneos-title { font-size: 36px; }
        }
    </style>
</head>
<body>
    @include('components.header')

    <section class="mis-torneos-section">
        <div class="container">
            <div class="mis-torneos-header">
                <h1 class="mis-torneos-title">Mis Torneos</h1>
            </div>

            <!-- Lista de torneos -->
            <div class="torneos-list">
                @forelse($participaciones as $participacion)
                    <div class="torneo-item">
                        <div class="torneo-status">
                            @if($participacion->estado === 'Ganador')
                                🏆
                            @elseif($participacion->posicion && $participacion->posicion <= 3)
                                🥈
                            @else
                                ⚡
                            @endif
                        </div>
                        
                        <div class="torneo-info">
                            <h3 class="torneo-name">{{ $participacion->tournament->nombre }}</h3>
                            <p class="torneo-meta">
                                📍 {{ $participacion->tournament->complejo->nombre }} • 
                                📅 {{ $participacion->tournament->fecha_inicio->format('d/m/Y') }}
                            </p>
                        </div>
                        
                        <div class="torneo-actions">
                            <span class="torneo-badge">{{ $participacion->tournament->estado }}</span>
                            <a href="{{ route('torneos.show', $participacion->tournament->id) }}" class="torneo-btn">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 60px 20px; background: #1a1a1a; border-radius: 12px;">
                        <p style="font-size: 24px; color: #666; margin: 0 0 16px;">No estás inscrito en ningún torneo</p>
                        <a href="{{ route('torneos.index') }}" class="torneo-btn" style="display: inline-block; background: var(--electric); color: #000;">
                            Explorar Torneos
                        </a>
                    </div>
                @endforelse
            </div>

            @if($participaciones->hasPages())
                <div style="margin-top: 32px;">
                    {{ $participaciones->links() }}
                </div>
            @endif
        </div>
    </section>

    @include('components.footer')
</body>
</html>
