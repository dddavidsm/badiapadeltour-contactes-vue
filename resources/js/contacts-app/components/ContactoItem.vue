<template>
  <div class="bpt-card">
    <div class="bpt-avatar" :style="{ background: groupColor }">
      {{ initials }}
    </div>

    <div class="bpt-meta">
      <strong>{{ contacto.name }} {{ contacto.surname }}</strong>
      <span>{{ contacto.phone }}</span>
      <span v-if="contacto.email">{{ contacto.email }}</span>
      <span v-if="contacto.city">{{ contacto.city }}</span>
      <span class="bpt-chip" :style="{ background: `${groupColor}33`, color: groupColor }">
        {{ groupName }}
      </span>
    </div>

    <div class="bpt-actions">
      <button class="bpt-btn ghost" @click="onEdit(contacto)">Editar</button>
      <button class="bpt-btn danger" @click="onDelete(contacto.id)">Eliminar</button>
      <button class="bpt-btn icon" @click="onFavorite(contacto.id)">{{ contacto.favorite ? '★' : '☆' }}</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { Contact } from '../data/contact-types';
import { groups } from '../store';

const props = defineProps<{
  contacto: Contact;
  onEdit: (contact: Contact) => void;
  onDelete: (id: number) => void;
  onFavorite: (id: number) => void;
}>();

const group = computed(() => groups.value.find((g) => g.id === props.contacto.groupId));
const groupName = computed(() => group.value?.name ?? 'Sin grupo');
const groupColor = computed(() => group.value?.color ?? '#555');
const initials = computed(() => `${props.contacto.name[0] ?? ''}${props.contacto.surname[0] ?? ''}`.toUpperCase());
</script>
