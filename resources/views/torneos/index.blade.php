<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Torneos - Badia Padel Tour</title>
    <link rel="icon" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --electric: #c9ff00; --black: #0f0f0f; --dark-green: #4a5d2f; --turtle-green: #5a6d3f; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Gopher', 'Inter', sans-serif; color: #fff; background: #0f0f0f; }
        .container { width: min(1200px, calc(100% - 96px)); margin: 0 auto; }
        
        .torneos-section { padding: 80px 0 60px; }
        .torneos-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; gap: 32px; }
        .torneos-title { margin: 0; font-size: 56px; color: var(--electric); font-weight: 800; letter-spacing: -0.02em; line-height: 1.1; }
        .torneos-subtitle { margin: 12px 0 0; font-size: 18px; color: #999; font-weight: 600; line-height: 1.5; }
        
        .filters-container { background: #1a1a1a; border-radius: 12px; padding: 24px; margin-bottom: 32px; }
        .filters-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
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
        
        .quick-actions { display: flex; gap: 12px; margin-bottom: 32px; }
        .quick-action-btn { background: var(--dark-green); color: var(--electric); border: none; border-radius: 10px; padding: 14px 24px; font-weight: 700; font-size: 15px; cursor: pointer; transition: background 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .quick-action-btn:hover { background: var(--turtle-green); }
        
        .torneos-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .torneo-card { background: #1a1a1a; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 28px rgba(0,0,0,0.3); transition: transform 0.3s, box-shadow 0.3s; text-decoration: none; display: flex; flex-direction: column; position: relative; }
        .torneo-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(201, 255, 0, 0.2); }
        
        .torneo-badge { position: absolute; top: 12px; right: 12px; background: var(--electric); color: #000; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 800; text-transform: uppercase; z-index: 2; }
        .torneo-badge.finalizado { background: #666; color: #fff; }
        .torneo-badge.en-curso { background: #ff6b00; color: #fff; }
        
        .torneo-thumb { width: 100%; height: 220px; background-size: cover; background-position: center; background-repeat: no-repeat; background-color: #2a2a2a; position: relative; }
        .torneo-thumb::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(15,15,15,0.8) 0%, transparent 60%); }
        
        .torneo-body { padding: 18px 20px 20px; flex: 1; display: flex; flex-direction: column; }
        .torneo-name { margin: 0 0 8px; font-size: 20px; font-weight: 800; color: #fff; letter-spacing: 0.01em; line-height: 1.3; }
        .torneo-location { margin: 0 0 12px; font-size: 13px; font-weight: 600; color: #999; }
        .torneo-info { display: flex; flex-direction: column; gap: 6px; margin-bottom: 12px; }
        .torneo-info-item { font-size: 13px; color: #ccc; display: flex; align-items: center; gap: 6px; }
        .torneo-info-item strong { color: var(--electric); font-weight: 700; }
        .torneo-participants { margin: 12px 0 16px; font-size: 13px; color: #ccc; }
        .torneo-participants strong { color: #fff; }
        .torneo-btn { background: var(--electric); color: #000; border: none; border-radius: 8px; padding: 12px 0; font-weight: 800; font-size: 13px; width: 100%; text-transform: uppercase; letter-spacing: 0.02em; margin-top: auto; cursor: pointer; transition: background 0.2s; text-decoration: none; display: block; text-align: center; }
        .torneo-btn:hover { background: #b8ee00; }
        .torneo-btn.disabled { background: #3a3a3a; color: #666; cursor: not-allowed; }
        
        .no-results { text-align: center; padding: 60px 20px; }
        .no-results-title { font-size: 24px; color: #666; margin: 0 0 8px; }
        .no-results-text { font-size: 16px; color: #888; }
        
        @media (max-width: 960px) {
            .torneos-grid { grid-template-columns: 1fr 1fr; }
            .torneos-title { font-size: 42px; }
            .torneos-header { flex-direction: column; }
        }
        
        @media (max-width: 640px) {
            .container { width: calc(100% - 48px); }
            .torneos-grid { grid-template-columns: 1fr; }
            .torneos-title { font-size: 36px; }
            .quick-actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    @include('components.header')

    <section class="torneos-section">
        <div class="container">
            <div class="torneos-header">
                <div>
                    <h1 class="torneos-title">Torneos<br>BPT</h1>
                    <p class="torneos-subtitle">Compite en los mejores torneos de pádel de la zona</p>
                </div>
            </div>
            
            <!-- Acciones rápidas -->
            @auth
            <div class="quick-actions">
                <a href="{{ route('torneos.mis-torneos') }}" class="quick-action-btn">
                    📋 Mis Torneos
                </a>
            </div>
            @endauth
            
            <!-- Filtros -->
            <div class="filters-container">
                <form method="GET" action="{{ route('torneos.index') }}" id="filterForm">
                    <div class="filters-grid">
                        <!-- Filtro por Complejo -->
                        <div class="filter-group">
                            <label class="filter-label" for="complejo">Complejo</label>
                            <select name="complejo" id="complejo" class="filter-select">
                                <option value="">Todos los complejos</option>
                                @foreach($complejos as $comp)
                                    <option value="{{ $comp->id }}" {{ request('complejo') == $comp->id ? 'selected' : '' }}>
                                        {{ $comp->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Filtro por Estado -->
                        <div class="filter-group">
                            <label class="filter-label" for="estado">Estado</label>
                            <select name="estado" id="estado" class="filter-select">
                                <option value="">Todos los estados</option>
                                <option value="Abierto" {{ request('estado') == 'Abierto' ? 'selected' : '' }}>Abierto</option>
                                <option value="En Curso" {{ request('estado') == 'En Curso' ? 'selected' : '' }}>En Curso</option>
                                <option value="Finalizado" {{ request('estado') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                            </select>
                        </div>
                        
                        <!-- Filtro por Nivel -->
                        <div class="filter-group">
                            <label class="filter-label" for="nivel">Nivel</label>
                            <select name="nivel" id="nivel" class="filter-select">
                                <option value="">Todos los niveles</option>
                                <option value="Principiante" {{ request('nivel') == 'Principiante' ? 'selected' : '' }}>Principiante</option>
                                <option value="Intermedio" {{ request('nivel') == 'Intermedio' ? 'selected' : '' }}>Intermedio</option>
                                <option value="Avanzado" {{ request('nivel') == 'Avanzado' ? 'selected' : '' }}>Avanzado</option>
                                <option value="Profesional" {{ request('nivel') == 'Profesional' ? 'selected' : '' }}>Profesional</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="filters-actions">
                        <button type="submit" class="filter-btn">Aplicar Filtros</button>
                        <a href="{{ route('torneos.index') }}" class="filter-btn filter-btn-reset">Limpiar</a>
                    </div>
                </form>
            </div>
            
            <!-- Grid de torneos -->
            @if($torneos->count() > 0)
                <div class="torneos-grid">
                    @foreach($torneos as $torneo)
                        <div class="torneo-card">
                            <span class="torneo-badge {{ strtolower(str_replace(' ', '-', $torneo->estado)) }}">
                                {{ $torneo->estado }}
                            </span>
                            
                            @php
                                // Usar imagen del complejo al que pertenece el torneo
                                if ($torneo->complejo && $torneo->complejo->imagen) {
                                    $imagenUrl = asset($torneo->complejo->imagen);
                                } else {
                                    $imagenUrl = 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=600&h=400&fit=crop';
                                }
                            @endphp
                            
                            <div class="torneo-thumb" style="background-image: url('{{ $imagenUrl }}');"></div>
                            
                            <div class="torneo-body">
                                <h3 class="torneo-name">{{ $torneo->nombre }}</h3>
                                <p class="torneo-location">📍 {{ $torneo->complejo->nombre }}</p>
                                
                                <div class="torneo-info">
                                    <div class="torneo-info-item">
                                        📅 <strong>{{ $torneo->fecha_inicio->format('d/m/Y') }}</strong> 
                                        @if($torneo->fecha_inicio != $torneo->fecha_fin)
                                            - {{ $torneo->fecha_fin->format('d/m/Y') }}
                                        @endif
                                    </div>
                                    <div class="torneo-info-item">
                                        🎯 Nivel: <strong>{{ $torneo->nivel }}</strong>
                                    </div>
                                </div>
                                
                                <p class="torneo-participants">
                                    <strong>{{ $torneo->participantes_actuales }}</strong> / {{ $torneo->max_participantes }} participantes
                                </p>
                                
                                <a href="{{ route('torneos.show', $torneo->id) }}" class="torneo-btn">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Paginación -->
                <div style="margin-top: 48px;">
                    {{ $torneos->links() }}
                </div>
            @else
                <div class="no-results">
                    <h3 class="no-results-title">No se encontraron torneos</h3>
                    <p class="no-results-text">Prueba ajustando los filtros</p>
                </div>
            @endif
        </div>
    </section>

    @include('components.footer')
</body>
</html>
