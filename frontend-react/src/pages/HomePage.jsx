import { useEffect, useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import { getComplejos, getPistas } from '../api/client';
import { toAssetUrl } from '../utils/assets';

export default function HomePage() {
  const [complejos, setComplejos] = useState([]);
  const [pistasCount, setPistasCount] = useState(0);

  useEffect(() => {
    getComplejos({ per_page: 100 }).then((response) => {
      setComplejos(response.data || []);
    }).catch(() => {
      setComplejos([]);
    });

    getPistas({ per_page: 100 }).then((response) => {
      setPistasCount((response.data || []).length);
    }).catch(() => {
      setPistasCount(0);
    });
  }, []);

  const stats = useMemo(() => ([
    { label: 'Pistas Disponibles', value: pistasCount || 0 },
    { label: 'Usuarios Registrados', value: 250 },
    { label: 'Complejos Deportivos', value: complejos.length || 0 },
    { label: 'Reservas Realizadas', value: 1200 },
  ]), [complejos.length, pistasCount]);

  return (
    <>
      <section className="hero" aria-label="Hero video">
        <video src={toAssetUrl('/assets/Video.mp4')} autoPlay muted loop playsInline />
        <div className="hero-overlay">
          <div className="hero-content">
            <p className="hero-kicker">PADEL CLUB</p>
            <h1 className="hero-title">Listo Para Jugar?</h1>
            <Link to="/pistas" className="hero-cta">VER PISTAS</Link>
          </div>
        </div>
      </section>

      <section className="stats-section">
        <div className="site-container stats-grid">
          {stats.map((stat) => (
            <div key={stat.label} className="stat-card">
              <p className="stat-number">{stat.value}<span className="plus">+</span></p>
              <p className="stat-label">{stat.label}</p>
            </div>
          ))}
        </div>
      </section>

      <section className="complexes-section">
        <div className="site-container">
          <h4>Nuestros Complejos</h4>
          <h2>Elige Donde Quieres Jugar</h2>
          <div className="complex-grid">
            {complejos.slice(0, 3).map((complejo) => (
              <article key={complejo.id} className="complex-card">
                <div
                  className="complex-thumb"
                  style={{
                    backgroundImage: `url(${toAssetUrl(complejo.imagen || '/assets/Pistas/barberadelvalles_pista2.jfif')})`,
                  }}
                >
                  <h3 className="complex-overlay-title">{String(complejo.nombre || '').toUpperCase()}</h3>
                </div>

                <div className="complex-body">
                  <p className="complex-meta">
                    <img src={toAssetUrl('/assets/location_icon.svg')} alt="location" />
                    {complejo.direccion || 'Sin direccion'}
                  </p>
                  <p className="complex-text">
                    {complejo.descripcion || 'Pistas de padel de primer nivel con tecnologia actual.'}
                  </p>
                  <Link to={`/pistas?complejo_id=${complejo.id}`} className="complex-btn">
                    VER PISTAS DEL COMPLEJO
                  </Link>
                </div>
              </article>
            ))}
          </div>

          <Link to="/pistas" className="complex-cta">VER TODAS LAS PISTAS</Link>
        </div>
      </section>

      <section className="map-section" aria-label="Mapa de ubicaciones">
        <div className="map-frame">
          <img src={toAssetUrl('/assets/MAPA.png')} alt="Mapa Badia Padel Tour" />
          <div className="map-text">
            <h4>Ubicaciones</h4>
            <h2>Seguimos Creciendo Dia A Dia</h2>
          </div>
        </div>
      </section>
    </>
  );
}
