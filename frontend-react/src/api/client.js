const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || '/api';

async function request(path, options = {}) {
  const response = await fetch(`${API_BASE_URL}${path}`, {
    headers: {
      'Content-Type': 'application/json',
      ...(options.headers || {}),
    },
    ...options,
  });

  const data = await response.json().catch(() => ({}));

  if (!response.ok) {
    const error = new Error(data.message || 'Error en la API');
    error.status = response.status;
    error.payload = data;
    throw error;
  }

  return data;
}

export function getPistas(params) {
  const query = new URLSearchParams(params).toString();
  return request(`/pistas${query ? `?${query}` : ''}`);
}

export function getComplejos(params = { per_page: 100 }) {
  const query = new URLSearchParams(params).toString();
  return request(`/complejos${query ? `?${query}` : ''}`);
}

export function checkAvailability(payload) {
  return request('/checkout/availability', {
    method: 'POST',
    body: JSON.stringify(payload),
  });
}

export function checkout(payload) {
  return request('/checkout', {
    method: 'POST',
    body: JSON.stringify(payload),
  });
}
