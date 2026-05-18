import { useState } from 'react';
import { Navigate, useNavigate } from 'react-router-dom';
import { checkout } from '../api/client';
import { useCart } from '../context/CartContext';

export default function CheckoutPage() {
  const navigate = useNavigate();
  const { items, total, clearCart } = useCart();

  const [form, setForm] = useState({
    name: '',
    email: '',
    phone: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  if (!items.length) {
    return <Navigate to="/cart" replace />;
  }

  const onChange = (event) => {
    const { name, value } = event.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const onSubmit = async (event) => {
    event.preventDefault();
    setError('');
    setLoading(true);

    try {
      const payload = {
        guest: {
          name: form.name,
          email: form.email,
          phone: form.phone,
        },
        items: items.map((item) => ({
          pista_id: item.pista_id,
          fecha_reserva: item.fecha_reserva,
          hora_inicio: item.hora_inicio,
          duracion_minutos: item.duracion_minutos,
        })),
      };

      const order = await checkout(payload);
      clearCart();
      navigate('/thank-you', { state: order });
    } catch (apiError) {
      setError(apiError.payload?.message || 'No se ha podido finalizar la compra.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <section className="checkout-section">
      <div className="site-container checkout-container">
        <div className="checkout-header">
          <h1 className="checkout-title">Finalizar Compra</h1>
          <div className="checkout-steps">
            <div className="step">
              <span className="step-number inactive">1</span>
              <span className="step-label inactive">Carrito</span>
            </div>
            <div className="step">
              <span className="step-number">2</span>
              <span className="step-label">Datos</span>
            </div>
            <div className="step">
              <span className="step-number inactive">3</span>
              <span className="step-label inactive">Confirmacion</span>
            </div>
          </div>
        </div>

        <div className="checkout-layout">
          <form onSubmit={onSubmit} className="checkout-form">
            <div className="form-section">
              <h2 className="section-title">Datos personales</h2>
              <div className="form-grid single">
                <label className="form-group">
                  <span className="form-label">Nombre completo</span>
                  <input className="form-input" required name="name" value={form.name} onChange={onChange} />
                </label>
                <label className="form-group">
                  <span className="form-label">Email</span>
                  <input className="form-input" required type="email" name="email" value={form.email} onChange={onChange} />
                </label>
                <label className="form-group">
                  <span className="form-label">Telefono</span>
                  <input className="form-input" name="phone" value={form.phone} onChange={onChange} />
                </label>
              </div>
            </div>

            {error && <p className="error">{error}</p>}

            <button disabled={loading} className="btn-place-order" type="submit">
              {loading ? 'Procesando...' : 'Confirmar reserva'}
            </button>
          </form>

          <aside className="order-summary">
            <div className="summary-card">
              <h3 className="summary-title">Resumen pedido</h3>
              <div className="summary-items">
                {items.map((item) => (
                  <div className="summary-item" key={item.cartId}>
                    <div className="item-info">
                      <p className="item-name-small">{item.pista_nombre}</p>
                      <p className="item-qty">{item.fecha_reserva} · {item.hora_inicio}</p>
                    </div>
                    <span className="item-price-small">{Number(item.price).toFixed(2)} EUR</span>
                  </div>
                ))}
              </div>
              <div className="summary-total">
                <span className="total-label">Total</span>
                <span className="total-amount">{total.toFixed(2)} EUR</span>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </section>
  );
}
