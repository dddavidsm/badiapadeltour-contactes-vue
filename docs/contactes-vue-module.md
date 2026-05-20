

# 🗺️ Guion de Ejecución: Módulo de Contactos del Badia Padel Tour

---

## 🧵 PASO 1: La Conexión e Inyección (Laravel ➡️ Vue 3)

*¿Cómo se conecta el backend con el frontend y dónde nace la aplicación?*

### 1. La Ruta en Laravel (`routes/web.php`)

Laravel es quien sigue controlando el sistema de rutas del servidor. Cuando el usuario navega a la URL de contactos, esta ruta responde:

```php
Route::get('/contactos-padel', [PageController::class, 'contactosPadel'])
    ->name('contactos.padel');

```

### 2. El Controlador (`PageController.php`)

El controlador de Laravel no consulta ninguna base de datos SQL para este módulo; simplemente se limita a retornar la vista puente en Blade:

```php
public function contactosPadel()
{
    return view('contactos-padel');
}

```

### 3. La Vista Puente (`contactos-padel.blade.php`)

Esta plantilla Blade es el "anclaje". Carga los archivos compilados por Vite (CSS, JS y el punto de entrada de TypeScript) y prepara un elemento HTML vacío con un ID único (`#contactos-padel-app`). Vue necesita este contenedor para saber exactamente dónde inyectarse sin romper el resto de la página:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/contacts-app/main.ts'])

<div id="contactos-padel-app"></div>

```

### 4. El Punto de Entrada de Vue (`main.ts`)

TypeScript entra en acción. Busca el contenedor por su ID, inicializa la instancia de Vue (`createApp`) cargando el componente raíz (`ContactosPadelApp.vue`) y lo "monta" en el DOM:

```typescript
import { createApp } from 'vue';
import ContactosPadelApp from './ContactosPadelApp.vue';
import '../../css/contacts-app.css'; // Carga los estilos específicos del módulo

const app = createApp(ContactosPadelApp);
app.mount('#contactos-padel-app');

```

---

## 🏗️ PASO 2: Inicialización, Ciclo de Vida y Carga de Datos

*¿Qué pasa inmediatamente cuando la aplicación "despierta" en el navegador?*

### 1. El Gancho del Ciclo de Vida (`onMounted`)

En cuanto el componente raíz se inserta en la pantalla, se dispara de forma automática la función asíncrona `onMounted`. Su función es activar un estado visual de carga (`loading = true`) y coordinar la petición de datos:

```typescript
onMounted(async () => {
  groups.value = loadGroups(); // 1. Carga inmediata de grupos desde localStorage
  loading.value = true;        // 2. Activa el estado de carga visual
  contacts.value = await loadContacts(); // 3. Pide los contactos (espera a la API si es necesario)
  loading.value = false;       // 4. Apaga el estado de carga
});

```

### 2. Estrategia Híbrida de Persistencia (`contacts-api.ts`)

Para no saturar la red y simular un entorno real, la API aplica una lógica condicional:

1. Si encuentra datos guardados previamente en el navegador (`localStorage`), los devuelve al instante.
2. Si es la primera visita, realiza una petición `fetch` a un servicio externo remoto de pruebas, transforma los objetos genéricos a nuestra estructura e interfaz `Contact`, los mezcla con datos base y los almacena localmente para el futuro.

```typescript
export async function loadContacts(): Promise<Contact[]> {
    // 1. Intenta leer de la caché local del navegador
    const raw = localStorage.getItem('bpt_contacts_contacts');
    if (raw) return JSON.parse(raw) as Contact[];

    // 2. Si está vacío, recurre a la API externa de respaldo
    let apiContacts: Contact[] = [];
    try {
        const res = await fetch('https://jsonplaceholder.typicode.com/users?_limit=5');
        if (res.ok) {
            const users = await res.json();
            // Normalización de datos externos al formato estructurado de nuestra aplicación
            apiContacts = users.map((u: any, i: number) => ({
                id: 2000 + i,
                name: u.name.split(' ')[0],
                surname: u.name.split(' ').slice(1).join(' ') || 'BPT',
                phone: `+346${String(10000000 + i).padStart(8, '0')}`,
                email: u.email,
                groupId: DEFAULT_GROUPS[i % DEFAULT_GROUPS.length].id,
                city: 'Sabadell',
                createdAt: new Date().toISOString(),
            }));
        }
    } catch { /* Resiliencia: si falla internet, continúa vacío */ }

    const contacts = [...DEFAULT_CONTACTS, ...apiContacts];
    localStorage.setItem('bpt_contacts_contacts', JSON.stringify(contacts));
    return contacts;
}

