<template>
  <div class="bpt-groups">
    <div class="bpt-groups-actions">
      <button class="bpt-btn" @click="openCreate">Nuevo grupo</button>
    </div>

    <p v-if="errorMessage" class="bpt-empty">{{ errorMessage }}</p>
    <p v-else-if="loading" class="bpt-empty">Cargando grupos...</p>
    <p v-else-if="!groupsWithCount.length" class="bpt-empty">No hay grupos.</p>

    <div v-else class="bpt-groups-list">
      <GrupoItem
        v-for="group in groupsWithCount"
        :key="group.id"
        :grupo="group"
        :on-edit="openEdit"
        :on-delete="removeGroup"
      />
    </div>

    <dialog v-if="showModal" class="bpt-modal" open>
      <h3>{{ editingId ? 'Editar grupo' : 'Nuevo grupo' }}</h3>

      <form class="bpt-form" @submit.prevent="submitForm">
        <input v-model.trim="form.name" type="text" placeholder="Nombre del grupo" />
        <input v-model="form.color" type="color" />

        <div class="bpt-form-actions">
          <button type="button" class="bpt-btn ghost" @click="closeModal">Cancelar</button>
          <button type="submit" class="bpt-btn">Guardar</button>
        </div>
      </form>
    </dialog>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue';
import GrupoItem from '../components/GrupoItem.vue';
import type { Contacto, Grupo, GrupoFormData } from '../data/contact-types';
import { GrupoService } from '../services/GrupoService';
import { ContactService } from '../services/ContactService';

const grupos = ref<Grupo[]>([]);
const contactos = ref<Contacto[]>([]);
const loading = ref(false);
const errorMessage = ref('');

const showModal = ref(false);
const editingId = ref<number | null>(null);

const form = reactive<GrupoFormData>({
  name: '',
  color: '#c9ff00',
});

const groupsWithCount = computed(() =>
  grupos.value.map((grupo) => ({
    ...grupo,
    total: contactos.value.filter((contacto) => contacto.groupId === grupo.id).length,
  }))
);

async function loadData() {
  loading.value = true;
  errorMessage.value = '';

  try {
    const [loadedContactos, loadedGrupos] = await Promise.all([
      ContactService.getContacts(),
      GrupoService.getGroups(),
    ]);
    grupos.value = loadedGrupos;
    contactos.value = loadedContactos;
  } catch {
    errorMessage.value = 'No se pudieron cargar los grupos.';
  } finally {
    loading.value = false;
  }
}

function openCreate() {
  editingId.value = null;
  form.name = '';
  form.color = '#c9ff00';
  showModal.value = true;
}

function openEdit(grupo: Grupo) {
  editingId.value = grupo.id;
  form.name = grupo.name;
  form.color = grupo.color;
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
}

async function submitForm() {
  if (!form.name) {
    return;
  }

  try {
    const payload = {
      name: form.name,
      color: form.color,
    };

    if (editingId.value === null) {
      await GrupoService.createGroup(payload);
    } else {
      await GrupoService.updateGroup(editingId.value, payload);
    }
    await loadData();
    closeModal();
  } catch {
    errorMessage.value = 'No se pudo guardar el grupo.';
  }
}

async function removeGroup(id: number) {
  const confirmed = window.confirm('¿Seguro que quieres borrar este grupo?');

  if (!confirmed) return;

  try {
    await GrupoService.deleteGroup(id);
    await loadData();
  } catch {
    errorMessage.value = 'No se pudo eliminar el grupo.';
  }
}

onMounted(async () => {
  await loadData();
});
</script>
