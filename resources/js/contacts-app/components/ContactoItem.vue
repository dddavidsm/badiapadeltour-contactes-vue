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
      <button class="bpt-btn ghost" @click="onFavorite(contacto)">
        {{ contacto.favorite ? 'Quitar favorito' : 'Favorito' }}
      </button>
      <button class="bpt-btn ghost" @click="onEdit(contacto)">Editar</button>
      <button class="bpt-btn danger" @click="onDelete(contacto.id)">Eliminar</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { Contacto } from '../data/contact-types';

const props = defineProps<{
  contacto: Contacto;
  groupName: string;
  groupColor: string;
  onFavorite: (contacto: Contacto) => void;
  onEdit: (contacto: Contacto) => void;
  onDelete: (id: number) => void;
}>();

const initials = computed(() => `${props.contacto.name[0] ?? ''}${props.contacto.surname[0] ?? ''}`.toUpperCase());
</script>
