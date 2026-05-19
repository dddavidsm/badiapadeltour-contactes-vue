# Mòdul de Contactes Vue

## Resum

Aquest mòdul afegeix una petita zona feta amb Vue 3 + TypeScript dins del projecte Laravel existent. No substitueix res del projecte antic: Laravel continua portant rutes, Blade continua renderitzant la pàgina i Bootstrap continua donant el marc visual global. Vue només s'encarrega del contingut dins de `#contactos-padel-app`.

Funcions actuals:
- CRUD de contactes
- CRUD de grups
- Cerca, filtre i ordenació
- Estadístiques bàsiques
- Persistència local al navegador

S'ha eliminat el sistema antic d'historial/call entry per deixar el mòdul més net.

## Com es connecta amb Laravel i Blade

La connexió és simple:

1. Laravel exposa la ruta `/contactos-padel`.
2. El controlador retorna la vista Blade `contactos-padel`.
3. Aquesta vista inclou el bundle de Vite i pinta un contenidor buit.
4. Vue es munta dins d'aquest contenidor.

Exemple de la vista:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/contacts-app/main.ts'])

<div id="contactos-padel-app"></div>
```

Laravel només deixa el contenidor al HTML i `main.ts` hi munta Vue. El mòdul ja no depèn de login, usuari ni dades d'autenticació.

## Connexió explicada per fitxers

### 1. Ruta HTTP
Fitxer: `routes/web.php`

Aquí es registra la URL que obre el mòdul:

```php
Route::get('/contactos-padel', [PageController::class, 'contactosPadel'])
    ->name('contactos.padel');
```

Sense aquest fitxer no existeix la ruta dins de Laravel.

### 2. Controlador
Fitxer: `app/Http/Controllers/Bpt/PageController.php`

Aquest mètode és el que respon a la ruta i retorna la vista:

```php
public function contactosPadel()
{
    return view('contactos-padel');
}
```

Aquí Laravel encara no sap res de Vue: només entrega una vista Blade normal.

### 3. Vista Blade pont
Fitxer: `resources/views/contactos-padel.blade.php`

Aquest és el fitxer més important de la connexió perquè fa de pont entre backend i frontend.

Fa dues coses:

- carrega els assets compilats amb Vite,
- i crea el `div` on es muntarà Vue.

També passa dades del servidor al client amb atributs `data-*`:

```blade
<div id="contactos-padel-app"></div>
```

Per tant, aquest fitxer és el lloc exacte on Laravel reserva l'espai perquè Vue es renderitzi, però sense passar dades d'usuari.

### 4. Entrada de Vue
Fitxer: `resources/js/contacts-app/main.ts`

Aquest fitxer busca el contenidor creat per Blade i hi munta l'aplicació Vue:

```ts
const root = document.getElementById('contactos-padel-app');

if (root) {
    createApp(ContactosPadelApp).mount(root);
}
```

Aquí es fa la unió directa amb el DOM que havia renderitzat Laravel.

### 5. Component arrel
Fitxer: `resources/js/contacts-app/ContactosPadelApp.vue`

Aquest component ja no rep props d'usuari. Coordina el mòdul i ara està dividit en seccions filles:

- `components/ContactsTopbar.vue`
- `components/ContactsListSection.vue`
- `components/GroupsSection.vue`
- `components/StatsSection.vue`

Aquest fitxer ja no fa tota la UI ell sol: ara reparteix la renderització però continua sent el centre de l'estat, formularis i CRUD.

### 6. Tipus i dades compartides
Fitxer: `resources/js/contacts-app/data/contact-types.ts`

Aquí es defineixen:

- els tipus `Contact` i `Group`,
- les dades inicials `DEFAULT_CONTACTS` i `DEFAULT_GROUPS`,
- la llista `PADEL_CITIES`.

`PADEL_CITIES` està aquí i no dins del component perquè és una dada compartida del domini. No és una decisió visual del formulari, sinó una llista reutilitzable del mòdul.

### 7. Càrrega i persistència
Fitxer: `resources/js/contacts-app/data/contacts-api.ts`

Aquest fitxer encapsula la part de dades:

- carrega grups des de `localStorage`,
- fa la primera càrrega des de la fake API,
- adapta la resposta externa al format `Contact`,
- guarda després els canvis al navegador.

La fake API s'usa només per tenir una font externa inicial. La persistència real es fa amb `localStorage`, perquè JSONPlaceholder no desa canvis de veritat. El guardat és únic per navegador, no per usuari.

## Per què `contact-types.ts`

`contact-types.ts` concentra les peces comunes del mòdul:

- els tipus `Contact` i `Group`,
- les dades inicials `DEFAULT_CONTACTS` i `DEFAULT_GROUPS`,
- i la llista `PADEL_CITIES`.

Les ciutats es defineixen aquí perquè no són només un detall visual del formulari: formen part del domini del mòdul. Tenir-les en aquest fitxer evita repetir-les dins del component, permet reutilitzar-les fàcilment i deixa `ContactosPadelApp.vue` més centrat en la UI i la lògica interactiva.

## Per què fake API + memòria local

La càrrega inicial usa `https://jsonplaceholder.typicode.com/users` com a fake API para cumplir el requisito de usar una API externa. Pero esa API no persiste cambios reales, así que el CRUD de verdad se guarda en `localStorage`.

