#!/bin/sh
set -e

if [ ! -f .env ]; then
  cp .env.example .env
fi

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true

# Limpia caches generadas en host (pueden incluir providers de dev no instalados en produccion)
rm -f bootstrap/cache/*.php || true

php artisan key:generate --force || true

ATTEMPTS=0
until php artisan migrate --force; do
  ATTEMPTS=$((ATTEMPTS + 1))
  if [ "$ATTEMPTS" -ge 10 ]; then
    echo "No se pudo migrar la base de datos tras varios intentos"
    exit 1
  fi
  echo "Esperando a la base de datos..."
  sleep 3
done

php artisan optimize:clear || true
php artisan config:cache || true
php artisan route:cache || true

exec "$@"
