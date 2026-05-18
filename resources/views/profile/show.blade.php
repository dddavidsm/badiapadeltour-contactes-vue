@extends('layouts.base')

@section('title', 'Mi Perfil - Badia Padel Tour')

@section('content')
<style>
    .profile-page {
        background: #0f0f0f;
        min-height: 100vh;
        padding: 60px 0;
    }
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .profile-sidebar {
        background: #1a1a1a;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
    }
    .profile-avatar {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #c9ff00, #95c000);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 48px;
        color: #0f0f0f;
        font-weight: bold;
    }
    .profile-name {
        color: #fdfdfd;
        font-size: 20px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 8px;
        font-family: 'Gopher', sans-serif;
    }
    .profile-email {
        color: #a0a0a0;
        font-size: 14px;
        text-align: center;
        margin-bottom: 24px;
    }
    .nav-tabs-custom {
        border: none;
        gap: 8px;
        display: flex;
        flex-direction: column;
    }
    .nav-tabs-custom .nav-link {
        background: transparent;
        border: none;
        color: #a0a0a0;
        padding: 12px 16px;
        border-radius: 8px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        font-family: 'Gopher', sans-serif;
    }
    .nav-tabs-custom .nav-link:hover {
        background: #252525;
        color: #fdfdfd;
    }
    .nav-tabs-custom .nav-link.active {
        background: #c9ff00;
        color: #0f0f0f;
    }
    .content-card {
        background: #1a1a1a;
        border-radius: 12px;
        padding: 32px;
        margin-bottom: 24px;
    }
    .content-card h5 {
        color: #c9ff00;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 24px;
        font-family: 'Gopher', sans-serif;
    }
    .form-label {
        color: #c9ff00;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 13px;
    }
    .form-control, .form-select {
        background: #252525;
        border: 1px solid #333;
        color: #fdfdfd;
        padding: 12px;
        border-radius: 8px;
        font-size: 14px;
    }
    .form-control:focus, .form-select:focus {
        background: #2a2a2a;
        border-color: #c9ff00;
        color: #fdfdfd;
        box-shadow: 0 0 0 3px rgba(201, 255, 0, 0.1);
    }
    .form-text {
        color: #808080;
        font-size: 12px;
        margin-top: 6px;
    }
    .btn-primary-bpt {
        background: #c9ff00;
        color: #0f0f0f;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.2s;
        font-family: 'Gopher', sans-serif;
    }
    .btn-primary-bpt:hover {
        background: #b8ee00;
        transform: translateY(-2px);
    }
    .alert-info-bpt {
        background: #1e2a1a;
        border: 1px solid #4a5d2f;
        color: #c9ff00;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
    }
    .alert-success-bpt {
        background: #1a2616;
        border: 1px solid #3d5a2a;
        color: #9ddb3c;
        padding: 16px;
        border-radius: 8px;
    }
    .table-dark-bpt {
        background: #252525;
        border-radius: 8px;
        overflow: hidden;
    }
    .table-dark-bpt th {
        background: #1a1a1a;
        color: #c9ff00;
        font-weight: 700;
        padding: 16px;
        font-family: 'Gopher', sans-serif;
    }
    .table-dark-bpt td {
        color: #fdfdfd;
        padding: 16px;
        border-top: 1px solid #333;
    }
    .badge-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-warning { background: #f59e0b; color: #000; }
    .badge-info { background: #3b82f6; color: #fff; }
    .badge-primary { background: #8b5cf6; color: #fff; }
    .badge-success { background: #10b981; color: #fff; }
    .badge-danger { background: #ef4444; color: #fff; }
    .btn-outline-bpt {
        background: transparent;
        border: 1px solid #c9ff00;
        color: #c9ff00;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-outline-bpt:hover {
        background: #c9ff00;
        color: #0f0f0f;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-state i {
        font-size: 64px;
        color: #333;
        margin-bottom: 16px;
    }
    .empty-state p {
        color: #808080;
        font-size: 16px;
        margin-bottom: 24px;
    }
    .reserva-card {
        background: #252525;
        border: 1px solid #333;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        transition: all 0.2s;
    }
    .reserva-card:hover {
        border-color: #c9ff00;
        transform: translateY(-2px);
    }
    .reserva-card h6 {
        color: #fdfdfd;
        font-weight: 700;
        margin-bottom: 8px;
        font-family: 'Gopher', sans-serif;
    }
    .reserva-card p {
        color: #a0a0a0;
        font-size: 14px;
        margin-bottom: 4px;
    }
    @media (max-width: 768px) {
        .profile-page {
            padding: 30px 0;
        }
        .content-card {
            padding: 20px;
        }
    }
</style>

<div class="profile-page">
    <div class="profile-container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 mb-4">
                <div class="profile-sidebar">
                    <div class="profile-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <h5 class="profile-name">{{ auth()->user()->name }}</h5>
                    <p class="profile-email">{{ auth()->user()->email }}</p>
                    
                    <ul class="nav nav-tabs-custom" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#datos-personales">
                                <i class="bi bi-person me-2"></i>Datos Personales
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#medidas">
                                <i class="bi bi-rulers me-2"></i>Mis Medidas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#pedidos">
                                <i class="bi bi-box-seam me-2"></i>Mis Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reservas.mis-reservas') }}">
                                <i class="bi bi-calendar-check me-2"></i>Mis Reservas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#seguridad">
                                <i class="bi bi-shield-lock me-2"></i>Seguridad
                            </a>
                        </li>
                    </ul>

                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="btn-outline-bpt w-100">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="tab-content">
                    <!-- Datos Personales -->
                    <div id="datos-personales" class="tab-pane fade show active">
                        <div class="content-card">
                            <h5><i class="bi bi-person me-2" style="font-size: 20px;"></i>Datos Personales</h5>
                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="bi bi-person-fill me-2" style="font-size: 14px;"></i>Nombre</label>
                                        <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="bi bi-person-badge me-2" style="font-size: 14px;"></i>Apellidos</label>
                                        <input type="text" name="apellidos" class="form-control" value="{{ auth()->user()->apellidos }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="bi bi-card-text me-2" style="font-size: 14px;"></i>DNI</label>
                                        <input type="text" name="dni" class="form-control" value="{{ auth()->user()->dni }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="bi bi-telephone-fill me-2" style="font-size: 14px;"></i>Teléfono</label>
                                        <input type="text" name="telefono" class="form-control" value="{{ auth()->user()->telefono }}" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label"><i class="bi bi-envelope-fill me-2" style="font-size: 14px;"></i>Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn-primary-bpt">
                                    <i class="bi bi-check-circle me-2" style="font-size: 16px;"></i>Guardar Cambios
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mis Medidas -->
                    <div id="medidas" class="tab-pane fade">
                        <div class="content-card">
                            <h5><i class="bi bi-rulers me-2" style="font-size: 20px;"></i>Mis Medidas Deportivas</h5>
                            <div class="alert-info-bpt">
                                <i class="bi bi-info-circle me-2" style="font-size: 16px;"></i>
                                Completa tus medidas para recibir recomendaciones personalizadas de productos y tallas.
                            </div>

                    <form action="{{ route('profile.update-medidas') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row">
                            <!-- Tallas -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-shoe-fill me-1"></i>Talla de Pie (EU)</label>
                                <select name="talla_pie" class="form-select">
                                    <option value="">Selecciona...</option>
                                    @for($i = 35; $i <= 48; $i++)
                                        <option value="{{ $i }}" {{ auth()->user()->talla_pie == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-person-square me-1"></i>Talla de Camiseta</label>
                                <select name="talla_camiseta" class="form-select">
                                    <option value="">Selecciona...</option>
                                    <option value="XS" {{ auth()->user()->talla_camiseta == 'XS' ? 'selected' : '' }}>XS</option>
                                    <option value="S" {{ auth()->user()->talla_camiseta == 'S' ? 'selected' : '' }}>S</option>
                                    <option value="M" {{ auth()->user()->talla_camiseta == 'M' ? 'selected' : '' }}>M</option>
                                    <option value="L" {{ auth()->user()->talla_camiseta == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="XL" {{ auth()->user()->talla_camiseta == 'XL' ? 'selected' : '' }}>XL</option>
                                    <option value="XXL" {{ auth()->user()->talla_camiseta == 'XXL' ? 'selected' : '' }}>XXL</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-file-person me-1"></i>Talla de Pantalón</label>
                                <select name="talla_pantalon" class="form-select">
                                    <option value="">Selecciona...</option>
                                    <option value="XS" {{ auth()->user()->talla_pantalon == 'XS' ? 'selected' : '' }}>XS</option>
                                    <option value="S" {{ auth()->user()->talla_pantalon == 'S' ? 'selected' : '' }}>S</option>
                                    <option value="M" {{ auth()->user()->talla_pantalon == 'M' ? 'selected' : '' }}>M</option>
                                    <option value="L" {{ auth()->user()->talla_pantalon == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="XL" {{ auth()->user()->talla_pantalon == 'XL' ? 'selected' : '' }}>XL</option>
                                    <option value="XXL" {{ auth()->user()->talla_pantalon == 'XXL' ? 'selected' : '' }}>XXL</option>
                                </select>
                            </div>

                            <!-- Físicas -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-arrows-vertical me-1"></i>Altura (cm)</label>
                                <input type="number" name="altura" class="form-control" value="{{ auth()->user()->altura }}" step="0.01" min="120" max="250" placeholder="Ej: 175">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-activity me-1"></i>Peso (kg)</label>
                                <input type="number" name="peso" class="form-control" value="{{ auth()->user()->peso }}" step="0.01" min="30" max="200" placeholder="Ej: 70">
                            </div>

                            <!-- Nivel de Juego -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-trophy me-1"></i>Nivel de Juego</label>
                                <select name="nivel_juego" class="form-select">
                                    <option value="">Selecciona...</option>
                                    <option value="principiante" {{ auth()->user()->nivel_juego == 'principiante' ? 'selected' : '' }}>Principiante</option>
                                    <option value="intermedio" {{ auth()->user()->nivel_juego == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                                    <option value="avanzado" {{ auth()->user()->nivel_juego == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                                    <option value="profesional" {{ auth()->user()->nivel_juego == 'profesional' ? 'selected' : '' }}>Profesional</option>
                                </select>
                                <div class="form-text">
                                    <strong>Principiante:</strong> < 1 año jugando | 
                                    <strong>Intermedio:</strong> 1-3 años | 
                                    <strong>Avanzado:</strong> 3+ años | 
                                    <strong>Profesional:</strong> Competición
                                </div>
                            </div>

                            <!-- Mano Dominante -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-hand-index me-1"></i>Mano Dominante</label>
                                <select name="mano_dominante" class="form-select">
                                    <option value="">Selecciona...</option>
                                    <option value="diestra" {{ auth()->user()->mano_dominante == 'diestra' ? 'selected' : '' }}>Diestra</option>
                                    <option value="zurda" {{ auth()->user()->mano_dominante == 'zurda' ? 'selected' : '' }}>Zurda</option>
                                </select>
                            </div>
                        </div>

                            <!-- Recomendaciones basadas en perfil -->
                            @if(auth()->user()->nivel_juego)
                            <div class="alert-success-bpt mt-3">
                                <h6><i class="bi bi-lightbulb me-2"></i>Recomendaciones para tu nivel:</h6>
                                @if(auth()->user()->nivel_juego == 'principiante')
                                    <p class="mb-0 small">Te recomendamos palas de <strong>forma redonda</strong> con <strong>balance bajo</strong> para máximo control y un punto dulce amplio.</p>
                                @elseif(auth()->user()->nivel_juego == 'intermedio')
                                    <p class="mb-0 small">Palas de <strong>forma lágrima</strong> con <strong>balance medio</strong> te darán versatilidad entre potencia y control.</p>
                                @elseif(auth()->user()->nivel_juego == 'avanzado')
                                    <p class="mb-0 small">Palas de <strong>forma diamante</strong> con materiales de <strong>carbono</strong> para máxima potencia.</p>
                                @else
                                    <p class="mb-0 small">Busca palas con tecnologías premium como <strong>grafeno o kevlar</strong> y personaliza tu equipamiento.</p>
                                @endif
                            </div>
                            @endif

                            <button type="submit" class="btn-primary-bpt">
                                <i class="bi bi-check-circle me-2" style="font-size: 16px;"></i>Guardar Medidas
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mis Pedidos -->
                <div id="pedidos" class="tab-pane fade">
                    <div class="content-card">
                        <h5><i class="bi bi-box-seam me-2" style="font-size: 20px;"></i>Mis Pedidos</h5>
                        @if($pedidos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-dark-bpt">
                                    <thead>
                                        <tr>
                                            <th>Pedido #</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th>Total</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pedidos as $pedido)
                                        <tr>
                                            <td><strong>#{{ $pedido->id }}</strong></td>
                                            <td>{{ $pedido->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @if($pedido->estado == 'pendiente')
                                                    <span class="badge-status badge-warning">Pendiente</span>
                                                @elseif($pedido->estado == 'procesando')
                                                    <span class="badge-status badge-info">Procesando</span>
                                                @elseif($pedido->estado == 'enviado')
                                                    <span class="badge-status badge-primary">Enviado</span>
                                                @elseif($pedido->estado == 'entregado')
                                                    <span class="badge-status badge-success">Entregado</span>
                                                @else
                                                    <span class="badge-status badge-danger">Cancelado</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ number_format($pedido->total, 2) }}€</strong></td>
                                            <td>
                                                <button class="btn-outline-bpt" data-bs-toggle="modal" data-bs-target="#pedidoModal{{ $pedido->id }}">
                                                    <i class="bi bi-eye"></i> Ver
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="bi bi-inbox" style="font-size: 64px;"></i>
                                <p>No tienes pedidos todavía</p>
                                <a href="{{ route('tienda.productos') }}" class="btn-primary-bpt">
                                    <i class="bi bi-shop me-2" style="font-size: 16px;"></i>Ir a la Tienda
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Mis Reservas -->
                <div id="reservas" class="tab-pane fade">
                    <div class="content-card">
                        <h5><i class="bi bi-calendar-check me-2" style="font-size: 20px;"></i>Mis Reservas</h5>
                        @if($reservas->count() > 0)
                            <div class="row">
                                @foreach($reservas as $reserva)
                                <div class="col-md-6 mb-3">
                                    <div class="reserva-card">
                                        <h6>{{ $reserva->pista->nombre }}</h6>
                                        <p class="text-muted small mb-2">{{ $reserva->pista->complejo->nombre }}</p>
                                        <p class="mb-1"><i class="bi bi-calendar3 me-2"></i>{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</p>
                                        <p class="mb-1"><i class="bi bi-clock me-2"></i>{{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}</p>
                                        <span class="badge-status {{ $reserva->estado == 'confirmada' ? 'badge-success' : 'badge-warning' }}">
                                            {{ ucfirst($reserva->estado) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="bi bi-calendar-x" style="font-size: 64px;"></i>
                                <p>No tienes reservas</p>
                                <a href="{{ route('complejos.index') }}" class="btn-primary-bpt">
                                    <i class="bi bi-calendar-plus me-2" style="font-size: 16px;"></i>Hacer Reserva
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Seguridad -->
                <div id="seguridad" class="tab-pane fade">
                    <div class="content-card">
                        <h5><i class="bi bi-shield-lock me-2" style="font-size: 20px;"></i>Seguridad</h5>
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Contraseña Actual</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nueva Contraseña</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn-primary-bpt">
                                <i class="bi bi-key me-2" style="font-size: 16px;"></i>Cambiar Contraseña
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
