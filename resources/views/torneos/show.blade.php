<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $torneo->nombre }} - Badia Padel Tour</title>
    <link rel="icon" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --electric: #c9ff00; --black: #0f0f0f; --dark-green: #4a5d2f; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Gopher', 'Inter', sans-serif; color: #fff; background: #0f0f0f; }
        .container { width: min(1200px, calc(100% - 96px)); margin: 0 auto; }
        
        .torneo-detail { padding: 80px 0 60px; }
        .torneo-hero { background: #1a1a1a; border-radius: 16px; overflow: hidden; margin-bottom: 40px; }
        .torneo-hero-img { width: 100%; height: 400px; background-size: cover; background-position: center; position: relative; }
        .torneo-hero-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(15,15,15,0.95) 0%, transparent 60%); }
        .torneo-hero-content { position: absolute; bottom: 0; left: 0; right: 0; padding: 32px; }
        .torneo-badge { display: inline-block; background: var(--electric); color: #000; padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 800; text-transform: uppercase; margin-bottom: 12px; }
        .torneo-title { margin: 0 0 12px; font-size: 48px; color: #fff; font-weight: 800; }
        .torneo-subtitle { margin: 0; font-size: 18px; color: #ccc; font-weight: 600; }
        
        .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 32px; }
        .main-content { display: flex; flex-direction: column; gap: 24px; }
        .sidebar { display: flex; flex-direction: column; gap: 24px; }
        
        .info-card { background: #1a1a1a; border-radius: 12px; padding: 24px; }
        .info-card-title { margin: 0 0 20px; font-size: 22px; color: var(--electric); font-weight: 800; }
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .info-item { display: flex; flex-direction: column; gap: 4px; }
        .info-label { font-size: 12px; color: #888; text-transform: uppercase; font-weight: 700; }
        .info-value { font-size: 16px; color: #fff; font-weight: 700; }
        
        .description { line-height: 1.7; color: #ccc; }
        
        .participants-list { display: flex; flex-direction: column; gap: 12px; max-height: 400px; overflow-y: auto; }
        .participant-item { display: flex; align-items: center; gap: 12px; padding: 12px; background: #222; border-radius: 8px; }
        .participant-position { width: 32px; height: 32px; background: var(--electric); color: #000; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; }
        .participant-name { flex: 1; font-weight: 600; color: #fff; }
        
        .action-card { background: var(--dark-green); border-radius: 12px; padding: 24px; text-align: center; }
        .action-btn { background: var(--electric); color: #000; border: none; border-radius: 8px; padding: 16px 32px; font-weight: 800; font-size: 15px; width: 100%; cursor: pointer; transition: background 0.2s; text-decoration: none; display: block; }
        .action-btn:hover { background: #b8ee00; }
        .action-btn.disabled { background: #3a3a3a; color: #666; cursor: not-allowed; }
        .action-info { margin-top: 16px; font-size: 13px; color: #ccc; }
        
        .alert { padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 600; }
        .alert-success { background: rgba(74, 222, 128, 0.1); color: #4ade80; border: 2px solid #4ade80; }
        .alert-error { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 2px solid #ef4444; }
        
        @media (max-width: 960px) {
            .content-grid { grid-template-columns: 1fr; }
            .torneo-title { font-size: 36px; }
            .torneo-hero-img { height: 300px; }
        }
    </style>
</head>
<body>
    @include('components.header')

    <section class="torneo-detail">
        <div class="container">
            <!-- Hero -->
            <div class="torneo-hero">
                @php
                    // Usar imagen del complejo al que pertenece el torneo
                    if ($torneo->complejo && $torneo->complejo->imagen) {
                        $imagenUrl = asset($torneo->complejo->imagen);
                    } else {
                        $imagenUrl = 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=1200&h=600&fit=crop';
                    }
                @endphp
                
                <div class="torneo-hero-img" style="background-image: url('{{ $imagenUrl }}');">
                    <div class="torneo-hero-overlay"></div>
                    <div class="torneo-hero-content">
                        <span class="torneo-badge">{{ $torneo->estado }}</span>
                        <h1 class="torneo-title">{{ $torneo->nombre }}</h1>
                        <p class="torneo-subtitle">📍 {{ $torneo->complejo->nombre }} • {{ $torneo->complejo->ciudad }}</p>
                    </div>
                </div>
            </div>

            <!-- Mensajes -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <!-- Contenido -->
            <div class="content-grid">
                <div class="main-content">
                    <!-- Información del torneo -->
                    <div class="info-card">
                        <h2 class="info-card-title">Información del Torneo</h2>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Fecha Inicio</span>
                                <span class="info-value">{{ $torneo->fecha_inicio->format('d/m/Y') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Fecha Fin</span>
                                <span class="info-value">{{ $torneo->fecha_fin->format('d/m/Y') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Nivel</span>
                                <span class="info-value">{{ $torneo->nivel }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Participantes</span>
                                <span class="info-value">{{ $torneo->participantes_actuales }} / {{ $torneo->max_participantes }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Estado</span>
                                <span class="info-value">{{ $torneo->estado }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    @if($torneo->descripcion)
                        <div class="info-card">
                            <h2 class="info-card-title">Descripción</h2>
                            <div class="description">{!! nl2br(e($torneo->descripcion)) !!}</div>
                        </div>
                    @endif

                    <!-- Participantes -->
                    @if($torneo->participantes->count() > 0)
                        <div class="info-card">
                            <h2 class="info-card-title">Participantes ({{ $torneo->participantes->count() }})</h2>
                            <div class="participants-list">
                                @foreach($torneo->participantes->sortBy('posicion') as $participante)
                                    <div class="participant-item">
                                        <div class="participant-position">
                                            {{ $participante->posicion ?? '•' }}
                                        </div>
                                        <div class="participant-name">{{ $participante->user->name }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="sidebar">
                    <div class="action-card">
                        @auth
                            @if($usuarioInscrito)
                                <div style="margin-bottom: 16px;">
                                    <div style="font-size: 48px; margin-bottom: 12px;">✅</div>
                                    <h3 style="margin: 0 0 8px; color: var(--electric); font-size: 20px;">Inscripción Confirmada</h3>
                                    <p style="margin: 0; color: #ccc; font-size: 14px;">Ya estás participando en este torneo</p>
                                </div>
                            @elseif($torneo->estado === 'Abierto' && $torneo->estaAbierto())
                                <form action="{{ route('torneos.inscribir', $torneo->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="action-btn">Inscribirme Ahora</button>
                                </form>
                                <p class="action-info">¡Reserva tu lugar y compite!</p>
                            @else
                                <button class="action-btn disabled" disabled>Inscripciones Cerradas</button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="action-btn">Iniciar Sesión para Inscribirte</a>
                        @endauth
                    </div>

                    <!-- Info del complejo -->
                    <div class="info-card">
                        <h3 style="margin: 0 0 16px; color: var(--electric); font-size: 18px;">Ubicación</h3>
                        <div style="margin-bottom: 12px;">
                            <strong style="color: #fff; display: block; margin-bottom: 4px;">{{ $torneo->complejo->nombre }}</strong>
                            <p style="margin: 0; color: #999; font-size: 14px;">{{ $torneo->complejo->direccion }}</p>
                            <p style="margin: 4px 0 0; color: #999; font-size: 14px;">{{ $torneo->complejo->ciudad }}</p>
                        </div>
                        @if($torneo->complejo->telefono)
                            <p style="margin: 8px 0 0; color: #ccc; font-size: 14px;">📞 {{ $torneo->complejo->telefono }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.footer')
</body>
</html>