```

---

## 🎨 PASO 3: Generación de la Vista y Arquitectura de Componentes

*¿Cómo se estructuran las pantallas y cómo se comunican las variables?*

La aplicación utiliza un patrón de arquitectura centralizado: `ContactosPadelApp.vue` actúa como **Componente Contenedor (Cerebro)** encargándose del estado y las funciones de negocio. Los archivos dentro de `components/` actúan como **Componentes Presentacionales (Obreros)**, los cuales no modifican datos directamente; reciben información mediante `Props` y notifican acciones mediante `Emits`.

```
       ContactosPadelApp.vue (Padre - Estado Global)
        /         |         \
   (Props)     (Props)     (Props)   👇 Datos fluyen hacia abajo
   (Emits)     (Emits)     (Emits)   ☝️ Eventos viajan hacia arriba
     /            |            \
Topbar.vue   ListSection.vue   StatsSection.vue

```

### 1. La Barra de Navegación (`ContactsTopbar.vue`)

Pinta las pestañas de selección evaluando qué clase CSS aplicar de forma reactiva, e informa al padre si se cambia de sección:

```vue
<button
  v-for="tab in tabs"
  :key="tab.id"
  :class="['bpt-tab', { active: activeTab === tab.id }]"
  @click="$emit('change-tab', tab.id)"
>
  {{ tab.label }}
</button>

<script setup lang="ts">
defineProps<{ activeTab: string; tabs: ReadonlyArray<{ id: string; label: string }> }>();
defineEmits<{ 'change-tab': [tabId: string] }>();
</script>

```

### 2. La Sección Principal (`ContactsListSection.vue`)

Recibe los contactos previamente procesados por el padre y renderiza la rejilla de tarjetas HTML. Delegando los clicks de edición o borrado:

```vue
<div v-for="contact in contacts" :key="contact.id" class="bpt-card">
  <div class="bpt-avatar" :style="{ background: groupColorOf(contact.groupId) }">
    {{ initials(contact.name, contact.surname) }}
  </div>
  <strong>{{ contact.name }} {{ contact.surname }}</strong>
  <button @click="$emit('edit-contact', contact)">✏️</button>
  <button @click="$emit('delete-contact', contact.id)">🗑️</button>
</div>

```

---

## 🔍 PASO 4: El Motor Reactivo (Búsqueda, Filtro y Ordenación)

*¿Cómo se recalculan las listas en pantalla instantáneamente sin recargar la página?*

Para lograr esto de forma óptima sin alterar la base de datos original (`contacts.value`), se utiliza una **Propiedad Computada (`computed`)** en el componente padre. Las propiedades computadas almacenan su resultado en caché y se recalculan solas únicamente cuando alguna de las variables reactivas de su interior sufre un cambio.

```typescript
const filteredContacts = computed<Contact[]>(() => {
  // Guardamos la consulta de búsqueda en minúsculas para una comparación insensible a mayúsculas
  const q = searchQ.value.toLowerCase();
  
  // FASE 1: FILTRADO COINCIDENTE
  let list = contacts.value.filter((c) => {
    // Comprueba si el texto ingresado coincide con el nombre, apellido o teléfono del jugador
    const matchSearch = !q 
      || c.name.toLowerCase().includes(q)
      || c.surname.toLowerCase().includes(q)
      || c.phone.includes(q);
      
    // Comprueba si el grupo del jugador se corresponde con el desplegable de filtro seleccionado
    const matchGroup = filterGroup.value === '' || c.groupId === filterGroup.value;
    
    return matchSearch && matchGroup; // El contacto debe cumplir ambos requisitos
  });
  
  // FASE 2: ORDENACIÓN
  // Buenas prácticas: Usamos [...list] para clonar el array filtrado. 
  // El método .sort() nativo modifica el array sobre el que actúa; mutar datos dentro de una computada está prohibido en Vue.
  if (sortBy.value === 'name') {
    list = [...list].sort((a, b) => a.name.localeCompare(b.name)); // Orden alfabético A-Z
  }
  if (sortBy.value === 'date') {
    list = [...list].sort((a, b) => b.createdAt.localeCompare(a.createdAt)); // Más recientes primero
  }
  
  return list; // Devuelve la lista final procesada lista para ser pintada
});

