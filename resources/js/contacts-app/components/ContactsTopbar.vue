<template>
  <div class="bpt-topbar">
    <div class="bpt-topbar-left">
      <span class="bpt-logo">🎾 Contactes Pàdel</span>
    </div>
    <div class="bpt-tabs">
      <button
        v-for="tab in tabs"
        :key="tab.id"
        :class="['bpt-tab', { active: activeTab === tab.id }]"
        @click="$emit('change-tab', tab.id)"
      >{{ tab.label }}</button>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  activeTab: string;
  tabs: ReadonlyArray<{ id: string; label: string }>;
}>();

defineEmits<{
  'change-tab': [tabId: string];
}>();
</script>