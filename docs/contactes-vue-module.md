# Modulo Contactos Padel (Vue + json-server)

## Resumen
Este modulo usa arquitectura multicapa:

1. Vistas: estado de UI, formularios y eventos de usuario.
2. Servicios: reglas de negocio y orquestacion.
3. API: llamadas HTTP cortas con `fetch` via helper `request()`.

Persistencia de datos: `json-server` en `http://localhost:3001` sobre `db.json`.

## Flujo de ejecucion

1. Laravel renderiza una vista Blade con el contenedor `#contactos-padel-app`.
2. `resources/js/contacts-app/main.ts` monta la app Vue.
3. `resources/js/contacts-app/App.vue` selecciona vista activa:
   - Contactos
   - Grupos
   - Estadisticas
4. Cada vista llama a servicios.
5. Los servicios llaman a capa API.
6. La capa API usa `request()` para hablar con `json-server`.

## Estructura actual

```text
resources/js/contacts-app/
  App.vue
  main.ts
  api/
    request.ts
    contactApi.ts
    grupoApi.ts
    historialApi.ts
  services/
    ContactoService.ts
    GrupoService.ts
    HistorialService.ts
  data/
    contact-types.ts
  views/
    ContactosView.vue
    GruposView.vue
    StatsView.vue
  components/
    AppMenu.vue
    ContactoItem.vue
    GrupoItem.vue
```

## Modelo de datos

Definido en `resources/js/contacts-app/data/contact-types.ts`:

- `Grupo`
- `Contacto`
- `HistorialItem`
- `GrupoFormData`
- `ContactoFormData`

## Capa API

### request helper

`resources/js/contacts-app/api/request.ts` centraliza:

- base URL (`http://localhost:3001`)
- control de errores HTTP
- parse de respuesta JSON

Ejemplo:

```ts
return request<Contacto[]>('/contactos');
```

### API de Contactos

`resources/js/contacts-app/api/contactApi.ts`

- `getContactosApi()`
- `getContactoByIdApi(id)`
- `createContactoApi(payload)`
- `updateContactoApi(id, payload)`
- `deleteContactoApi(id)`

### API de Grupos

`resources/js/contacts-app/api/grupoApi.ts`

- `getGruposApi()`
- `getGrupoByIdApi(id)`
- `createGrupoApi(payload)`
- `updateGrupoApi(id, payload)`
- `deleteGrupoApi(id)`

### API de Historial

`resources/js/contacts-app/api/historialApi.ts`

- `getHistorialApi()`
- `getHistorialByIdApi(id)`
- `createHistorialApi(payload)`
- `updateHistorialApi(id, payload)`
- `deleteHistorialApi(id)`

## Capa Servicios

### ContactoService

`resources/js/contacts-app/services/ContactoService.ts`

Responsabilidades:

- Orquestacion de datos para vistas con `getContactosViewData()`.
- Metodo `saveContacto(payload, id?)` para alta/edicion.
- Metodo `removeContacto(id)` para borrado.
- Registro automatico en historial al guardar o eliminar.
- No expone metodos pasarela redundantes cuando solo replican el CRUD de `contactApi`.

### GrupoService

`resources/js/contacts-app/services/GrupoService.ts`

Responsabilidades:

- CRUD de grupos via API.
- Metodo `saveGrupo(payload, id?)` para alta/edicion.
- Metodo `removeGrupo(id)` para borrado.

### HistorialService

`resources/js/contacts-app/services/HistorialService.ts`

Encapsula operaciones sobre historial.

## Capa Vistas

### ContactosView

`resources/js/contacts-app/views/ContactosView.vue`

- Carga inicial en `onMounted` usando `ContactoService.getContactosViewData()`.
- Estado reactivo de filtros y modal.
- Formulario de contacto.
- Llama a `ContactoService`.

### GruposView

`resources/js/contacts-app/views/GruposView.vue`

- Carga grupos y contactos con `ContactoService.getContactosViewData()` para mostrar conteo por grupo.
- Alta/edicion/borrado de grupos.
- Sin `emit`: se usan callbacks por props en componentes hijos.

### StatsView

`resources/js/contacts-app/views/StatsView.vue`

- Carga grupos y contactos con `ContactoService.getContactosViewData()`.
- Calcula:
  - total de contactos
  - contactos de ultimos 7 dias
  - contactos por grupo (barras)

## Componentes presentacionales

- `resources/js/contacts-app/components/ContactoItem.vue`
- `resources/js/contacts-app/components/GrupoItem.vue`

Ambos son componentes tontos:

- reciben datos por props
- invocan callbacks por props (`onEdit`, `onDelete`)
- no emiten eventos

## Datos de desarrollo

`db.json` define colecciones:

- `contactos`
- `grupos`
- `historial`

Ejecutar json-server:

```bash
npx json-server --watch db.json --port 3001
```

## Estado actual de la refactorizacion

- Arquitectura separada por capas completada.
- Nomenclatura estandarizada a `Contacto` y `Grupo`.
- Servicios y APIs simplificados y cortos.
- Eliminados archivos duplicados/legacy de servicios anteriores.
- Validacion de TypeScript en `contacts-app` sin errores.
