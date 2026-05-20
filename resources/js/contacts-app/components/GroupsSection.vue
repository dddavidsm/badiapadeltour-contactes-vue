<template>
  <div class="bpt-groups">
    <div class="bpt-groups-actions">
      <!-- Ejecuta directamente la función del padre. Sin emit. -->
      <button class="bpt-btn" @click="onOpenGroup()">Nuevo grupo</button>
    </div>

    <div class="bpt-groups-list">
      <div v-for="group in groups" :key="group.id" class="bpt-group-card">
        <div class="bpt-group-color" :style="{ background: group.color }"></div>

        <div class="bpt-group-meta">
          <strong>{{ group.name }}</strong>
          <span>{{ contactsPerGroup[group.id] ?? 0 }} contactos</span>
        </div>

        <div class="bpt-group-actions">
          <button class="bpt-btn ghost" @click="onEditGroup(group)">Editar</button>
          <button class="bpt-btn danger" @click="onDeleteGroup(group.id)">Eliminar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Group } from '../data/contact-types';

// PROPS: Datos de visualización + funciones del padre para cada acción.
// Sin defineEmits: el hijo ejecuta directamente las funciones que recibe, sin notificar hacia arriba.
defineProps<{
  groups: Group[]; // Lista completa con todos los grupos de la app
  contactsPerGroup: Record<number, number>; // Diccionario con los contadores de jugadores por grupo
  // Función del padre: abre el formulario de creación
  onOpenGroup: () => void;
  // Función del padre: abre el formulario con el grupo a editar
  onEditGroup: (group: Group) => void;
  // Función del padre: valida las reglas de borrado y elimina si procede
  onDeleteGroup: (id: number) => void;
}>(); 
</script>