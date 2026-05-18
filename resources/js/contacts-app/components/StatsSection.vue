<template>
  <div class="bpt-section">
    <div class="bpt-stats-grid">
      <div class="bpt-stat-card">
        <span class="bpt-stat-val">{{ contactsCount }}</span>
        <span class="bpt-stat-label">Total contactes</span>
      </div>
      <div class="bpt-stat-card">
        <span class="bpt-stat-val">{{ recentContactsCount }}</span>
        <span class="bpt-stat-label">Últims 7 dies</span>
      </div>
    </div>

    <div class="bpt-card" style="margin-top:1rem">
      <h4 style="margin:0 0 .75rem;color:var(--electric)">Contactes per grup</h4>
      <div v-for="group in groups" :key="group.id" class="bpt-stat-row">
        <span class="bpt-group-dot" :style="{ background: group.color }"></span>
        <span>{{ group.name }}</span>
        <span class="bpt-bar-wrap">
          <span class="bpt-bar" :style="{ width: barWidth(contactsPerGroup[group.id] ?? 0), background: group.color }"></span>
        </span>
        <span class="bpt-muted">{{ contactsPerGroup[group.id] ?? 0 }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { Group } from '../data/contact-types';

const props = defineProps<{
  contactsCount: number;
  recentContactsCount: number;
  groups: Group[];
  contactsPerGroup: Record<number, number>;
}>();

const maxContacts = computed(() => Math.max(...Object.values(props.contactsPerGroup), 1));

function barWidth(count: number): string {
  return `${Math.round((count / maxContacts.value) * 100)}%`;
}
</script>