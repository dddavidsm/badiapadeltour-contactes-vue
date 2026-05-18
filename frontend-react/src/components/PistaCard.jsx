import { toAssetUrl } from '../utils/assets';

export default function PistaCard({ pista, onAdd }) {
  const imageSrc = toAssetUrl(pista.imagen || '/assets/Pistas/barberadelvalles_pista2.jfif');

  return (
    <article className="pista-card">
      <div className="pista-thumb" style={{ backgroundImage: `url(${imageSrc})` }} />
      <div className="pista-body">
        <h3 className="pista-name">{pista.nombre}</h3>
        <p className="pista-location">{pista.complejo?.nombre || 'Complejo no disponible'}</p>
        <p className="pista-info">Tipo: <strong>{pista.tipo === 'indoor' ? 'Indoor' : 'Outdoor'}</strong></p>
        <p className="pista-price">Precio/Hora: <strong>{Number(pista.precio_hora || 0).toFixed(2)} EUR</strong></p>
        <p className="pista-info muted">
          Disponibilidad: {pista.disponible ? <strong className="ok">Disponible</strong> : <strong className="ko">No disponible</strong>}
        </p>

        <button onClick={() => onAdd(pista)} className="pista-btn" type="button">
          Anadir al carrito
        </button>
      </div>
    </article>
  );
}
