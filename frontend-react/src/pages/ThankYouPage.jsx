import { Link, useLocation } from 'react-router-dom';

export default function ThankYouPage() {
  const { state } = useLocation();

  return (
    <section className="thankyou-section">
      <div className="site-container">
        <div className="confirmacion-content">
          <div className="success-icon">✓</div>
          <h1 className="confirmacion-title">Reserva Confirmada</h1>
          <p className="confirmacion-subtitle">Tu reserva se ha procesado correctamente</p>

          {!state && (
            <div className="reserva-card">
              <p>No hay datos de pedido en esta sesion.</p>
              <div className="actions">
                <Link className="btn-primary" to="/">Volver al inicio</Link>
              </div>
            </div>
          )}

          {state && (
            <div className="reserva-card">
              <div className="card-section">
                <div className="detail-row">
                  <span className="detail-label">Referencia</span>
                  <span className="detail-value highlight">{state.order_reference || '-'}</span>
                </div>
                <div className="detail-row">
                  <span className="detail-label">Cliente</span>
                  <span className="detail-value">{state.guest?.name} ({state.guest?.email})</span>
                </div>
                <div className="detail-row">
                  <span className="detail-label">Total pagado</span>
                  <span className="detail-value price">{Number(state.total || 0).toFixed(2)} EUR</span>
                </div>
              </div>

              <div className="card-section">
                {state.reservas?.map((reserva) => (
                  <div className="detail-row" key={reserva.id}>
                    <span className="detail-label">{reserva.pista?.nombre} · {reserva.pista?.complejo?.nombre}</span>
                    <span className="detail-value">{Number(reserva.precio_total).toFixed(2)} EUR</span>
                  </div>
                ))}
              </div>

              <div className="actions">
                <Link className="btn-primary" to="/pistas">Hacer otra reserva</Link>
                <Link className="btn-secondary" to="/">Volver a inicio</Link>
              </div>
            </div>
          )}
        </div>
      </div>
    </section>
  );
}
