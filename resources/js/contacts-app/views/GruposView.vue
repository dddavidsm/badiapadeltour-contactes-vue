<template>
  <div class="bpt-groups">
    <div class="bpt-groups-actions">
      <button class="bpt-btn" @click="openCreate">Nuevo grupo</button>
    </div>

    <div v-if="!groupsWithCount.length" class="bpt-empty">No hay grupos.</div>

    <div v-else class="bpt-groups-list">
      <GrupoItem
        v-for="grupo in groupsWithCount"
        :key="grupo.id"
        :grupo="grupo"
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
import { computed, reactive, ref } from 'vue';
import GrupoItem from '../components/GrupoItem.vue';
import { type Group, type GroupFormData } from '../data/contact-types';
import { contactsPerGroup, groups, saveGroup, deleteGroup } from '../store';

const showModal = ref(false);
const editingId = ref<number | undefined>();

const form = reactive<GroupFormData>({
  name: '',
  color: '#c9ff00',
});

const groupsWithCount = computed(() =>
  groups.value.map((g) => ({
    ...g,
    total: contactsPerGroup.value[g.id] ?? 0,
  }))
);

function openCreate() {
  editingId.value = undefined;
  form.name = '';
  form.color = '#c9ff00';
  showModal.value = true;
}

function openEdit(group: Group) {
  editingId.value = group.id;
  form.name = group.name;
  form.color = group.color;
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
}

function submitForm() {
  if (!form.name) return;

  saveGroup(
    {
      name: form.name,
      color: form.color,
    },
    editingId.value
  );

  showModal.value = false;
}

function removeGroup(id: number) {
  deleteGroup(id);
}
</script>
