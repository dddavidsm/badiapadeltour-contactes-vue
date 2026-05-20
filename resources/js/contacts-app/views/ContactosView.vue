<template>
  <div class="bpt-contacts-section">
    <div class="bpt-filters">
      <input v-model.trim="searchQ" class="bpt-input" type="search" placeholder="Buscar contacto" />

      <select v-model.number="filterGroup" class="bpt-select">
        <option :value="0">Todos los grupos</option>
        <option v-for="group in grupos" :key="group.id" :value="group.id">{{ group.name }}</option>
      </select>

      <select v-model="orderBy" class="bpt-select">
        <option value="name">Ordenar alfabeticamente</option>
        <option value="date">Ordenar por mas recientes</option>
      </select>

      <button class="bpt-btn" @click="openCreate">Nuevo contacto</button>
    </div>

    <p v-if="message" class="bpt-empty">{{ message }}</p>

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
        :on-favorite="toggleFavorite"
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
import { ContactService } from '../services/ContactService';
import { GrupoService } from '../services/GrupoService';

const contactos = ref<Contacto[]>([]);
const grupos = ref<Grupo[]>([]);
const loading = ref(false);
const errorMessage = ref('');

const showModal = ref(false);
const editingId = ref<number | null>(null);
const message = ref('');

const searchQ = ref('');
const filterGroup = ref(0);
const orderBy = ref<'name' | 'date'>('name');

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
  let result = contactos.value.filter((contacto) => {
    const matchesName = contacto.name.toLowerCase().startsWith(query);
    const hasSurname = !!contacto.surname;
    const matchesSurname = hasSurname && contacto.surname.toLowerCase().startsWith(query);
    const matchesGroup = filterGroup.value === 0 || contacto.groupId === filterGroup.value;
    return (matchesName || matchesSurname) && matchesGroup;
  });

  if (orderBy.value === 'name') {
    result = result.sort((a, b) => a.name.localeCompare(b.name));
  } else {
    result = result.sort((a, b) => {
      const dateA = a.createdAt ? new Date(a.createdAt).getTime() : 0;
      const dateB = b.createdAt ? new Date(b.createdAt).getTime() : 0;
      return dateB - dateA;
    });
  }

  return result;
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
    const [loadedContactos, loadedGrupos] = await Promise.all([
      ContactService.getContacts(),
      GrupoService.getGroups(),
    ]);
    contactos.value = loadedContactos;
    grupos.value = loadedGrupos;
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
  const regexPhone = /^\+[1-9]\d{1,14}$/;
  const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const duplicatedPhone = contactos.value.find(
    (contacto) => contacto.phone === form.phone && contacto.id !== editingId.value
  );

  if (!form.name?.trim()) return showAlert('El nombre es obligatorio.', true);
  if (form.surname && form.surname.trim().length < 2) return showAlert('Si indicas apellidos, minimo 2 caracteres.', true);
  if (!regexPhone.test(form.phone || '')) return showAlert('El telefono debe tener formato internacional E.164 (ejemplo: +34600000000).', true);
  if (form.email && !regexEmail.test(form.email)) return showAlert('El formato del email no es valido.', true);
  if (duplicatedPhone) return showAlert('Este telefono ya esta asignado a otro contacto.', true);
  if (!form.groupId) return showAlert('Debes seleccionar un grupo para el contacto.', true);

  const payload = {
    name: form.name,
    surname: form.surname,
    phone: form.phone,
    email: form.email,
    city: form.city,
    groupId: form.groupId,
  };

  try {
    if (editingId.value === null) {
      await ContactService.createContact(payload);
      showAlert('Contacto creado.');
    } else {
      await ContactService.updateContact(editingId.value, payload);
      showAlert('Contacto actualizado.');
    }
    await loadData();
    closeModal();
  } catch {
    showAlert('Error al guardar el contacto.', true);
  }
}

async function removeContact(id: number) {
  try {
    await ContactService.deleteContact(id);
    showAlert('Contacto eliminado.', true);
    await loadData();
  } catch {
    showAlert('Error al eliminar el contacto.', true);
  }
}

async function toggleFavorite(contacto: Contacto) {
  try {
    await ContactService.updateContact(contacto.id, {
      name: contacto.name,
      surname: contacto.surname,
      phone: contacto.phone,
      email: contacto.email,
      city: contacto.city,
      groupId: contacto.groupId,
      favorite: !contacto.favorite,
    });
    await loadData();
  } catch {
    showAlert('No se pudo actualizar favorito.', true);
  }
}

function showAlert(text: string, isError = false) {
  message.value = text;
  if (isError) {
    errorMessage.value = text;
  }
  setTimeout(() => {
    message.value = '';
    errorMessage.value = '';
  }, 3000);
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
