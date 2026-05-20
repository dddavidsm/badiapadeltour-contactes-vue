<template>
  <div class="bpt-topbar">
    <div class="bpt-topbar-title">
      <span>Contactos Padel</span>
    </div>

    <div class="bpt-tabs">
      <button
        v-for="tab in tabs"
        :key="tab.id"
        class="bpt-tab"
        :class="{ active: activeView === tab.id }"
        :disabled="activeView === tab.id"
        @click="onChangeView(tab.id)"
      >
        {{ tab.label }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  activeView: 'contactos' | 'grupos' | 'stats';
  onChangeView: (view: 'contactos' | 'grupos' | 'stats') => void;
}>();

const tabs = [
  { id: 'contactos', label: 'Contactos' },
  { id: 'grupos', label: 'Grupos' },
  { id: 'stats', label: 'Estadisticas' },
] as const;
</script>