```

---

## 📝 PASO 5: Formularios y Captura de Datos (`v-model`)

*¿Cómo viaja la información desde los campos de texto del modal hacia las variables internas?*

Para sincronizar los formularios en tiempo real, se implementa el enlace bidireccional mediante la directiva **`v-model`**. Cuando el usuario escribe, el estado reactivo del JavaScript se actualiza de inmediato; si el JavaScript cambia, el campo del formulario refleja dicho valor.

En `ContactosPadelApp.vue`, se agrupan los campos en un objeto reactivo estructurado (`cForm`):

```vue
<template>
  <input v-model.trim="cForm.name" type="text" placeholder="Nombre" />
  <input v-model.trim="cForm.phone" type="tel" placeholder="Teléfono" />
  
  <select v-model="cForm.groupId">
    <option :value="null">Selecciona grupo</option>
    <option v-for="g in groups" :key="g.id" :value="g.id">{{ g.name }}</option>
  </select>
</template>

<script setup lang="ts">
import { reactive } from 'vue';
import type { ContactFormData } from './data/contact-types';

// reactive es ideal para estructurar objetos complejos de formularios agrupados
const cForm = reactive<ContactFormData>({
  name: '', surname: '', phone: '', email: '', groupId: null, city: '',
});
</script>

```

---

## 🛡️ PASO 6: Lógica de Negocio, Validaciones y Guardado (Alta y Edición)

*¿Qué pasa cuando se pulsa el botón "Guardar" de un formulario?*

Este es el proceso crítico donde se aplican las reglas de negocio de la aplicación antes de persistir los datos.

### 1. Capa de Validación Reglas de Negocio (`validateContactForm`)

Antes de guardar nada, la función comprueba los requisitos de longitud, formatos por expresiones regulares (Regex) y lanza una consulta de prevención de duplicados:

```typescript
function validateContactForm(): boolean {
  let ok = true;
  clearErrors(cErrors); // Limpia mensajes rojos previos

  // Regla 1: Validaciones de longitud mínima
  if (cForm.name.length < 2) { cErrors.name = 'Mínimo 2 caracteres'; ok = false; }
  
  // Regla 2: Formato de expresiones regulares para el teléfono internacional E.164
  const PHONE_RE = /^\+?[1-9]\d{7,14}$/;
  if (!PHONE_RE.test(cForm.phone)) { cErrors.phone = 'Formato de teléfono inválido'; ok = false; }

  // Regla 3: Validación de Duplicados (Clave única lógica)
  // Busca si ya existe un jugador con ese mismo número de teléfono.
  // IMPORTANTE: Excluye de la búsqueda al 'editingContactId' actual; de lo contrario, 
  // al editar un contacto sin cambiar su teléfono saltaría un falso positivo diciendo que está duplicado.
  const duplicate = contacts.value.find(
    (c) => c.phone === cForm.phone && c.id !== editingContactId.value
  );
  if (duplicate) {
    cErrors.phone = 'Este número de teléfono ya está registrado en otro contacto';
    ok = false;
  }

  return ok; // Si devuelve false, el proceso de guardado se detiene de inmediato
}

```

### 2. Bifurcación Alta / Edición (`submitContactForm`)

Si las validaciones se superan con éxito, se unifican los datos del formulario y se determina el flujo correspondiente según el estado de `editingContactId`:

```typescript
function submitContactForm(): void {
  if (!validateContactForm()) return; // Freno de mano de seguridad si hay errores en el formulario

  // Empaqueta los valores limpios del formulario en un objeto payload limpio
  const payload = {
    name: cForm.name,
    surname: cForm.surname,
    phone: cForm.phone,
    email: cForm.email,
    groupId: cForm.groupId as number,
    city: cForm.city,
  };

  // CASO A: MODO EDICIÓN (editingContactId contiene un número de ID válido)
  if (editingContactId.value !== null) {
    // Busca el índice de la posición original del contacto dentro de nuestra matriz reactiva
    const index = contacts.value.findIndex((c) => c.id === editingContactId.value);
    if (index !== -1) {
      // Sobrescribe el objeto exacto manteniendo las propiedades intactas que no estaban en el formulario 
      // (como el id original y su fecha de creación inicial) fusionando el nuevo payload
      contacts.value[index] = { ...contacts.value[index], ...payload };
    }
    
  // CASO B: MODO ALTA NUEVA (editingContactId es null)
  } else {
    // Inserta un nuevo objeto al final del array. Como no tenemos base de datos relacional conectada por API,
    // simulamos una clave primaria autoincremental única utilizando el timestamp de 'Date.now()'
    contacts.value.push({
      id: Date.now(),
      ...payload,
      createdAt: new Date().toISOString(), // Setea la fecha actual en formato ISO estándar
    });
  }

  persistContacts();   // 1. Envía el array entero actualizado a localStorage para guardarlo en disco
  closeContactModal(); // 2. Cierra la interfaz del modal visual de cara al usuario
}

```