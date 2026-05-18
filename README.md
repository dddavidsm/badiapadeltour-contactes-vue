# Badia Padel Tour - Laravel + Vue Contactos Padel

Proyecto Laravel con un apartado integrado en Vue 3 + TypeScript para buscar pareja de padel, con CRUD y filtros.

## Novedad principal: Contactos Padel (Vue)

Se ha anadido una pagina integrada en el sitio publico:

- Ruta: `/contactos-padel`
- Menu: enlace `Parejas`
- Vista Blade que monta Vue
- App Vue con TypeScript y estructura `data/`

Requisitos funcionales cubiertos:

- TypeScript con interfaces/types
- Reactividad con computed properties
- Formularios con `v-model` y validacion basica
- Listado dinamico con `v-for` + busqueda/filtros
- CRUD completo (Create, Read, Update, Delete)
- CRUD contra API externa (`jsonplaceholder.typicode.com`)
- Estilos con variables CSS y colores del proyecto

## Estructura relevante

- `resources/views/contactos-padel.blade.php`
- `resources/js/contacts-app/main.ts`
- `resources/js/contacts-app/ContactosPadelApp.vue`
- `resources/js/contacts-app/data/contact-types.ts`
- `resources/js/contacts-app/data/contacts-api.ts`
- `routes/web.php`
- `app/Http/Controllers/Bpt/PageController.php`
- `resources/views/components/header.blade.php`

## Docker (despliegue rapido)

El despliegue Docker queda en un comando y ya compila assets Vite dentro de la imagen.

### 1. Requisitos

- Docker Desktop
- Docker Compose v2

### 2. Levantar todo

Desde la raiz del proyecto:

```bash
docker compose up --build -d
```

Servicios expuestos:

- App Laravel (incluye pagina Vue integrada): http://localhost:8080
- MySQL: localhost:3307

### 3. Parar entorno

```bash
docker compose down
```

Para borrar tambien los datos de MySQL:

```bash
docker compose down -v
```

### 4. Ver logs

```bash
docker compose logs -f backend
docker compose logs -f db
```

## Desarrollo sin Docker

### Backend + assets

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run dev
php artisan serve
```

Abrir: http://127.0.0.1:8000/contactos-padel

## Publicar en GitHub sin historial anterior

Objetivo: subir el proyecto al repo `https://github.com/dddavidsm/badiapadeltour-contactes-vue` con historial limpio (sin commits de repos anteriores).

### Opcion recomendada: rama orphan + push forzado

Ejecuta desde este proyecto:

```bash
git checkout --orphan release-contactes-vue
git add -A
git commit -m "Initial clean release: Laravel + Vue contactos padel + Docker"

git remote remove origin
git remote add origin https://github.com/dddavidsm/badiapadeltour-contactes-vue.git

git push -u origin release-contactes-vue:main --force
```

Con esto, el repo destino queda con un historial nuevo (limpio) y un commit inicial.

## Checklist rapido post-despliegue

1. Entrar a `/contactos-padel`.
2. Verificar que carga contactos iniciales.
3. Crear, editar y eliminar un contacto.
4. Probar busqueda por nombre/ciudad y filtros por nivel/disponibilidad.
