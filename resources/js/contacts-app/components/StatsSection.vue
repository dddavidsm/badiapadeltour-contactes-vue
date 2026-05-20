<template>
  <div class="bpt-stats">
    <div class="bpt-stat-cards">
      <div class="bpt-stat-card">
        <span class="bpt-stat-value">{{ contactsCount }}</span>
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
          <span 
            class="bpt-bar-fill"
            :style="{ width: barWidth(contactsPerGroup[group.id] ?? 0), background: group.color }"
          ></span>
        </span>
        
        <span class="bpt-bar-count">{{ contactsPerGroup[group.id] ?? 0 }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { Group } from '../data/contact-types';

// PROPS: Datos estadísticos calculados previamente por el componente raíz (Padre)
const props = defineProps<{
  contactsCount: number;
  recentContactsCount: number;
  groups: Group[];
  // Diccionario tipo { [groupId]: cantidad } (ej: { 1: 5, 2: 12 })
  contactsPerGroup: Record<number, number>;
}>();

// COMPUTED: Encuentra cuál es el número máximo de contactos asignados a un solo grupo.
// Se usa como el límite "100%" para escalar de forma proporcional el ancho de todas las barras.
const maxContacts = computed(() => 
  // Object.values extrae los números del diccionario, el operador spread (...) los esparce como argumentos,
  // y Math.max encuentra el mayor. Si todos los grupos están vacíos, usa 1 por defecto para evitar dividir por 0.
  Math.max(...Object.values(props.contactsPerGroup), 1)
);

/**
 * FUNCIÓN DE CÁLCULO VISUAL:
 * Recibe la cantidad de contactos de un grupo y devuelve el string del porcentaje exacto
 * para inyectarlo directamente en el atributo CSS 'width' de la barra.
 */
function barWidth(count: number): string {
  // Regla de tres: (contactos del grupo / contactos del grupo más numeroso) * 100
  return `${Math.round((count / maxContacts.value) * 100)}%`;
}
</script>