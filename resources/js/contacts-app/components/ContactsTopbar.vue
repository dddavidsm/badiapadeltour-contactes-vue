<template>
  <div class="bpt-topbar">
    <div class="bpt-topbar-title">
      <span>Contactos Padel</span>
    </div>

    <div class="bpt-tabs">
      <!-- Al hacer clic, el hijo llama DIRECTAMENTE a la función del padre recibida por prop.
           No hay emit: la función viaja hacia abajo (prop) y se ejecuta aquí abajo. -->
      <button v-for="tab in tabs" :key="tab.id" class="bpt-tab" :class="{ active: activeTab === tab.id }" :disabled="activeTab === tab.id"
              @click="onChangeTab(tab.id)">
        {{ tab.label }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
// PROPS: Recibe los datos de visualización Y la función controladora del padre.
// Sin defineEmits: el hijo no anuncia nada hacia arriba, simplemente ejecuta lo que recibió.
defineProps<{
  // Identifica la pestaña activa para deshabilitar su botón
  activeTab: string;
  // Array de solo lectura con los datos de cada pestaña
  tabs: ReadonlyArray<{ id: string; label: string }>;
  // Función del padre: actualiza activeTab cuando el usuario hace clic en una pestaña
  onChangeTab: (tabId: string) => void;
}>();
</script>