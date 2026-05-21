<template>
  <div class="bpt-stats">
    
    <div class="bpt-stat-cards">
      <div class="bpt-stat-card">
        <span class="bpt-stat-value">{{ contacts.length }}</span>
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
          <span class="bpt-bar-fill" :style="{ width: barWidth(group.id), background: group.color }"></span>
        </span>
        
        <span class="bpt-bar-count">{{ contactsPerGroup[group.id] ?? 0 }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import type { Contacto, Grupo } from '../data/contact-types';
import { ContactService } from '../services/ContactService';
import { GrupoService } from '../services/GrupoService';

// Variables reactivas locales para almacenar lo que nos devuelva la API
const contacts = ref<Contacto[]>([]);
const groups = ref<Grupo[]>([]);

/**
 * COMPUTED: contactsPerGroup
 * Transforma la lista plana de contactos en un objeto mapa de recuentos.
 * Devuelve algo como esto: { 1: 5, 2: 0, 3: 12 } (donde la clave es el groupId y el valor es cuántos hay).
 */
const contactsPerGroup = computed(() => {
  const map: Record<number, number> = {};
  
  // Paso 1: Inicializamos todos los grupos a 0 para que existan aunque estén vacíos
  for (const group of groups.value) {
    map[group.id] = 0;
  }
  
  // Paso 2: Recorremos los contactos uno a uno e incrementamos el contador de su grupo
  for (const contact of contacts.value) {
    map[contact.groupId] = (map[contact.groupId] ?? 0) + 1;
  }
  
  return map;
});

/**
 * COMPUTED: recentContactsCount
 * Filtra el array de contactos para contar cuántos se crearon hace 7 días o menos.
 */
const recentContactsCount = computed(() => {
  // Calculamos la marca de tiempo (timestamp) exacta de hace justo una semana (7 días en milisegundos)
  const lastWeek = Date.now() - 7 * 24 * 60 * 60 * 1000;
  
  return contacts.value.filter((contact) => {
    if (!contact.createdAt) return false; // Si no tiene fecha, lo descartamos
    // Pasamos la fecha string ISO a milisegundos y comparamos si es mayor o igual a "hace una semana"
    return new Date(contact.createdAt).getTime() >= lastWeek;
  }).length; // El .length final nos da el número total de los que han cumplido la condición
});

/**
 * COMPUTED: maxCount
 * Encuentra cuál es el grupo que más contactos tiene. 
 * Se usa como "el 100%" para escalar las barras de forma proporcional.
 * El ", 1" final asegura que si no hay contactos, no se divida por 0 en la fórmula matemática.
 */
const maxCount = computed(() => Math.max(...Object.values(contactsPerGroup.value), 1));

/**
 * FUNCIÓN: barWidth
 * Recibe un identificador de grupo y calcula qué porcentaje de ancho (width) debe tener su barra.
 * No lo hace sobre el total general, sino respecto al grupo que más contactos tiene (diseño visual proporcional).
 */
function barWidth(groupId: number) {
  const count = contactsPerGroup.value[groupId] ?? 0; // Cuántos tiene este grupo
  
  // Regla de tres: (Contactos del grupo actual / Contactos del grupo más lleno) * 100
  // Math.round elimina los decimales para que el CSS sea limpio (ej. "67%")
  return `${Math.round((count / maxCount.value) * 100)}%`;
}

/**
 * CICLO DE VIDA: onMounted
 * Al cargar la pestaña de estadísticas, va a buscar los datos frescos a la Fake API (json-server).
 */
onMounted(async () => {
  // Orquestamos la llamada atómica de ambos servicios en paralelo con Promise.all
  const [loadedContactos, loadedGrupos] = await Promise.all([
    ContactService.getContacts(),
    GrupoService.getGroups(),
  ]);
  
  // Almacenamos el resultado en las variables reactivas. 
  // Al hacer esto, todos los computed de arriba se recalculan solos automáticamente.
  contacts.value = loadedContactos;
  groups.value = loadedGrupos;
});
</script>