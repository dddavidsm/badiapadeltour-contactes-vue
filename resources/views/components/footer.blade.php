<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-left">
                <a href="{{ route('home') }}" class="footer-brand" aria-label="Badia Padel Tour">
                    <img src="/assets/logo_electriclime.svg" alt="Badia Padel Tour">
                </a>
                <div class="footer-nav" aria-label="Navegación footer">
                    <a href="{{ route('complejos.index') }}">Complejos</a>
                    <a href="{{ route('pistas.index') }}">Pistas</a>
                    <a href="{{ route('torneos.index') }}">Torneos</a>
                    <a href="{{ route('tienda.index') }}">Tienda</a>
                </div>
            </div>

            <div class="footer-icons" aria-label="Redes sociales">
                <a href="#" aria-label="Email"><img src="/assets/mail_icon.svg" alt="Email"></a>
                <a href="#" aria-label="Instagram"><img src="/assets/instagram_icon.svg" alt="Instagram"></a>
                <a href="#" aria-label="Facebook"><img src="/assets/facebook_icon.svg" alt="Facebook"></a>
            </div>
        </div>

        <div class="footer-line"></div>
        <div class="copyright">© 2025 Badia Padel Tour — Gestión Profesional De Complejos Deportivos. Todos Los Derechos Reservados.</div>
    </div>
</footer>

<style>
    .site-footer { background: #3b8080; color: #fff; padding: 42px 0 38px; }
    .container { width: min(1200px, calc(100% - 96px)); margin: 0 auto; }
    .footer-grid { display: grid; grid-template-columns: 1fr auto; align-items: start; column-gap: 40px; row-gap: 18px; }
    
    .footer-left { display: flex; flex-direction: column; gap: 16px; }
    
    .footer-brand { display: inline-flex; align-items: center; gap: 12px; text-decoration: none; }
    .footer-brand img { height: 32px; width: auto; display: block; }
    
    .footer-nav { display: flex; gap: 36px; font-weight: 700; font-size: 16px; font-family: 'Gopher', 'Inter', sans-serif; }
    .footer-nav a { color: #fff; text-decoration: none; transition: opacity 0.2s; }
    .footer-nav a:hover { opacity: 0.8; }
    
    .footer-icons { display: flex; gap: 100px; align-items: center; }
    .footer-icons a { display: flex; align-items: center; justify-content: center; transition: opacity 0.2s; }
    .footer-icons a:hover { opacity: 0.8; }
    .footer-icons img { height: 22px; width: auto; display: block; filter: brightness(0) saturate(100%) invert(88%) sepia(89%) saturate(1352%) hue-rotate(13deg) brightness(104%) contrast(104%); }
    
    .footer-line { height: 4px; background: #c9ff00; margin: 30px 0 20px; }
    .copyright { text-align: left; color: #fff; font-weight: 500; letter-spacing: 0.01em; font-size: 14px; line-height: 1.5; font-family: 'Gopher', 'Inter', sans-serif; }

    @media (max-width: 960px) {
        .footer-grid { grid-template-columns: 1fr; text-align: center; justify-items: center; }
        .footer-left { align-items: center; }
        .footer-nav { justify-content: center; }
        .footer-icons { justify-content: center; }
    }

    @media (max-width: 640px) {
        .container { width: calc(100% - 48px); }
        .site-footer { padding: 32px 0 30px; }
        .footer-nav { gap: 24px; flex-wrap: wrap; }
    }
</style>
