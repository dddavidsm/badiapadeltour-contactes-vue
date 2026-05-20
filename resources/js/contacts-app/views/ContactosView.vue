<template>
  <div class="bpt-contacts-section">
    <div class="bpt-filters">
      <input v-model.trim="searchQ" class="bpt-input" type="search" placeholder="Buscar contacto (ej: Marta)" />

      <select v-model="filterGroup" class="bpt-select">
        <option :value="''">Todos los grupos</option>
        <option v-for="group in groups" :key="group.id" :value="group.id">{{ group.name }}</option>
      </select>

      <select v-model="sortBy" class="bpt-select">
        <option value="name">Ordenar por nombre</option>
        <option value="date">Ordenar por fecha</option>
      </select>

      <button class="bpt-btn" @click="openCreate">Nuevo contacto</button>
    </div>

    <div v-if="!filteredContacts.length" class="bpt-empty">No hay contactos.</div>

    <div v-else class="bpt-list">
      <ContactoItem
        v-for="contacto in filteredContacts"
        :key="contacto.id"
        :contacto="contacto"
        :on-edit="openEdit"
        :on-delete="removeContact"
        :on-favorite="toggleFavorite"
      />
    </div>

    <dialog v-if="showModal" class="bpt-modal" open>
      <h3>{{ editingId ? 'Editar contacto' : 'Nuevo contacto' }}</h3>

      <form class="bpt-form" @submit.prevent="submitForm">
        <input v-model.trim="form.name" type="text" placeholder="Nombre (ej: Marta)" />
        <input v-model.trim="form.surname" type="text" placeholder="Apellidos (ej: Garcia Lopez)" />
        <input v-model.trim="form.phone" type="tel" placeholder="Telefono (ej: 612345678)" />
        <small v-if="phoneError" class="error">{{ phoneError }}</small>
        <input v-model.trim="form.email" type="email" placeholder="Email (ej: marta@email.com)" />

        <select v-model="form.city">
          <option value="">Ciudad (ej: Sabadell)</option>
          <option v-for="city in PADEL_CITIES" :key="city" :value="city">{{ city }}</option>
        </select>

        <select v-model="form.groupId">
          <option :value="null">Grupo</option>
          <option v-for="group in groups" :key="group.id" :value="group.id">{{ group.name }}</option>
        </select>

        <div class="bpt-form-actions">
          <button type="button" class="bpt-btn ghost" @click="closeModal">Cancelar</button>
          <button type="submit" class="bpt-btn" :disabled="isSaveDisabled">{{ editingId ? 'Guardar cambios' : 'Guardar contacto' }}</button>
        </div>
      </form>
    </dialog>
  </div>
</template>

<script setup lang="ts">
import { computed, reactive, ref } from 'vue';
import ContactoItem from '../components/ContactoItem.vue';
import { PADEL_CITIES, type Contact, type ContactFormData } from '../data/contact-types';
import { contacts, groups, saveContact, deleteContact, toggleFavorite } from '../store';

const SPANISH_PHONE_RE = /^[6789]\d{8}$/;

function cleanPhone(value: string): string {
  return value.replace(/\s+/g, '');
}

function isSpanishPhone(value: string): boolean {
  return SPANISH_PHONE_RE.test(cleanPhone(value));
}

const searchQ = ref('');
const filterGroup = ref<number | ''>('');
const sortBy = ref<'name' | 'date'>('name');
const showModal = ref(false);
const editingId = ref<number | undefined>();

const isSaveDisabled = computed(
  () => !form.name.trim() || !form.surname.trim() || !form.phone.trim() || !isSpanishPhone(form.phone) || form.groupId === null
);

const phoneError = computed(() => {
  if (!form.phone.trim()) return '';
  if (isSpanishPhone(form.phone)) return '';
  return 'Telefono no valido. Usa 9 digitos.';
});

const form = reactive<ContactFormData>({
  name: '',
  surname: '',
  phone: '',
  email: '',
  city: '',
  groupId: null,
});

const filteredContacts = computed(() => {
  const q = searchQ.value.toLowerCase();

  const list = contacts.value.filter((c) => {
    const matchQ =
      c.name.toLowerCase().includes(q) ||
      c.surname.toLowerCase().includes(q) ||
      c.phone.includes(q);
    const matchGroup = filterGroup.value === '' || c.groupId === filterGroup.value;
    return matchQ && matchGroup;
  });

  if (sortBy.value === 'name') {
    return [...list].sort((a, b) => a.name.localeCompare(b.name));
  }

  return [...list].sort((a, b) => b.createdAt.localeCompare(a.createdAt));
});

function openCreate() {
  editingId.value = undefined;
  form.name = '';
  form.surname = '';
  form.phone = '';
  form.email = '';
  form.city = '';
  form.groupId = groups.value[0]?.id ?? null;
  showModal.value = true;
}

function openEdit(contact: Contact) {
  editingId.value = contact.id;
  form.name = contact.name;
  form.surname = contact.surname;
  form.phone = contact.phone;
  form.email = contact.email;
  form.city = contact.city;
  form.groupId = contact.groupId;
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
}

function submitForm() {
  if (isSaveDisabled.value) {
    return;
  }

  saveContact(
    {
      name: form.name,
      surname: form.surname,
      phone: cleanPhone(form.phone),
      email: form.email,
      city: form.city,
      groupId: form.groupId as number,
    },
    editingId.value
  );

  showModal.value = false;
}

function removeContact(id: number) {
  deleteContact(id);
}
</script>
