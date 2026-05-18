<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #111111;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}" style="font-family: 'Gopher', sans-serif; font-weight: bold; color: #C9FF00;">
            BPT
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('complejos.index') }}">Complejos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pistas.index') }}">Pistas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contacto') }}">Contacto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tienda.index') }}">Tienda</a>
                </li>
            </ul>

            <div class="d-flex">
                @if (Route::has('login'))
                        @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-dark me-2">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-link text-decoration-none text-secondary me-2">Iniciar Sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-dark">Registrarse</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>
