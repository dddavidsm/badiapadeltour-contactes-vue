@extends('layouts.base')

@section('title', 'Editar Reserva - BPT')

@section('content')
<div class="container my-5 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4" style="font-family: 'Gopher', sans-serif; font-weight: bold; color: #111111;">
                Editar Reserva
            </h1>

            <!-- Información de la pista -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 style="font-family: 'Gopher', sans-serif; font-weight: bold; color: #3B8080;">
                        {{ $reserva->pista->complejo->nombre }} - {{ $reserva->pista->nombre }}
                    </h5>
                    <p class="text-muted mb-0" style="font-family: 'Gopher', sans-serif;">
                        {{ $reserva->pista->tipo }} • {{ $reserva->pista->precio_hora }}€/hora
                    </p>
                </div>
            </div>

            <!-- Formulario de edición -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li style="font-family: 'Gopher', sans-serif;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reservas.actualizar', $reserva->id) }}" id="editForm">
                        @csrf
                        @method('PUT')

                        <!-- Fecha -->
                        <div class="mb-4">
                            <label for="fecha" class="form-label" style="font-family: 'Gopher', sans-serif; font-weight: bold; color: #111111;">
                                Fecha de Reserva
                            </label>
                            <input 
                                type="date" 
                                class="form-control @error('fecha') is-invalid @enderror" 
                                id="fecha" 
                                name="fecha" 
                                value="{{ old('fecha', $reserva->fecha_reserva) }}"
                                min="{{ now()->format('Y-m-d') }}"
                                required
                                style="font-family: 'Gopher', sans-serif;">
                            @error('fecha')
                                <div class="invalid-feedback" style="font-family: 'Gopher', sans-serif;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hora de Inicio -->
                        <div class="mb-4">
                            <label for="hora_inicio" class="form-label" style="font-family: 'Gopher', sans-serif; font-weight: bold; color: #111111;">
                                Hora de Inicio
                            </label>
                            <select 
                                class="form-select @error('hora_inicio') is-invalid @enderror" 
                                id="hora_inicio" 
                                name="hora_inicio" 
                                required
                                style="font-family: 'Gopher', sans-serif;">
                                <option value="">Seleccionar hora de inicio</option>
                                @foreach($horariosDisponibles as $horario)
                                    <option value="{{ $horario }}" {{ old('hora_inicio', substr($reserva->hora_inicio, 0, 5)) == $horario ? 'selected' : '' }}>
                                        {{ $horario }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hora_inicio')
                                <div class="invalid-feedback" style="font-family: 'Gopher', sans-serif;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hora de Fin -->
                        <div class="mb-4">
                            <label for="hora_fin" class="form-label" style="font-family: 'Gopher', sans-serif; font-weight: bold; color: #111111;">
                                Hora de Fin
                            </label>
                            <select 
                                class="form-select @error('hora_fin') is-invalid @enderror" 
                                id="hora_fin" 
                                name="hora_fin" 
                                required
                                style="font-family: 'Gopher', sans-serif;">
                                <option value="">Seleccionar hora de fin</option>
                                @foreach($horariosDisponibles as $horario)
                                    <option value="{{ $horario }}" {{ old('hora_fin', substr($reserva->hora_fin, 0, 5)) == $horario ? 'selected' : '' }}>
                                        {{ $horario }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hora_fin')
                                <div class="invalid-feedback" style="font-family: 'Gopher', sans-serif;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Precio Estimado -->
                        <div class="mb-4">
                            <label class="form-label" style="font-family: 'Gopher', sans-serif; font-weight: bold; color: #111111;">
                                Precio Estimado
                            </label>
                            <div class="p-3 rounded" style="background-color: #F5F5F5;">
                                <span id="precio-display" class="fs-4" style="font-family: 'Gopher', sans-serif; font-weight: bold; color: #C9FF00;">
                                    {{ number_format($reserva->precio_total, 2) }}€
                                </span>
                                <small class="text-muted d-block mt-1" style="font-family: 'Gopher', sans-serif;">
                                    El precio se calcula automáticamente según las horas seleccionadas
                                </small>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-lg flex-grow-1" 
                                    style="background-color: #C9FF00; color: #111111; font-family: 'Gopher', sans-serif; font-weight: bold; border: none;">
                                Guardar Cambios
                            </button>
                            <a href="{{ route('reservas.mis-reservas') }}" class="btn btn-lg btn-outline-dark" 
                               style="font-family: 'Gopher', sans-serif; font-weight: bold;">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const precioHora = {{ $reserva->pista->precio_hora }};
    const horaInicioSelect = document.getElementById('hora_inicio');
    const horaFinSelect = document.getElementById('hora_fin');
    const precioDisplay = document.getElementById('precio-display');

    function calcularPrecio() {
        const horaInicio = horaInicioSelect.value;
        const horaFin = horaFinSelect.value;

        if (horaInicio && horaFin) {
            const [hi_horas, hi_minutos] = horaInicio.split(':').map(Number);
            const [hf_horas, hf_minutos] = horaFin.split(':').map(Number);

            const inicioMinutos = hi_horas * 60 + hi_minutos;
            const finMinutos = hf_horas * 60 + hf_minutos;

            if (finMinutos > inicioMinutos) {
                const horas = (finMinutos - inicioMinutos) / 60;
                const precio = horas * precioHora;
                precioDisplay.textContent = precio.toFixed(2) + '€';
            } else {
                precioDisplay.textContent = '0.00€';
            }
        } else {
            precioDisplay.textContent = '0.00€';
        }
    }

    horaInicioSelect.addEventListener('change', calcularPrecio);
    horaFinSelect.addEventListener('change', calcularPrecio);
</script>
@endsection
