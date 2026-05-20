<template>
  <div class="bpt-contacts-section">
    <div class="bpt-filters">
      <input v-model.trim="searchQ" class="bpt-input" type="search" placeholder="Buscar contacto" />

      <select v-model.number="filterGroup" class="bpt-select">
        <option :value="0">Todos los grupos</option>
        <option v-for="group in grupos" :key="group.id" :value="group.id">{{ group.name }}</option>
      </select>

      <button class="bpt-btn" @click="openCreate">Nuevo contacto</button>
    </div>

    <p v-if="errorMessage" class="bpt-empty">{{ errorMessage }}</p>
    <p v-else-if="loading" class="bpt-empty">Cargando contactos...</p>
    <p v-else-if="!filteredContacts.length" class="bpt-empty">No hay contactos.</p>

    <div v-else class="bpt-list">
      <ContactoItem
        v-for="contacto in filteredContacts"
        :key="contacto.id"
        :contacto="contacto"
        :group-name="groupName(contacto.groupId)"
        :group-color="groupColor(contacto.groupId)"
        :on-edit="openEdit"
        :on-delete="removeContact"
      />
    </div>

    <dialog v-if="showModal" class="bpt-modal" open>
      <h3>{{ editingId ? 'Editar contacto' : 'Nuevo contacto' }}</h3>

      <form class="bpt-form" @submit.prevent="submitForm">
        <input v-model.trim="form.name" type="text" placeholder="Nombre" />
        <input v-model.trim="form.surname" type="text" placeholder="Apellidos" />
        <input v-model.trim="form.phone" type="tel" placeholder="Telefono" />
        <input v-model.trim="form.email" type="email" placeholder="Email" />
        <input v-model.trim="form.city" type="text" placeholder="Ciudad" />

        <select v-model.number="form.groupId">
          <option :value="0">Selecciona grupo</option>
          <option v-for="group in grupos" :key="group.id" :value="group.id">{{ group.name }}</option>
        </select>

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
import ContactoItem from '../components/ContactoItem.vue';
import type { Contacto, ContactoFormData, Grupo } from '../data/contact-types';
import { ContactoService } from '../services/ContactoService';

const contactos = ref<Contacto[]>([]);
const grupos = ref<Grupo[]>([]);
const loading = ref(false);
const errorMessage = ref('');

const showModal = ref(false);
const editingId = ref<number | null>(null);

const searchQ = ref('');
const filterGroup = ref(0);

const form = reactive<ContactoFormData>({
  name: '',
  surname: '',
  phone: '',
  email: '',
  city: '',
  groupId: null,
});

const filteredContacts = computed(() => {
  const query = searchQ.value.toLowerCase();
  return contactos.value.filter((contacto) => {
    const matchesText =
      contacto.name.toLowerCase().includes(query) ||
      contacto.surname.toLowerCase().includes(query) ||
      contacto.phone.includes(query);
    const matchesGroup = filterGroup.value === 0 || contacto.groupId === filterGroup.value;
    return matchesText && matchesGroup;
  });
});

function resetForm() {
  form.name = '';
  form.surname = '';
  form.phone = '';
  form.email = '';
  form.city = '';
  form.groupId = grupos.value[0]?.id ?? null;
}

async function loadData() {
  loading.value = true;
  errorMessage.value = '';

  try {
    const data = await ContactoService.getContactosViewData();
    contactos.value = data.contactos;
    grupos.value = data.grupos;
  } catch {
    errorMessage.value = 'No se pudieron cargar los datos.';
  } finally {
    loading.value = false;
  }
}

function openCreate() {
  editingId.value = null;
  resetForm();
  showModal.value = true;
}

function openEdit(contacto: Contacto) {
  editingId.value = contacto.id;
  form.name = contacto.name;
  form.surname = contacto.surname;
  form.phone = contacto.phone;
  form.email = contacto.email;
  form.city = contacto.city;
  form.groupId = contacto.groupId;
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
}

async function submitForm() {
  if (!form.name || !form.surname || !form.phone || !form.email || !form.city || !form.groupId) {
    return;
  }

  const payload = {
    name: form.name,
    surname: form.surname,
    phone: form.phone,
    email: form.email,
    city: form.city,
    groupId: form.groupId,
  };

  try {
    await ContactoService.saveContacto(payload, editingId.value ?? undefined);
    await loadData();
    closeModal();
  } catch {
    errorMessage.value = 'No se pudo guardar el contacto.';
  }
}

async function removeContact(id: number) {
  try {
    await ContactoService.removeContacto(id);
    await loadData();
  } catch {
    errorMessage.value = 'No se pudo eliminar el contacto.';
  }
}

function groupName(groupId: number) {
  return grupos.value.find((grupo) => grupo.id === groupId)?.name ?? 'Sin grupo';
}

function groupColor(groupId: number) {
  return grupos.value.find((grupo) => grupo.id === groupId)?.color ?? '#555555';
}

onMounted(async () => {
  await loadData();
});
</script>
