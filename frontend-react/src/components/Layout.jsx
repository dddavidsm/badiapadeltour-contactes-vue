import { Link, NavLink } from 'react-router-dom';
import { useCart } from '../context/CartContext';
import { toAssetUrl } from '../utils/assets';

export default function Layout({ children }) {
  const { items } = useCart();

  return (
    <div className="site-shell">
      <header className="site-header">
        <div className="site-container header-grid">
          <Link to="/" className="brand" aria-label="Badia Padel Tour">
            <img src={toAssetUrl('/assets/logo_electriclime.svg')} alt="Badia Padel Tour" />
          </Link>

          <nav className="main-nav" aria-label="Navegacion principal">
            <NavLink to="/" end className={({ isActive }) => (isActive ? 'nav-active' : '')}>Home</NavLink>
            <NavLink to="/pistas" className={({ isActive }) => (isActive ? 'nav-active' : '')}>Pistas</NavLink>
            <NavLink to="/cart" className={({ isActive }) => (isActive ? 'nav-active' : '')}>
              Carrito ({items.length})
            </NavLink>
          </nav>

          <div className="auth-actions">
            <Link to="/checkout" className="auth-register">Reserva Invitado</Link>
          </div>
        </div>
      </header>

      <main className="site-main">{children}</main>

      <footer className="site-footer">
        <div className="site-container">
          <div className="footer-grid">
            <div className="footer-left">
              <Link to="/" className="footer-brand" aria-label="Badia Padel Tour">
                <img src={toAssetUrl('/assets/logo_electriclime.svg')} alt="Badia Padel Tour" />
              </Link>
              <div className="footer-nav" aria-label="Navegacion footer">
                <Link to="/">Home</Link>
                <Link to="/pistas">Pistas</Link>
                <Link to="/cart">Carrito</Link>
                <Link to="/checkout">Checkout</Link>
              </div>
            </div>
            <div className="footer-icons" aria-label="Redes sociales">
              <a href="#" aria-label="Email"><img src={toAssetUrl('/assets/mail_icon.svg')} alt="Email" /></a>
              <a href="#" aria-label="Instagram"><img src={toAssetUrl('/assets/instagram_icon.svg')} alt="Instagram" /></a>
              <a href="#" aria-label="Facebook"><img src={toAssetUrl('/assets/facebook_icon.svg')} alt="Facebook" /></a>
            </div>
          </div>

          <div className="footer-line" />
          <p className="copyright">© 2025 Badia Padel Tour - Gestion Profesional De Complejos Deportivos.</p>
        </div>
      </footer>
    </div>
  );
}
