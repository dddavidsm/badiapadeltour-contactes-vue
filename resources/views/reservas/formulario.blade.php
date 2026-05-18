<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservar Pista - Badia Padel Tour</title>
    <link rel="icon" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --electric: #c9ff00; --black: #0f0f0f; --teal: #3b8080; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Gopher', 'Inter', sans-serif; color: #fff; background: #0f0f0f; }
        .container { width: min(1200px, calc(100% - 96px)); margin: 0 auto; }
        
        .reserva-section { padding: 80px 0 60px; }
        .reserva-header { margin-bottom: 16px; }
        .reserva-title { margin: 0 0 8px; font-size: 48px; color: var(--electric); font-weight: 800; letter-spacing: -0.02em; line-height: 1.1; }
        .reserva-subtitle { margin: 0 0 56px; font-size: 16px; color: #999; font-weight: 500; }
        
        .reserva-content { display: grid; grid-template-columns: 1fr 400px; gap: 32px; }
        
        .form-card { background: #1a1a1a; border-radius: 14px; padding: 40px; }
        
        .form-group { margin-bottom: 24px; }
        .form-label { display: block; margin: 0 0 8px; font-size: 14px; font-weight: 700; color: #fff; }
        .form-input { width: 100%; background: #2a2a2a; border: none; border-radius: 6px; padding: 14px 16px; font-size: 15px; color: #fff; font-family: 'Gopher', 'Inter', sans-serif; outline: none; transition: background 0.2s; }
        .form-input::placeholder { color: #666; }
        .form-input:focus { background: #333; }
        .form-input:disabled { background: #1a1a1a; color: #666; cursor: not-allowed; }
        .form-hint { display: block; margin-top: 6px; font-size: 12px; color: #666; }
        
        .horarios-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-top: 12px; }
        .horario-btn { background: #2a2a2a; border: 2px solid #3a3a3a; border-radius: 8px; padding: 12px 10px; font-size: 13px; color: #fff; cursor: pointer; transition: all 0.2s; font-weight: 600; }
        .horario-btn:hover:not(:disabled) { border-color: var(--electric); }
        .horario-btn.disponible:hover { background: rgba(201, 255, 0, 0.1); }
        .horario-btn.disponible.selected { background: var(--electric); color: #000; border-color: var(--electric); }
        .horario-btn:disabled { background: #1a1a1a; color: #555; cursor: not-allowed; border-color: #7a4a4a; }
        .horario-btn:disabled::after { content: ' (Ocupada)'; font-size: 11px; }
        
        .duracion-display { background: #2a2a2a; border-radius: 6px; padding: 12px 16px; margin-top: 12px; display: flex; justify-content: space-between; align-items: center; }
        .duracion-label { font-size: 13px; color: #888; }
        .duracion-value { font-size: 16px; font-weight: 800; color: var(--electric); }
        
        .price-summary { background: var(--teal); border-radius: 10px; padding: 24px; margin-bottom: 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .price-item { }
        .price-label { margin: 0 0 6px; font-size: 13px; font-weight: 600; color: rgba(255,255,255,0.8); }
        .price-value { margin: 0; font-size: 24px; font-weight: 800; color: var(--electric); }
        
        .form-actions { display: grid; gap: 12px; }
        .btn-primary { background: var(--electric); color: #000; border: none; border-radius: 8px; padding: 14px 0; font-weight: 800; font-size: 14px; width: 100%; text-transform: uppercase; letter-spacing: 0.02em; cursor: pointer; transition: background 0.2s; text-decoration: none; display: block; text-align: center; }
        .btn-primary:hover { background: #b8ee00; color: #000; }
        .btn-primary:disabled { background: #666; color: #999; cursor: not-allowed; }
        .btn-secondary { background: transparent; color: #fff; border: 2px solid #fff; border-radius: 8px; padding: 12px 0; font-weight: 800; font-size: 14px; width: 100%; text-transform: uppercase; letter-spacing: 0.02em; cursor: pointer; transition: all 0.2s; text-decoration: none; display: block; text-align: center; }
        .btn-secondary:hover { background: #fff; color: #000; }
        
        .info-card { background: #1a1a1a; border-radius: 14px; padding: 32px; }
        .info-title { margin: 0 0 20px; font-size: 18px; font-weight: 800; color: #fff; }
        .info-text { margin: 0 0 16px; font-size: 14px; color: #999; line-height: 1.6; }
        .contact-btn { background: var(--electric); color: #000; border: none; border-radius: 8px; padding: 12px 0; font-weight: 800; font-size: 13px; width: 100%; text-transform: uppercase; letter-spacing: 0.02em; cursor: pointer; transition: background 0.2s; text-decoration: none; display: block; text-align: center; }
        .contact-btn:hover { background: #b8ee00; color: #000; }
        
        .error-message { color: #ff4444; font-size: 13px; margin-top: 6px; display: block; }
        .legend { display: flex; gap: 16px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #2a2a2a; font-size: 12px; }
        .legend-item { display: flex; align-items: center; gap: 6px; }
        .legend-color { width: 16px; height: 16px; border-radius: 4px; }
        
        @media (max-width: 960px) {
            .reserva-content { grid-template-columns: 1fr; }
            .reserva-title { font-size: 36px; }
            .horarios-grid { grid-template-columns: repeat(3, 1fr); }
        }
        
        @media (max-width: 640px) {
            .container { width: calc(100% - 48px); }
            .reserva-title { font-size: 32px; }
            .form-card, .info-card { padding: 28px; }
            .price-summary { grid-template-columns: 1fr; }
            .horarios-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    @include('components.header')

    <section class="reserva-section">
        <div class="container">
            <div class="reserva-header">
                <h1 class="reserva-title">Formulario De Reserva</h1>
                <p class="reserva-subtitle">{{ $pista->nombre }} - {{ ucfirst($pista->tipo) }} - {{ $pista->complejo->nombre }}</p>
            </div>
            
            <div class="reserva-content">
                <div class="form-card">
                    @if ($errors->any())
                        <div style="background: #7a4a4a; border: 1px solid #ff6b6b; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                            <p style="margin: 0 0 8px; color: #ff6b6b; font-weight: 700;">Error en la reserva:</p>
                            @foreach ($errors->all() as $error)
                                <p style="margin: 0 0 4px; color: #fff; font-size: 14px;">• {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    
                    <form action="{{ route('reservas.guardar', $pista->id) }}" method="POST" id="formReserva">
                        @csrf
                        
                        <div class="form-group">
                            <label for="fecha_reserva" class="form-label">Selecciona Una Fecha *</label>
                            <input 
                                type="date" 
                                id="fecha_reserva"
                                name="fecha"
                                class="form-input"
                                value="{{ old('fecha') }}"
                                min="{{ now()->format('Y-m-d') }}"
                                max="{{ now()->addDays(30)->format('Y-m-d') }}"
                                required
                            >
                            @error('fecha')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Hora De Inicio *</label>
                            <div class="horarios-grid" id="horariosInicio">
                                <p style="grid-column: 1/-1; color: #666; font-size: 13px;">Selecciona una fecha primero</p>
                            </div>
                            @error('hora_inicio')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <input type="hidden" id="hora_inicio" name="hora_inicio" value="{{ old('hora_inicio') }}">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Hora De Fin *</label>
                            <div class="horarios-grid" id="horariosFin">
                                <p style="grid-column: 1/-1; color: #666; font-size: 13px;">Selecciona una hora de inicio</p>
                            </div>
                            @error('hora_fin')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <input type="hidden" id="hora_fin" name="hora_fin" value="{{ old('hora_fin') }}">
                        </div>
                        
                        <div class="duracion-display">
                            <span class="duracion-label">Duración De Reserva</span>
                            <span class="duracion-value" id="duracion">-</span>
                        </div>
                        
                        <div class="price-summary">
                            <div class="price-item">
                                <p class="price-label">Precio Estimado</p>
                                <p class="price-value" id="precioEstimado">-</p>
                            </div>
                            <div class="price-item">
                                <p class="price-label">Precio/Hora</p>
                                <p class="price-value">{{ number_format($pista->precio_hora, 2) }}€</p>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-primary" id="btnReservar" disabled>Confirmar Reserva</button>
                            <a href="{{ route('pistas.detalle', $pista->id) }}" class="btn-secondary">Cancelar</a>
                        </div>
                        
                        <div class="legend">
                            <div class="legend-item">
                                <div class="legend-color" style="background: #2a2a2a;"></div>
                                <span>Disponible</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #7a4a4a;"></div>
                                <span>Ocupada</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: var(--electric);"></div>
                                <span>Seleccionada</span>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="info-card">
                    <h3 class="info-title">¿Tienes Dudas?</h3>
                    <p class="info-text">Resolvemos tus dudas sobre la reserva y disponibilidad.</p>
                    <p class="info-text"><strong>Horario:</strong> De 8:00 a 21:00</p>
                    <p class="info-text"><strong>Duración mínima:</strong> 1 hora</p>
                    <a href="{{ route('contacto') }}" class="contact-btn">Contáctanos</a>
                </div>
            </div>
        </div>
    </section>

    @include('components.footer')
    
    <script>
        const pistaId = {{ $pista->id }};
        const precioHora = {{ $pista->precio_hora }};
        
        const fechaInput = document.getElementById('fecha_reserva');
        const horariosInicioDiv = document.getElementById('horariosInicio');
        const horariosFinDiv = document.getElementById('horariosFin');
        const horaInicioInput = document.getElementById('hora_inicio');
        const horaFinInput = document.getElementById('hora_fin');
        const precioEstimadoSpan = document.getElementById('precioEstimado');
        const duracionSpan = document.getElementById('duracion');
        const btnReservar = document.getElementById('btnReservar');
        
        // Cargar horarios al cambiar fecha
        fechaInput.addEventListener('change', async function() {
            const fecha = this.value;
            if (!fecha) return;
            
            try {
                const response = await fetch(`/api/disponibilidad/${pistaId}/${fecha}`);
                const horarios = await response.json();
                
                // Renderizar horarios de inicio
                horariosInicioDiv.innerHTML = '';
                horarios.forEach(h => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = h.disponible ? 'horario-btn disponible' : 'horario-btn';
                    btn.disabled = !h.disponible;
                    btn.textContent = h.hora;
                    btn.dataset.hora = h.hora;
                    
                    if (h.disponible) {
                        btn.addEventListener('click', () => selectHoraInicio(btn, h.hora, horarios));
                    }
                    
                    horariosInicioDiv.appendChild(btn);
                });
                
                // Reset horarios fin
                horariosFinDiv.innerHTML = '<p style="grid-column: 1/-1; color: #666; font-size: 13px;">Selecciona una hora de inicio</p>';
                horaInicioInput.value = '';
                horaFinInput.value = '';
                updatePrecio();
            } catch (error) {
                console.error('Error cargando horarios:', error);
            }
        });
        
        function selectHoraInicio(btn, hora, todosLosHorarios) {
            // Remover selección anterior
            document.querySelectorAll('#horariosInicio .horario-btn.selected').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            
            horaInicioInput.value = hora;
            
            // Encontrar índice del hora inicio
            const indexInicio = todosLosHorarios.findIndex(h => h.hora === hora);
            
            // Generar opciones de fin: solo horas posteriores disponibles
            horariosFinDiv.innerHTML = '';
            for (let i = indexInicio + 1; i < todosLosHorarios.length; i++) {
                const h = todosLosHorarios[i];
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = h.disponible ? 'horario-btn disponible' : 'horario-btn';
                btn.disabled = !h.disponible;
                btn.textContent = h.hora;
                btn.dataset.hora = h.hora;
                
                if (h.disponible) {
                    btn.addEventListener('click', () => selectHoraFin(btn, h.hora));
                }
                
                horariosFinDiv.appendChild(btn);
            }
        }
        
        function selectHoraFin(btn, hora) {
            // Remover selección anterior
            document.querySelectorAll('#horariosFin .horario-btn.selected').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            
            horaFinInput.value = hora;
            updatePrecio();
        }
        
        function updatePrecio() {
            const fecha = fechaInput.value;
            const horaInicio = horaInicioInput.value;
            const horaFin = horaFinInput.value;
            
            if (!fecha || !horaInicio || !horaFin) {
                precioEstimadoSpan.textContent = '-';
                duracionSpan.textContent = '-';
                btnReservar.disabled = true;
                return;
            }
            
            // Calcular horas
            const inicio = new Date(`2000-01-01 ${horaInicio}`);
            const fin = new Date(`2000-01-01 ${horaFin}`);
            const horas = (fin - inicio) / (1000 * 60 * 60);
            
            const precio = (horas * precioHora).toFixed(2);
            precioEstimadoSpan.textContent = precio + '€';
            duracionSpan.textContent = horas + 'h';
            btnReservar.disabled = false;
        }
    </script>
</body>
</html>