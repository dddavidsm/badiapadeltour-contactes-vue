# Modulo Contactos Padel - Arquitectura Multicapa Atomica

## Objetivo
Consolidar el patron:

Smart Components (Vistas) -> Servicios Atomicos -> Fake REST API (json-server)

## Decisiones de arquitectura

1. Sin store global
- Eliminado el estado global centralizado.
- Cada vista mantiene su estado reactivo local (`ref`, `reactive`, `computed`).

2. Sin fachadas de agregacion
- No existe `AppDataService`.
- No existe ninguna funcion tipo `getContactsViewData` en servicios.

3. Servicios atomicos
- `ContactService.ts`: CRUD directo de contactos sobre `http://localhost:3001/contactos`.
- `GrupoService.ts`: CRUD directo de grupos sobre `http://localhost:3001/grupos`.
- Cada metodo hace una sola responsabilidad HTTP.

4. Vistas como controladores
- `ContactosView.vue`, `GruposView.vue` y `StatsView.vue` combinan servicios en `onMounted` con `Promise.all`.
- Las vistas orquestan sus casos de uso sin mover esa logica a servicios agregadores.

## Estructura final

```text
resources/js/contacts-app/
  App.vue
  main.ts
  components/
    AppMenu.vue
    ContactoItem.vue
    GrupoItem.vue
  data/
    contact-types.ts
  services/
    ContactService.ts
    GrupoService.ts
  views/
    ContactosView.vue
    GruposView.vue
    StatsView.vue
```

## Contratos de datos

En `resources/js/contacts-app/data/contact-types.ts`:

- `Contacto`
- `Grupo`
- `HistorialItem`
- `ContactoFormData`
- `GrupoFormData`

## Servicios atomicos

### ContactService

`resources/js/contacts-app/services/ContactService.ts`

Metodos:

- `getContacts()`
- `getContactById(id)`
- `createContact(payload)`
- `updateContact(id, payload)`
- `deleteContact(id)`

### GrupoService

`resources/js/contacts-app/services/GrupoService.ts`

Metodos:

- `getGroups()`
- `getGroupById(id)`
- `createGroup(payload)`
- `updateGroup(id, payload)`
- `deleteGroup(id)`

## Vistas Smart

### ContactosView

- Carga `contactos` y `grupos` con:
  - `Promise.all([ContactService.getContacts(), GrupoService.getGroups()])`
- Alta/edicion con `createContact` y `updateContact`.
- Borrado con `deleteContact`.

### GruposView

- Carga `grupos` y `contactos` con:
  - `Promise.all([ContactService.getContacts(), GrupoService.getGroups()])`
- Alta/edicion con `createGroup` y `updateGroup`.
- Borrado con `deleteGroup`.

### StatsView

- Carga `contactos` y `grupos` con:
  - `Promise.all([ContactService.getContacts(), GrupoService.getGroups()])`
- Calcula totales, ultimos 7 dias y distribucion por grupo.

## Infraestructura de datos

Archivo raiz: `db.json`

Colecciones:

- `contactos`
- `grupos`
- `historial`

## Arranque local

Scripts definidos en `package.json`:

- `dev`: levanta Vite.
- `api`: levanta json-server en el puerto 3001.
- `app`: ejecuta `run-p dev api` para frontend + API fake en paralelo.

Comando unico:

```bash
npm run app
```

Con esta configuracion, la app frontend y json-server arrancan juntos y la capa de servicios atomicos funciona contra la API fake local.
