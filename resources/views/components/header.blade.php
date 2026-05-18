<header class="site-header">
    <div class="container">
        <div class="header-grid">
            <a href="{{ route('home') }}" class="brand" aria-label="Badia Padel Tour">
                <img src="/assets/logo_electriclime.svg" alt="Badia Padel Tour">
            </a>

            <nav class="main-nav" aria-label="Navegación principal">
                <a href="{{ route('complejos.index') }}" class="{{ Route::currentRouteName() === 'complejos.index' ? 'nav-active' : '' }}">Complejos</a>
                <a href="{{ route('pistas.index') }}" class="{{ Route::currentRouteName() === 'pistas.index' ? 'nav-active' : '' }}">Pistas</a>
                <a href="{{ route('torneos.index') }}" class="{{ str_starts_with(Route::currentRouteName(), 'torneos.') ? 'nav-active' : '' }}">Torneos</a>
                <a href="{{ route('contactos.padel') }}" class="{{ Route::currentRouteName() === 'contactos.padel' ? 'nav-active' : '' }}">Parejas</a>
                <a href="{{ route('tienda.index') }}" class="{{ Route::currentRouteName() === 'tienda.index' ? 'nav-active' : '' }}">Tienda</a>
                <a href="{{ route('contacto') }}" class="{{ Route::currentRouteName() === 'contacto' ? 'nav-active' : '' }}">Contacto</a>
            </nav>

            <div class="auth">
                @if (Route::has('login'))
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a class="auth-admin" href="/admin">Panel Admin</a>
                        @endif
                        <a class="auth-login" href="{{ url('/dashboard') }}">Perfil</a>
                    @else
                        <a class="auth-login" href="{{ route('login') }}">Iniciar Sesión</a>
                        @if (Route::has('register'))
                            <a class="auth-register" href="{{ route('register') }}">Registrarse</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</header>

<style>
    .site-header { background: #0f0f0f; padding: 18px 0 16px; position: sticky; top: 0; z-index: 20; }
    .container { width: min(1200px, calc(100% - 96px)); margin: 0 auto; }
    .header-grid { display: grid; grid-template-columns: auto 1fr auto; align-items: center; column-gap: 32px; }
    
    .brand { display: inline-flex; align-items: center; gap: 10px; text-decoration: none; }
    .brand img { height: 28px; width: auto; display: block; }
    .brand-name { color: #c9ff00; font-weight: 800; font-size: 22px; letter-spacing: -0.02em; font-family: 'Gopher', 'Inter', sans-serif; }
    .brand-subtitle { color: #c9ff00; font-weight: 500; font-size: 14px; letter-spacing: 0.01em; margin-top: 2px; font-family: 'Gopher', 'Inter', sans-serif; }
    
    .main-nav { display: flex; justify-content: center; gap: 32px; }
    .main-nav a { color: #fdfdfd; text-decoration: none; font-weight: 700; font-size: 15px; letter-spacing: 0.01em; font-family: 'Gopher', 'Inter', sans-serif; transition: color 0.2s; }
    .main-nav a:hover { color: #c9ff00; }
    .main-nav a.nav-active { color: #c9ff00; }
    
    .auth { display: flex; align-items: center; gap: 12px; justify-content: flex-end; }
    .auth a { text-decoration: none; font-weight: 700; font-size: 13px; font-family: 'Gopher', 'Inter', sans-serif; }
    .auth-login { color: #c1f03f; transition: opacity 0.2s; }
    .auth-login:hover { opacity: 0.8; }
    .auth-admin { background: #4a5d2f; color: #c9ff00; padding: 10px 16px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; min-width: 104px; transition: background-color 0.2s; }
    .auth-admin:hover { background: #5a6d3f; }
    .auth-register { background: #c9ff00; color: #0f0f0f; padding: 10px 16px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; min-width: 104px; transition: background-color 0.2s; }
    .auth-register:hover { background: #b8ee00; }

    @media (max-width: 960px) {
        .header-grid { grid-template-columns: auto auto; row-gap: 16px; }
        .main-nav { justify-content: flex-start; gap: 24px; }
    }

    @media (max-width: 640px) {
        .container { width: calc(100% - 48px); }
        .header-grid { grid-template-columns: 1fr; justify-items: center; text-align: center; }
        .main-nav { justify-content: center; gap: 20px; flex-wrap: wrap; }
        .auth { justify-content: center; }
    }
</style>
