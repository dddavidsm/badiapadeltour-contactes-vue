import { Link } from 'react-router-dom';
import { useCart } from '../context/CartContext';
import { toAssetUrl } from '../utils/assets';

export default function CartPage() {
  const { items, removeItem, total } = useCart();

  return (
    <section className="cart-section">
      <div className="site-container cart-container">
        <div className="cart-header">
          <h1 className="cart-title">Tu Carrito</h1>
          <p className="cart-subtitle">Revisa tus reservas antes de finalizar la compra</p>
        </div>

        {items.length === 0 && (
          <div className="empty-cart">
            <div className="empty-icon">🛒</div>
            <h2 className="empty-title">Carrito vacio</h2>
            <p className="empty-text">No tienes reservas anadidas todavia.</p>
            <Link className="btn-checkout" to="/pistas">Ver pistas</Link>
          </div>
        )}

        {items.length > 0 && (
          <div className="cart-layout">
            <div className="cart-items">
              {items.map((item) => (
                <article key={item.cartId} className="cart-item">
                  <img className="item-image" src={toAssetUrl('/assets/Pistas/barberadelvalles_pista2.jfif')} alt={item.pista_nombre} />
                  <div className="item-details">
                    <h3 className="item-name">{item.pista_nombre}</h3>
                    <p className="item-category">{item.complejo_nombre}</p>
                    <p className="item-meta">{item.fecha_reserva} · {item.hora_inicio} · {item.duracion_minutos} min</p>
                    <p className="item-price">{Number(item.price).toFixed(2)} EUR</p>
                  </div>
                  <div className="item-actions">
                    <button onClick={() => removeItem(item.cartId)} className="btn-remove">Eliminar</button>
                  </div>
                </article>
              ))}
            </div>

            <aside className="cart-summary">
              <div className="summary-card">
                <h3 className="summary-title">Resumen</h3>
                <div className="summary-row">
                  <span className="summary-label">Reservas</span>
                  <span className="summary-value">{items.length}</span>
                </div>
                <div className="summary-row">
                  <span className="summary-label">Total</span>
                  <span className="summary-total">{total.toFixed(2)} EUR</span>
                </div>

                <Link className="btn-checkout" to="/checkout">Finalizar compra</Link>
                <Link className="btn-continue" to="/pistas">Seguir comprando</Link>
              </div>
            </aside>
          </div>
        )}
      </div>
    </section>
  );
}
