<template>
  <div class="bpt-app">
    <AppMenu :active-view="activeView" :on-change-view="changeView" />

    <ContactosView v-if="activeView === 'contactos'" />
    <GruposView v-else-if="activeView === 'grupos'" />
    <StatsView v-else />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import AppMenu from './components/AppMenu.vue';
import ContactosView from './views/ContactosView.vue';
import GruposView from './views/GruposView.vue';
import StatsView from './views/StatsView.vue';

const activeView = ref<'contactos' | 'grupos' | 'stats'>('contactos');

function changeView(view: 'contactos' | 'grupos' | 'stats') {
  activeView.value = view;
}
</script>
