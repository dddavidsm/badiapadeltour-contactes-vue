<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BPT - Badia Padel Tour</title>
    <link rel="icon" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --electric: #c9ff00; --black: #0f0f0f; --footer-teal: #3b8080; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Gopher', 'Inter', sans-serif; color: #111; background: #fff; }

        /* Hero */
        .hero { position: relative; background: #000; color: #fff; overflow: hidden; }
        .hero video { width: 100%; height: 680px; object-fit: cover; display: block; filter: brightness(0.55); }
        .hero-overlay { position: absolute; inset: 0; display: grid; place-items: center; text-align: center; padding: 24px; }
        .hero-content { max-width: 680px; }
        .hero-kicker { color: var(--electric); font-weight: 800; letter-spacing: 0.08em; margin-bottom: 8px; font-size: 16px; }
        .hero-title { margin: 0 0 10px; font-size: 48px; line-height: 1.05; font-weight: 800; letter-spacing: -0.02em; }
        .hero-cta { background: var(--electric); color: #000; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 800; display: inline-flex; margin-top: 12px; }

        /* Stats */
        .stats { padding: 52px 0 48px; background: #fff; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 28px; text-align: center; align-items: start; }
        .stat-number { margin: 0; font-size: 34px; font-weight: 800; color: #000; letter-spacing: -0.01em; }
        .stat-number .plus { color: var(--electric); margin-left: 4px; }
        .stat-label { margin: 8px 0 0; color: #7a7a7a; font-size: 14px; font-weight: 500; letter-spacing: 0.01em; }

        /* Complexes */
        .complexes { background: var(--electric); padding: 56px 0 52px; text-align: center; }
        .complexes h4 { margin: 0; color: #1d1d1d; font-weight: 600; letter-spacing: 0.01em; font-size: 18px; }
        .complexes h2 { margin: 6px 0 32px; font-size: 30px; color: #0a0a0a; font-weight: 800; letter-spacing: -0.01em; }
        .complex-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
        .complex-card { background: #fff; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 28px rgba(0,0,0,0.12); display: flex; flex-direction: column; }
        .complex-thumb { width: 100%; height: 240px; background-size: cover; background-position: center; background-repeat: no-repeat; background-color: #d5e872; position: relative; display: flex; align-items: center; justify-content: center; }
        .complex-thumb::before { content: ''; position: absolute; inset: 0; background: rgba(0,0,0,0.35); }
        .complex-overlay-title { position: relative; z-index: 1; color: #fff; font-size: 26px; font-weight: 800; text-align: center; letter-spacing: 0.02em; text-transform: uppercase; }
        .complex-body { padding: 14px 16px 18px; text-align: left; color: #000; }
        .complex-meta { margin: 0 0 6px; font-size: 12px; font-weight: 600; color: #1e1e1e; display: flex; align-items: center; gap: 6px; }
        .complex-text { margin: 0 0 12px; font-size: 12px; color: #3a3a3a; line-height: 1.4; }
        .complex-btn { background: var(--electric); color: #000; border: none; border-radius: 6px; padding: 10px 0; font-weight: 800; font-size: 12px; width: 100%; text-transform: uppercase; }
        .complex-cta { margin-top: 24px; display: inline-flex; background: #111; color: var(--electric); padding: 12px 26px; border-radius: 6px; font-weight: 800; text-decoration: none; }

        /* Map */
        .map { background: #0c0d0c; padding: 0; }
        .map-frame { position: relative; width: 100%; max-width: 1084px; margin: 0 auto; }
        .map-frame img { display: block; width: 100%; height: auto; }
        .map-text { position: absolute; left: 48px; bottom: 48px; color: #fff; text-align: left; }
        .map-text h4 { margin: 0 0 8px; font-size: 16px; font-weight: 500; letter-spacing: 0.02em; }
        .map-text h2 { margin: 0; font-size: 36px; font-weight: 800; letter-spacing: -0.02em; }

        @media (max-width: 1100px) {
        }

        @media (max-width: 960px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .complex-grid { grid-template-columns: 1fr 1fr; }
            .map-text { left: 24px; bottom: 28px; }
        }

        @media (max-width: 640px) {
            .hero video { height: 380px; }
            .hero-title { font-size: 34px; }
            .stats { padding: 40px 0; }
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 20px; }
            .complex-grid { grid-template-columns: 1fr; }
            .map-text h2 { font-size: 28px; }
        }
    </style>
</head>
<body>
    @include('components.header')

    <section class="hero" aria-label="Hero video">
        <video src="/assets/video.mp4" autoplay muted loop playsinline></video>
        <div class="hero-overlay">
            <div class="hero-content">
                <div class="hero-kicker">PADEL CLUB</div>
                <h1 class="hero-title">¿Listo Para<br>Para Jugar?</h1>
                <a class="hero-cta" href="{{ route('complejos.index') }}">VER COMPLEJOS</a>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat">
                    <p class="stat-number">{{ $stats['pistas'] ?? 0 }}<span class="plus">+</span></p>
                    <p class="stat-label">Pistas Disponibles</p>
                </div>
                <div class="stat">
                    <p class="stat-number">{{ $stats['usuarios'] ?? 0 }}<span class="plus">+</span></p>
                    <p class="stat-label">Usuarios Registrados</p>
                </div>
                <div class="stat">
                    <p class="stat-number">{{ $stats['complejos'] ?? 0 }}<span class="plus">+</span></p>
                    <p class="stat-label">Complejos Deportivos</p>
                </div>
                <div class="stat">
                    <p class="stat-number">{{ $stats['reservas'] ?? 0 }}<span class="plus">+</span></p>
                    <p class="stat-label">Reservas Realizadas</p>
                </div>
            </div>
        </div>
    </section>

    <section class="complexes">
        <div class="container">
            <h4>Nuestros Complejos</h4>
            <h2>Elige Dónde Quieres Jugar</h2>
            <div class="complex-grid">
                @foreach($complejos->take(3) as $complejo)
                    <div class="complex-card">
                        <div class="complex-thumb" style="background-image:url('{{ $complejo->imagen ? asset($complejo->imagen) : '/assets/complejos/complejo_badia.png' }}')">
                            <h3 class="complex-overlay-title">{{ strtoupper($complejo->nombre) }}</h3>
                        </div>
                        <div class="complex-body">
                            <p class="complex-meta"><img src="/assets/location_icon.svg" alt="location" style="height: 18px; width: auto; vertical-align: middle; margin-right: 6px; filter: brightness(0);"> {{ $complejo->direccion }}</p>
                            <p class="complex-text">{{ $complejo->descripcion ?? 'Pistas de pádel de primer nivel con tecnología de última generación.' }}</p>
                            <a href="{{ route('pistas.index', ['complejo_id' => $complejo->id]) }}" class="complex-btn" style="display: inline-block; text-align: center; text-decoration: none;">VER PISTAS DEL COMPLEJO</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <a class="complex-cta" href="{{ route('complejos.index') }}">VER TODOS LOS COMPLEJOS</a>
        </div>
    </section>

    <section class="map" aria-label="Mapa de ubicaciones">
        <div class="map-frame">
            <img src="/assets/MAPA.png" alt="Mapa Badia Padel Tour">
            <div class="map-text">
                <h4>Ubicaciones</h4>
                <h2>Seguimos Creciendo Día A Día</h2>
            </div>
        </div>
    </section>
    @include('components.footer')
</body>
</html>