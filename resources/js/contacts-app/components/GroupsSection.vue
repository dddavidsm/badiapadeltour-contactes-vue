<template>
  <div class="bpt-section">
    <div class="bpt-filters">
      <button class="bpt-btn bpt-btn-primary" @click="$emit('open-group')">+ Nuevo grupo</button>
    </div>
    <div class="bpt-grid">
      <div v-for="group in groups" :key="group.id" class="bpt-card bpt-group-card">
        <div class="bpt-group-dot" :style="{ background: group.color }"></div>
        <div class="bpt-group-info">
          <strong>{{ group.name }}</strong>
          <span class="bpt-muted">{{ contactsPerGroup[group.id] ?? 0 }} contactos</span>
        </div>
        <div class="bpt-card-actions">
          <button class="bpt-icon-btn" @click="$emit('edit-group', group)">✏️</button>
          <button class="bpt-icon-btn danger" @click="$emit('delete-group', group.id)">🗑️</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Group } from '../data/contact-types';

defineProps<{
  groups: Group[];
  contactsPerGroup: Record<number, number>;
}>();

defineEmits<{
  'open-group': [];
  'edit-group': [group: Group];
  'delete-group': [id: number];
}>();
</script>