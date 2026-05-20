<template>
  <div class="bpt-topbar">

    <div class="bpt-topbar-left">
      <span class="bpt-logo">🎾 Contactos Pádel</span>
    </div>

    <div class="bpt-tabs">

      <button v-for="tab in tabs" :key="tab.id" :class="['bpt-tab', { active: activeTab === tab.id }]"
        @click="$emit('change-tab', tab.id)">
        {{ tab.label }}
      </button>

    </div>
  </div>
</template>

<script setup lang="ts">
// --- DEFINICIÓN DE PROPS (Datos de entrada) ---
// TypeScript obliga a que el componente padre pase estas dos propiedades de forma estricta.
defineProps<{
  // activeTab: Guarda el string que identifica a la sección visible (ej: 'contacts', 'groups' o 'stats')
  activeTab: string;

  // tabs: Es un array de objetos de solo lectura ('ReadonlyArray') para prevenir mutaciones accidentales.
  // Cada objeto interno debe cumplir con tener un 'id' string y un 'label' string.
  tabs: ReadonlyArray<{ id: string; label: string }>;
}>();

// --- DEFINICIÓN DE EMITS (Eventos de salida) ---
// Registra formalmente los eventos que este componente presentacional está autorizado a "gritar" hacia arriba.
defineEmits<{
  // Especifica que cuando se emita 'change-tab', es de carácter obligatorio adjuntar una tupla 
  // que contenga un único argumento de tipo string (el identificador 'tabId' de destino).
  'change-tab': [tabId: string];
}>();
</script>