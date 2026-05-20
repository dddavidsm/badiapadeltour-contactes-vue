<template>
  <div class="bpt-stats">
    <div class="bpt-stat-cards">
      <div class="bpt-stat-card">
        <span class="bpt-stat-value">{{ contacts.length }}</span>
        <span class="bpt-stat-label">Total contactos</span>
      </div>

      <div class="bpt-stat-card">
        <span class="bpt-stat-value">{{ recentContactsCount }}</span>
        <span class="bpt-stat-label">Ultimos 7 dias</span>
      </div>
    </div>

    <div class="bpt-stats-groups">
      <h4>Contactos por grupo</h4>

      <div v-for="group in groups" :key="group.id" class="bpt-bar-row">
        <span class="bpt-color-dot" :style="{ background: group.color }"></span>
        <span class="bpt-bar-name">{{ group.name }}</span>
        <span class="bpt-bar-track">
          <span class="bpt-bar-fill" :style="{ width: barWidth(group.id), background: group.color }"></span>
        </span>
        <span class="bpt-bar-count">{{ contactsPerGroup[group.id] ?? 0 }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { contacts, groups, contactsPerGroup, recentContactsCount } from '../store';

const maxCount = computed(() => Math.max(...Object.values(contactsPerGroup.value), 1));

function barWidth(groupId: number) {
  const count = contactsPerGroup.value[groupId] ?? 0;
  return `${Math.round((count / maxCount.value) * 100)}%`;
}
</script>
