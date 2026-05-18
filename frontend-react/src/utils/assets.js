const ASSET_BASE_URL = import.meta.env.VITE_ASSET_BASE_URL || '';

export function toAssetUrl(path) {
  if (!path) {
    return '';
  }

  if (/^https?:\/\//i.test(path)) {
    return path;
  }

  const cleanPath = path.startsWith('/') ? path : `/${path}`;
  const base = ASSET_BASE_URL.endsWith('/') ? ASSET_BASE_URL.slice(0, -1) : ASSET_BASE_URL;
  return `${base}${cleanPath}`;
}
