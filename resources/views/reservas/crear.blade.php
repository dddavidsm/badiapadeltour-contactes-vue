@extends('layouts.base')

@section('title', 'Reservar pista - Badia Padel Tour')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">Formulario de Reserva</h1>
            <p class="text-muted">Pista: <strong>{{ $pista->nombre }}</strong> - {{ $pista->complejo->nombre }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('pistas.detalle', $pista->id) }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('reservas.guardar', $pista->id) }}" method="POST" novalidate>
                        @csrf

                        {{-- Información de la pista (resumen) --}}
                        <div class="alert alert-light border mb-4" role="alert">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Tipo de pista</small>
                                    <p class="lead">{{ ucfirst($pista->tipo) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Precio por hora</small>
                                    <p class="lead text-success">€{{ number_format($pista->precio_hora, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Fecha de reserva --}}
                        <div class="mb-3">
                            <label for="fecha_reserva" class="form-label">
                                Fecha de reserva <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="date" 
                                class="form-control @error('fecha_reserva') is-invalid @enderror" 
                                id="fecha_reserva"
                                name="fecha_reserva"
                                value="{{ old('fecha_reserva') }}"
                                min="{{ now()->format('Y-m-d') }}"
                                required
                            >
                            @error('fecha_reserva')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Hora inicio --}}
                        <div class="mb-3">
                            <label for="hora_inicio" class="form-label">
                                Hora de inicio <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="time" 
                                class="form-control @error('hora_inicio') is-invalid @enderror" 
                                id="hora_inicio"
                                name="hora_inicio"
                                value="{{ old('hora_inicio') }}"
                                required
                            >
                            @error('hora_inicio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Formato: HH:mm (ej: 09:00)</small>
                        </div>

                        {{-- Hora fin --}}
                        <div class="mb-3">
                            <label for="hora_fin" class="form-label">
                                Hora de fin <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="time" 
                                class="form-control @error('hora_fin') is-invalid @enderror" 
                                id="hora_fin"
                                name="hora_fin"
                                value="{{ old('hora_fin') }}"
                                required
                            >
                            @error('hora_fin')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Debe ser posterior a la hora de inicio</small>
                        </div>

                        {{-- Resumen de precio --}}
                        <div class="alert alert-info mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <small class="text-muted">Precio estimado</small>
                                    <h5 id="precioEstimado" class="text-info mb-0">-</h5>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small class="text-muted d-block">Duración aproximada</small>
                                    <span id="duracionEstimada" class="small">-</span>
                                </div>
                            </div>
                        </div>

                        {{-- Botones de acción --}}
                        <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-lg btn-dark">
                                Confirmar reserva
                            </button>
                            <a href="{{ route('pistas.detalle', $pista->id) }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Información útil</h5>
                    <p class="small">
                        <strong>Pista:</strong><br>
                        {{ $pista->nombre }}<br>
                        <small class="text-muted">{{ $pista->complejo->nombre }}</small>
                    </p>
                    <hr>
                    <p class="small">
                        <strong>Precio por hora:</strong><br>
                        <span class="text-success">€{{ number_format($pista->precio_hora, 2) }}</span>
                    </p>
                    <p class="small">
                        <strong>Tipo:</strong><br>
                        {{ ucfirst($pista->tipo) }}
                    </p>
                    @if($pista->es_dobles)
                        <p class="small">
                            <strong>Apta para dobles</strong><br>
                            <span class="badge bg-success">Sí</span>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Calcular precio y duración en tiempo real
    const horaInicio = document.getElementById('hora_inicio');
    const horaFin = document.getElementById('hora_fin');
    const precioEstimado = document.getElementById('precioEstimado');
    const duracionEstimada = document.getElementById('duracionEstimada');
    const precioPorHora = {{ $pista->precio_hora }};

    function actualizarPrecio() {
        if (horaInicio.value && horaFin.value) {
            const inicio = new Date(`2025-01-01T${horaInicio.value}`);
            const fin = new Date(`2025-01-01T${horaFin.value}`);
            
            if (fin > inicio) {
                const horas = (fin - inicio) / (1000 * 60 * 60);
                const precioTotal = (horas * precioPorHora).toFixed(2);
                const minutos = ((fin - inicio) / (1000 * 60)) % 60;
                
                precioEstimado.textContent = '€' + precioTotal;
                duracionEstimada.textContent = `${Math.floor(horas)}h ${Math.round(minutos)}min`;
            }
        }
    }

    horaInicio.addEventListener('change', actualizarPrecio);
    horaFin.addEventListener('change', actualizarPrecio);
    </script>
</div>
@endsection