El flujo es este:

1. Primera visita: se consulta la fake API.
2. Los datos recibidos se adaptan al tipo `Contact`.
3. Se mezclan con los contactos por defecto.
4. Se guarda todo en `localStorage`.
5. A partir de ahí, se trabaja en memoria del navegador para que altas, ediciones y borrados sí se mantengan.

No es memoria RAM temporal, sino almacenamiento local del navegador. Se usa porque el módulo no tiene backend propio para este CRUD.

## Estructura mínima

```text
resources/js/contacts-app/main.ts
resources/js/contacts-app/ContactosPadelApp.vue
resources/js/contacts-app/data/contact-types.ts
resources/js/contacts-app/data/contacts-api.ts
resources/views/contactos-padel.blade.php
app/Http/Controllers/Bpt/PageController.php
routes/web.php
```

## Qué hace cada archivo

- `main.ts`: monta Vue.
- `ContactosPadelApp.vue`: interfaz, formularios, filtros y CRUD.
- `contact-types.ts`: tipos y datos compartidos.
- `contacts-api.ts`: carga inicial y persistencia en `localStorage`.
- `contactos-padel.blade.php`: puente entre Laravel y Vue.

## Idea técnica del componente

`ContactosPadelApp.vue` se mantiene como un único componente porque el módulo es pequeño. Guarda tres bloques de estado:

- datos: contactos y grupos,
- UI: pestaña activa, modales y carga,
- formularios: datos temporales y errores.

Además usa `computed` para no recalcular manualmente filtros y estadísticas.

## Validación

El formulario de contacto valida nombre, apellidos, teléfono, correo opcional, grupo obligatorio y teléfonos duplicados. El de grupo solo valida nombre y color.

## Decisión final

La idea del módulo es sencilla: aprovechar el proyecto Laravel que ya existía y montar encima una isla Vue pequeña, autocontenida y fácil de mantener.

Tot el comportament del mòdul arrenca aquí: primer els grups locals i després els contactes.

---

## 8. Formularis i validació

El formulari de contacte valida:

- nom mínim de 2 caràcters,
- cognoms mínim de 2 caràcters,
- telèfon amb format tipus E.164,
- correu opcional però correcte si s'omple,
- grup obligatori,
- telèfon no duplicat.

Exemple de validació del telèfon:

```ts
/^\+?[1-9]\d{7,14}$/
```

El formulari de grup és encara més simple:

- nom obligatori amb mínim 2 caràcters,
- color editable.

També hi ha una protecció addicional: no es poden eliminar grups per defecte ni grups que encara tenen contactes assignats.

---

## 9. Decisions de simplificació

Les decisions actuals del mòdul són aquestes:

- Un sol component Vue en lloc de diversos components petits.
- `localStorage` com a persistència real del CRUD.
- API externa només per a la càrrega inicial.
- Dades del backend cap a Vue mitjançant atributs `data-*` a Blade.
- Pestanyes simples amb `v-if`, sense `vue-router`.
- Eliminació completa del sistema d'historial/call entry per reduir complexitat i evitar codi mort.

En resum, el projecte antic de Laravel + Blade + Bootstrap no s'ha trencat ni reescrit: simplement s'ha ampliat amb una part de Vue muntada dins d'una vista concreta, aprofitant Vite com a punt d'unió entre els dos mons.
