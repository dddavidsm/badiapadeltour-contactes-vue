<template>
  <div class="bpt-app">
    
    <AppMenu :active-view="activeView" :on-change-view="changeView" />

    <ContactosView v-if="activeView === 'contactos'" />
    
    <GruposView v-else-if="activeView === 'grupos'" />
    
    <StatsView v-else />
    
  </div>
</template>

<script setup lang="ts">
// Importamos la función 'ref' de Vue, que sirve para crear variables "reactivas" (que actualizan el HTML automáticamente al cambiar)
import { ref } from 'vue';

// Importamos los "ladrillos" (componentes) que vamos a usar arriba en el <template>
import AppMenu from './components/AppMenu.vue';
import ContactosView from './views/ContactosView.vue';
import GruposView from './views/GruposView.vue';
import StatsView from './views/StatsView.vue';

/**
 * ESTADO DE LA APLICACIÓN
 * Creamos una variable reactiva llamada 'activeView'.
 * Entre los símbolos < > usamos TypeScript para blindar la variable: 
 * solo podrá contener los textos 'contactos', 'grupos' o 'stats'. Ningún otro.
 * El valor ('contactos') del final es el valor por defecto al cargar la página.
 */
const activeView = ref<'contactos' | 'grupos' | 'stats'>('contactos');

/**
 * FUNCIÓN CONTROLADORA
 * Esta función es la encargada de cambiar de pantalla.
 * Recibe como parámetro el nombre de la nueva vista a la que queremos ir.
 */
function changeView(view: 'contactos' | 'grupos' | 'stats') {
  // Para cambiar el valor de una variable creada con 'ref', siempre debemos añadir el ".value"
  // Al hacer esto, Vue detecta el cambio de forma automática y recalcula los "v-if" de arriba.
  activeView.value = view;
}
</script>