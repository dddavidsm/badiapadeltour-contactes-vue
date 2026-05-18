import { useEffect, useMemo, useState } from 'react';
import PistaCard from '../components/PistaCard';
import { getComplejos, getPistas } from '../api/client';
import { useCart } from '../context/CartContext';

const DURATION_OPTIONS = [60, 90, 120];

export default function PistasPage() {
  const { addItem } = useCart();
  const [pistas, setPistas] = useState([]);
  const [complejos, setComplejos] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const [filters, setFilters] = useState({
    q: '',
    tipo: '',
    complejo_id: '',
    precio_min: '',
    precio_max: '',
    sort: 'id_desc',
  });

  const [bookingData, setBookingData] = useState({
    fecha_reserva: new Date().toISOString().slice(0, 10),
    hora_inicio: '18:00',
    duracion_minutos: 90,
  });

  useEffect(() => {
    getComplejos().then((res) => {
      setComplejos(res.data || []);
    }).catch(() => {});
  }, []);

  useEffect(() => {
    setLoading(true);
    setError('');

    getPistas({ ...filters, per_page: 100 })
      .then((res) => {
        setPistas(res.data || []);
      })
      .catch(() => {
        setError('No se han podido cargar las pistas.');
      })
      .finally(() => setLoading(false));
  }, [filters]);

  const onFilterChange = (event) => {
    const { name, value } = event.target;
    setFilters((prev) => ({ ...prev, [name]: value }));
  };

  const onBookingChange = (event) => {
    const { name, value } = event.target;
    setBookingData((prev) => ({
      ...prev,
      [name]: name === 'duracion_minutos' ? Number(value) : value,
    }));
  };

  const addPistaToCart = (pista) => {
    const durationHours = Number(bookingData.duracion_minutos) / 60;
    const price = Number(pista.precio_hora) * durationHours;

    addItem({
      pista_id: pista.id,
      pista_nombre: pista.nombre,
      complejo_nombre: pista.complejo?.nombre || '',
      fecha_reserva: bookingData.fecha_reserva,
      hora_inicio: bookingData.hora_inicio,
      duracion_minutos: bookingData.duracion_minutos,
      price: Number(price.toFixed(2)),
    });
  };

  const totalPreview = useMemo(
    () => pistas.reduce((sum, pista) => sum + Number(pista.precio_hora || 0), 0),
    [pistas]
  );

  return (
    <section className="pistas-section">
      <div className="site-container">
        <div className="pistas-header">
          <h1 className="pistas-title">Pistas Disponibles</h1>
          {!!filters.complejo_id && (
            <div className="location-selector">
              <span>Complejo:</span>
              <strong>{complejos.find((item) => String(item.id) === String(filters.complejo_id))?.nombre || '-'}</strong>
            </div>
          )}
        </div>

        <div className="filters-container">
          <div className="filters-grid">
            <div className="filter-group">
              <label className="filter-label" htmlFor="q">Busqueda</label>
              <input id="q" className="filter-select" name="q" value={filters.q} onChange={onFilterChange} placeholder="Buscar por nombre" />
            </div>
            <div className="filter-group">
              <label className="filter-label" htmlFor="complejo_id">Complejo</label>
              <select id="complejo_id" className="filter-select" name="complejo_id" value={filters.complejo_id} onChange={onFilterChange}>
                <option value="">Todos los complejos</option>
                {complejos.map((complejo) => (
                  <option key={complejo.id} value={complejo.id}>{complejo.nombre}</option>
                ))}
              </select>
            </div>
            <div className="filter-group">
              <label className="filter-label" htmlFor="tipo">Tipo</label>
              <select id="tipo" className="filter-select" name="tipo" value={filters.tipo} onChange={onFilterChange}>
                <option value="">Todos los tipos</option>
                <option value="indoor">Indoor</option>
                <option value="outdoor">Outdoor</option>
              </select>
            </div>
            <div className="filter-group">
              <label className="filter-label" htmlFor="precio_min">Precio Minimo</label>
              <input id="precio_min" className="filter-select" name="precio_min" type="number" value={filters.precio_min} onChange={onFilterChange} />
            </div>
            <div className="filter-group">
              <label className="filter-label" htmlFor="precio_max">Precio Maximo</label>
              <input id="precio_max" className="filter-select" name="precio_max" type="number" value={filters.precio_max} onChange={onFilterChange} />
            </div>
            <div className="filter-group">
              <label className="filter-label" htmlFor="sort">Orden</label>
              <select id="sort" className="filter-select" name="sort" value={filters.sort} onChange={onFilterChange}>
                <option value="id_desc">Mas nuevas</option>
                <option value="precio_asc">Precio ascendente</option>
                <option value="precio_desc">Precio descendente</option>
                <option value="nombre_asc">Nombre A-Z</option>
              </select>
            </div>
          </div>
        </div>

        <div className="filters-container booking-block">
          <h3 className="booking-title">Datos de reserva</h3>
          <div className="booking-inline">
            <label>
              Fecha
              <input className="filter-select" type="date" name="fecha_reserva" value={bookingData.fecha_reserva} onChange={onBookingChange} />
            </label>
            <label>
              Hora inicio
              <input className="filter-select" type="time" name="hora_inicio" value={bookingData.hora_inicio} onChange={onBookingChange} />
            </label>
            <label>
              Duracion
              <select className="filter-select" name="duracion_minutos" value={bookingData.duracion_minutos} onChange={onBookingChange}>
                {DURATION_OPTIONS.map((minutes) => (
                  <option key={minutes} value={minutes}>{minutes} min</option>
                ))}
              </select>
            </label>
          </div>
        </div>

        {loading && <p className="status-message">Cargando pistas...</p>}
        {error && <p className="error">{error}</p>}

        <div className="pistas-grid">
          {pistas.map((pista) => (
            <PistaCard key={pista.id} pista={pista} onAdd={addPistaToCart} />
          ))}
        </div>

        {!loading && !error && pistas.length === 0 && <p className="status-message">No hay pistas con estos filtros.</p>}

        <p className="muted total-preview">Precio base acumulado: {totalPreview.toFixed(2)} EUR/h</p>
      </div>
    </section>
  );
}
