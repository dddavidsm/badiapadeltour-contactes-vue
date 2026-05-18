<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reserva Confirmada - Badia Padel Tour</title>
    <link rel="icon" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --electric: #c9ff00; --black: #0f0f0f; --teal: #3b8080; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Gopher', 'Inter', sans-serif; color: #fff; background: #0f0f0f; }
        .container { width: min(1200px, calc(100% - 96px)); margin: 0 auto; }
        
        .confirmacion-section { padding: 80px 0 60px; min-height: calc(100vh - 200px); display: flex; align-items: center; }
        .confirmacion-content { max-width: 700px; margin: 0 auto; text-align: center; }
        
        .success-icon { width: 80px; height: 80px; margin: 0 auto 32px; background: var(--electric); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .success-icon svg { width: 48px; height: 48px; }
        
        .confirmacion-title { margin: 0 0 16px; font-size: 48px; color: var(--electric); font-weight: 800; letter-spacing: -0.02em; line-height: 1.1; }
        .confirmacion-subtitle { margin: 0 0 48px; font-size: 18px; color: #999; font-weight: 500; }
        
        .reserva-card { background: #1a1a1a; border-radius: 14px; padding: 40px; text-align: left; margin-bottom: 32px; }
        .card-section { margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid #333; }
        .card-section:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
        
        .detail-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .detail-row:last-child { margin-bottom: 0; }
        .detail-label { font-size: 14px; color: #999; font-weight: 600; }
        .detail-value { font-size: 16px; color: #fff; font-weight: 700; }
        .detail-value.highlight { color: var(--electric); font-size: 18px; }
        .detail-value.price { color: var(--electric); font-size: 24px; }
        
        .badge { display: inline-block; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 700; text-transform: uppercase; }
        .badge-success { background: var(--electric); color: #000; }
        
        .actions { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .btn { border-radius: 8px; padding: 14px 0; font-weight: 800; font-size: 14px; text-transform: uppercase; letter-spacing: 0.02em; cursor: pointer; transition: all 0.2s; text-decoration: none; display: block; text-align: center; }
        .btn-primary { background: var(--electric); color: #000; border: none; }
        .btn-primary:hover { background: #b8ee00; color: #000; }
        .btn-secondary { background: transparent; color: #fff; border: 2px solid #fff; }
        .btn-secondary:hover { background: #fff; color: #000; }
        
        @media (max-width: 640px) {
            .container { width: calc(100% - 48px); }
            .confirmacion-title { font-size: 36px; }
            .reserva-card { padding: 28px; }
            .actions { grid-template-columns: 1fr; }
            .detail-row { flex-direction: column; align-items: flex-start; gap: 4px; }
        }
    </style>
</head>
<body>
    @include('components.header')

    <section class="confirmacion-section">
        <div class="container">
            <div class="confirmacion-content">
                <div class="success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0f0f0f">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                    </svg>
                </div>
                
                <h1 class="confirmacion-title">¡Reserva Confirmada!</h1>
                <p class="confirmacion-subtitle">Tu reserva ha sido procesada exitosamente</p>
                
                <div class="reserva-card">
                    <div class="card-section">
                        <div class="detail-row">
                            <span class="detail-label">Nombre Completo</span>
                            <span class="detail-value">{{ $reserva->user->name }} {{ $reserva->user->apellidos }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">DNI</span>
                            <span class="detail-value">{{ $reserva->user->dni }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Teléfono</span>
                            <span class="detail-value">{{ $reserva->user->telefono }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Email</span>
                            <span class="detail-value">{{ $reserva->user->email }}</span>
                        </div>
                    </div>
                    
                    <div class="card-section">
                        <div class="detail-row">
                            <span class="detail-label">Pista</span>
                            <span class="detail-value">{{ $reserva->pista->nombre }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Complejo</span>
                            <span class="detail-value">{{ $reserva->pista->complejo->nombre }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Tipo</span>
                            <span class="detail-value">{{ ucfirst($reserva->pista->tipo) }}</span>
                        </div>
                    </div>
                    
                    <div class="card-section">
                        <div class="detail-row">
                            <span class="detail-label">Fecha</span>
                            <span class="detail-value highlight">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Horario</span>
                            <span class="detail-value highlight">{{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Duración</span>
                            <span class="detail-value">
                                @php
                                    $inicio = \Carbon\Carbon::parse($reserva->hora_inicio);
                                    $fin = \Carbon\Carbon::parse($reserva->hora_fin);
                                    $horas = $fin->diffInHours($inicio);
                                    $minutos = $fin->diffInMinutes($inicio) % 60;
                                @endphp
                                {{ $horas }}h {{ $minutos }}min
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-section">
                        <div class="detail-row">
                            <span class="detail-label">Estado</span>
                            <span class="badge badge-success">{{ ucfirst($reserva->estado) }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Precio Total</span>
                            <span class="detail-value price">€{{ number_format($reserva->precio_total, 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="actions">
                    <a href="{{ route('reservas.mis-reservas') }}" class="btn btn-primary">Ver Mis Reservas</a>
                    <a href="{{ route('complejos.index') }}" class="btn btn-secondary">Reservar Otra Pista</a>
                </div>
            </div>
        </div>
    </section>

    @include('components.footer')
</body>
</html>
