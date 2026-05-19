# Modulo de Contactos Vue

## Resumen

Este modulo anade una zona Vue 3 + TypeScript dentro del proyecto Laravel existente. No reemplaza el proyecto anterior: Laravel sigue gestionando rutas y Blade sigue renderizando la pagina. Vue solo controla el contenido dentro de `#contactos-padel-app`.

Funcionalidades actuales:
- CRUD de contactos
- CRUD de grupos
- Busqueda, filtro y ordenacion
- Estadisticas basicas
- Persistencia local en el navegador

## Conexion con Laravel por archivos

### 1. Ruta
Archivo: `routes/web.php`

Laravel publica la URL del modulo:

```php
Route::get('/contactos-padel', [PageController::class, 'contactosPadel'])
    ->name('contactos.padel');
```

### 2. Controlador
Archivo: `app/Http/Controllers/Bpt/PageController.php`

El metodo que responde a la ruta devuelve una vista Blade normal:

```php
public function contactosPadel()
{
    return view('contactos-padel');
}
```

### 3. Vista puente
Archivo: `resources/views/contactos-padel.blade.php`

La vista carga Vite y crea el contenedor donde se monta Vue:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/contacts-app/main.ts'])
<div id="contactos-padel-app"></div>
```

Aqui no se pasa ningun dato de login ni usuario.

### 4. Entrada Vue
Archivo: `resources/js/contacts-app/main.ts`

El punto de entrada busca el contenedor y monta la app:

```ts
const root = document.getElementById('contactos-padel-app');
if (root) {
    createApp(ContactosPadelApp).mount(root);
}
```

### 5. Componente raiz
Archivo: `resources/js/contacts-app/ContactosPadelApp.vue`

Gestiona estado, formularios y CRUD. Renderiza secciones hijas:
- `components/ContactsTopbar.vue`
- `components/ContactsListSection.vue`
- `components/GroupsSection.vue`
- `components/StatsSection.vue`

## Datos y persistencia

### Tipos y datos base
Archivo: `resources/js/contacts-app/data/contact-types.ts`

Define:
- Tipos `Contact` y `Group`
- `DEFAULT_CONTACTS`
- `DEFAULT_GROUPS`
- `PADEL_CITIES`

`PADEL_CITIES` esta fuera del componente para reutilizarla y evitar duplicacion.

### API y guardado
Archivo: `resources/js/contacts-app/data/contacts-api.ts`

Estrategia:
1. Primera carga: consulta fake API (`jsonplaceholder`) y normaliza datos.
2. Mezcla con contactos por defecto.
3. Guarda en `localStorage`.
4. Cargas siguientes: lee directamente `localStorage`.

La fake API sirve como fuente externa inicial. El CRUD real persiste en el navegador.

## Flujo del modal de contacto

- Boton Nuevo contacto emite `open-contact` en `ContactsListSection.vue`.
- Boton Editar emite `edit-contact` con el contacto seleccionado.
- El padre (`ContactosPadelApp.vue`) recibe ambos eventos y llama a `openContactForm(...)`.
- `openContactForm(null)` abre alta y `openContactForm(contact)` abre edicion.
- El modal se muestra cuando `showContactModal` vale `true`.

## Validacion de formularios

Contacto:
- Nombre y apellidos min. 2 caracteres
- Telefono con regex E.164
- Correo opcional, pero valido si se informa
- Grupo obligatorio
- Telefono no duplicado

Grupo:
- Nombre min. 2 caracteres
- Color editable

Reglas extra:
- No se puede borrar un grupo con contactos asignados
- No se puede borrar un grupo por defecto
