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

    <div v-if="showModal" class="bpt-modal-overlay">
      <div class="bpt-modal-content">
        <h3>{{ editingId ? 'Editar contacto' : 'Nuevo contacto' }}</h3>
        <form class="bpt-form" @submit.prevent="submitForm">
            <div class="bpt-form-group">
              <input
                v-model.trim="form.name"
                type="text"
                placeholder="Nombre"
                :class="{ 'bpt-error': errors.name }"
              />
              <span v-if="errors.name" class="bpt-error-msg">{{ errors.name }}</span>
            </div>

            <div class="bpt-form-group">
              <input
                v-model.trim="form.surname"
                type="text"
                placeholder="Apellidos"
                :class="{ 'bpt-error': errors.surname }"
              />
              <span v-if="errors.surname" class="bpt-error-msg">{{ errors.surname }}</span>
            </div>

            <div class="bpt-form-group">
              <input
                v-model.trim="form.phone"
                type="tel"
                placeholder="Telefono (+34600000000)"
                :class="{ 'bpt-error': errors.phone }"
              />
              <span v-if="errors.phone" class="bpt-error-msg">{{ errors.phone }}</span>
            </div>

            <div class="bpt-form-group">
              <input
                v-model.trim="form.email"
                type="email"
                placeholder="Email"
                :class="{ 'bpt-error': errors.email }"
              />
              <span v-if="errors.email" class="bpt-error-msg">{{ errors.email }}</span>
            </div>

            <div class="bpt-form-group">
              <input
                v-model.trim="form.city"
                type="text"
                placeholder="Ciudad"
                :class="{ 'bpt-error': errors.city }"
              />
              <span v-if="errors.city" class="bpt-error-msg">{{ errors.city }}</span>
            </div>

            <div class="bpt-form-group">
              <select v-model.number="form.groupId" :class="{ 'bpt-error': errors.groupId }">
                <option :value="0">Selecciona grupo</option>
                <option v-for="group in grupos" :key="group.id" :value="group.id">{{ group.name }}</option>
              </select>
              <span v-if="errors.groupId" class="bpt-error-msg">{{ errors.groupId }}</span>
            </div>

            <div class="bpt-form-actions">
              <button type="button" class="bpt-btn ghost" @click="closeModal">Cancelar</button>
              <button type="submit" class="bpt-btn">Guardar</button>
            </div>
        </form>
      </div>
    </div>
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

const errors = reactive<Record<string, string>>({
  name: '',
  surname: '',
  phone: '',
  email: '',
  city: '',
  groupId: '',
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

function clearErrors() {
  Object.keys(errors).forEach((k) => (errors[k] = ''));
}

function resetForm() {
  form.name = '';
  form.surname = '';
  form.phone = '';
  form.email = '';
  form.city = '';
  form.groupId = grupos.value[0]?.id ?? null;
  clearErrors();
}

function groupName(groupId: number) {
  return grupos.value.find((grupo) => grupo.id === groupId)?.name ?? 'Sin grupo';
}

function groupColor(groupId: number) {
  return grupos.value.find((grupo) => grupo.id === groupId)?.color ?? '#555555';
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
  clearErrors();
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
  clearErrors();
}

async function submitForm() {
  clearErrors();

  const regexPhone = /^\+[1-9]\d{1,14}$/;
  const regexEmail = /^[^\s@]+@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/;
  let hasError = false;

  const duplicatedPhone = contactos.value.find(
    (contacto) => contacto.phone === form.phone && contacto.id !== editingId.value
  );

  if (!form.name?.trim()) {
    errors.name = 'El nombre es obligatorio.';
    hasError = true;
  } else if (form.name.trim().length < 2) {
    errors.name = 'El nombre debe tener minimo 2 caracteres.';
    hasError = true;
  }
  if (!form.surname?.trim()) {
    errors.surname = 'Los apellidos son obligatorios.';
    hasError = true;
  } else if (form.surname.trim().length < 2) {
    errors.surname = 'Los apellidos deben tener minimo 2 caracteres.';
    hasError = true;
  }
  if (!regexPhone.test(form.phone || '')) {
    errors.phone = 'Formato E.164 obligatorio (ejemplo: +34600000000).';
    hasError = true;
  }
  if (form.email && !regexEmail.test(form.email)) {
    errors.email = 'El formato del email no es valido.';
    hasError = true;
  }
  if (duplicatedPhone) {
    errors.phone = 'Este telefono ya esta asignado a otro contacto.';
    hasError = true;
  }
  if (!form.groupId) {
    errors.groupId = 'Debes seleccionar un grupo para el contacto.';
    hasError = true;
  }

  if (hasError) return;

  const payload = {
    name: form.name,
    surname: form.surname,
    phone: form.phone,
    email: form.email,
    city: form.city,
    groupId: form.groupId as number,
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
  const confirmed = window.confirm('¿Seguro que quieres borrar este contacto?');

  if (!confirmed) return;

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

onMounted(loadData);
</script>

<style scoped>
.bpt-modal-overlay {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(20, 20, 30, 0.7);
  backdrop-filter: blur(3px);
}

.bpt-modal-content {
  min-width: 340px;
  max-width: 95vw;
  padding: 2rem 2.5rem 1.5rem;
  border-radius: 12px;
  background: #181c24;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
}

.bpt-form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 1rem;
}

.bpt-form-group input,
.bpt-form-group select {
  margin-bottom: 0.2rem;
  padding: 0.5rem 0.75rem;
  border: 1.5px solid #23283a;
  border-radius: 6px;
  outline: none;
  background: #23283a;
  color: #fff;
  font-size: 1rem;
  transition: border 0.2s;
}

.bpt-form-group input.bpt-error,
.bpt-form-group select.bpt-error {
  border-color: #ff3b3b;
}

.bpt-error-msg {
  min-height: 1.2em;
  margin-top: -0.2em;
  margin-bottom: 0.2em;
  color: #ff3b3b;
  font-size: 0.92em;
}

.bpt-form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1.5rem;
}
</style>
