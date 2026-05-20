<template>
  <div class="bpt-group-card">
    <div class="bpt-group-color" :style="{ background: grupo.color }"></div>

    <div class="bpt-group-meta">
      <strong>{{ grupo.name }}</strong>
      <span>{{ grupo.total }} contactos</span>
    </div>

    <div class="bpt-group-actions">
      <button class="bpt-btn ghost" @click="onEdit(grupo)">Editar</button>
      <button class="bpt-btn danger" @click="onDelete(grupo.id)">Eliminar</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Group } from '../data/contact-types';

defineProps<{
  grupo: Group & { total: number };
  onEdit: (group: Group) => void;
  onDelete: (id: number) => void;
}>();
</script>
