<template>
  <div class="bpt-contacts-section">
    <div class="bpt-filters">
      <!-- Los inputs llaman directamente a las funciones actualizadoras recibidas por prop.
           Sin emit: el hijo informa al padre ejecutando la función en lugar de emitir un evento. -->
      <input
        class="bpt-input"
        :value="searchQ"
        type="search"
        placeholder="Buscar por nombre, apellido o telefono"
        @input="onUpdateSearchQ(($event.target as HTMLInputElement).value)"
      />

      <!-- filterGroupStr convierte number|'' <-> string localmente para que el select sea válido -->
      <select class="bpt-select" :value="filterGroupStr" @change="filterGroupStr = ($event.target as HTMLSelectElement).value">
        <option value="">Todos los grupos</option>
        <option v-for="g in groups" :key="g.id" :value="g.id">{{ g.name }}</option>
      </select>

      <select class="bpt-select" :value="sortBy" @change="onUpdateSortBy(($event.target as HTMLSelectElement).value as 'name' | 'date')">
        <option value="name">Ordenar por nombre</option>
        <option value="date">Ordenar por fecha</option>
      </select>

      <button class="bpt-btn" @click="onOpenContact()">Nuevo contacto</button>
    </div>

    <div v-if="loading" class="bpt-empty">Cargando...</div>
    <div v-else-if="!contacts.length" class="bpt-empty">No se han encontrado contactos.</div>
    <div v-else class="bpt-list">
      <div v-for="contact in contacts" :key="contact.id" class="bpt-card">
        <div class="bpt-avatar" :style="{ background: groupColorOf(contact.groupId) }">
          {{ initials(contact.name, contact.surname) }}
        </div>

        <div class="bpt-meta">
          <strong>{{ contact.name }} {{ contact.surname }}</strong>
          <span>{{ contact.phone }}</span>
          <span v-if="contact.email">{{ contact.email }}</span>
          <span v-if="contact.city">{{ contact.city }}</span>
          <span class="bpt-chip" :style="{ background: `${groupColorOf(contact.groupId)}33`, color: groupColorOf(contact.groupId) }">
            {{ groupNameOf(contact.groupId) }}
          </span>
        </div>

        <div class="bpt-actions">
          <!-- Ejecuta directamente la función del padre sin ningún emit intermedio -->
          <button class="bpt-btn ghost" @click="onEditContact(contact)">Editar</button>
          <button class="bpt-btn danger" @click="onDeleteContact(contact.id)">Eliminar</button>
            <!-- Favorito: alterna ★/☆ sin abrir ninguna vista nueva -->
            <!-- Llamada: registra la entrada en el historial al instante -->
            <button class="bpt-btn icon" @click="toggleFavoriteAction(contact.id)">{{ contact.favorite ? '★' : '☆' }}</button>
            <button class="bpt-btn" @click="logCallAction(contact)">Llamar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { Contact, Group } from '../data/contact-types';

// PROPS: Recibe datos de visualización + funciones controladoras del padre.
// Sin defineEmits: el hijo actúa como ejecutor directo, no como emisor de eventos.
const props = defineProps<{
  loading: boolean;
  contacts: Contact[];
  groups: Group[];
  // Valores actuales de los filtros (solo lectura desde el hijo)
  searchQ: string;
  filterGroup: number | '';
  sortBy: 'name' | 'date';
  // Helpers de presentación inyectados desde el padre
  initials: (name: string, surname: string) => string;
  groupColorOf: (groupId: number) => string;
  groupNameOf: (groupId: number) => string;
  // Funciones actualizadoras de filtros (sustituyen a v-model/emit update:*)
  onUpdateSearchQ: (value: string) => void;
  onUpdateFilterGroup: (value: number | '') => void;
  onUpdateSortBy: (value: 'name' | 'date') => void;
  // Funciones de acciones CRUD (sustituyen a los emits de negocio)
  onOpenContact: () => void;
  onEditContact: (contact: Contact) => void;
  onDeleteContact: (id: number) => void;
  // Favoritos e historial: funciones del padre pasadas como prop
  toggleFavoriteAction: (id: number) => void;
  logCallAction: (contact: Contact) => void;
}>();

// COMPUTED PUENTE: El <select> HTML solo maneja strings.
// Convierte number|'' <-> string y llama a la función del padre al modificarse.
const filterGroupStr = computed<string>({
  get: () => (props.filterGroup === '' ? '' : String(props.filterGroup)),
  set: (value: string) => props.onUpdateFilterGroup(value === '' ? '' : Number(value)),
});
</script>