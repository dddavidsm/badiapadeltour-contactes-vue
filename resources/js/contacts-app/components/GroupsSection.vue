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

// PROPS: Datos de entrada inyectados por el componente padre
defineProps<{
  groups: Group[]; // Lista completa con todos los grupos de la app
  contactsPerGroup: Record<number, number>; // Diccionario con los contadores de jugadores por grupo
}>();

// EMITS: Eventos que este componente puede disparar hacia el exterior con su tipado correspondiente
defineEmits<{
  'open-group': []; // Evento simple sin argumentos para abrir creación
  'edit-group': [group: Group]; // Envía el objeto de grupo seleccionado como argumento
  'delete-group': [id: number]; // Envía el identificador numérico único del grupo a eliminar
}>();
</script>