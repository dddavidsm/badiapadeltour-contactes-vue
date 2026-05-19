<template>
  <div class="bpt-section">
    <div class="bpt-filters">
      <input
        :value="searchQ"
        type="search"
        placeholder="🔍 Cerca per nom, cognom…"
        class="bpt-input"
        @input="$emit('update:searchQ', ($event.target as HTMLInputElement).value)"
      />
      <select
        :value="filterGroup"
        class="bpt-input"
        @change="$emit('update:filterGroup', parseFilterValue(($event.target as HTMLSelectElement).value))"
      >
        <option value="">Tots els grups</option>
        <option v-for="g in groups" :key="g.id" :value="g.id">{{ g.name }}</option>
      </select>
      <select
        :value="sortBy"
        class="bpt-input"
        @change="$emit('update:sortBy', ($event.target as HTMLSelectElement).value as 'name' | 'date')"
      >
        <option value="name">Ordenar per nom</option>
        <option value="date">Ordenar per data</option>
      </select>
      <button class="bpt-btn bpt-btn-primary" @click="$emit('open-contact')">+ Nou contacte</button>
    </div>

    <div v-if="loading" class="bpt-state">Carregant…</div>
    <div v-else-if="!contacts.length" class="bpt-state">Cap contacte trobat.</div>
    <div v-else class="bpt-grid">
      <div v-for="contact in contacts" :key="contact.id" class="bpt-card bpt-contact-card">
        <div class="bpt-avatar" :style="{ background: groupColorOf(contact.groupId) }">
          {{ initials(contact.name, contact.surname) }}
        </div>
        <div class="bpt-contact-info">
          <strong>{{ contact.name }} {{ contact.surname }}</strong>
          <span>📞 {{ contact.phone }}</span>
          <span>✉️ {{ contact.email }}</span>
          <span>📍 {{ contact.city }}</span>
          <span
            class="bpt-group-badge"
            :style="{ background: `${groupColorOf(contact.groupId)}33`, color: groupColorOf(contact.groupId) }"
          >
            {{ groupNameOf(contact.groupId) }}
          </span>
        </div>
        <div class="bpt-card-actions">
          <button class="bpt-icon-btn" title="Editar" @click="$emit('edit-contact', contact)">✏️</button>
          <button class="bpt-icon-btn danger" title="Eliminar" @click="$emit('delete-contact', contact.id)">🗑️</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Contact, Group } from '../data/contact-types';

// Stateless UI section: receives data via props and emits user actions to parent.
defineProps<{
  loading: boolean;
  contacts: Contact[];
  groups: Group[];
  searchQ: string;
  filterGroup: number | '';
  sortBy: 'name' | 'date';
  initials: (name: string, surname: string) => string;
  groupColorOf: (groupId: number) => string;
  groupNameOf: (groupId: number) => string;
}>();

defineEmits<{
  'update:searchQ': [value: string];
  'update:filterGroup': [value: number | ''];
  'update:sortBy': [value: 'name' | 'date'];
  'open-contact': [];
  'edit-contact': [contact: Contact];
  'delete-contact': [id: number];
}>();

function parseFilterValue(value: string): number | '' {
  return value === '' ? '' : Number(value);
}
</script>