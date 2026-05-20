<template>
  <div class="bpt-app">
    <ContactsTopbar
      :active-tab="activeTab"
      :tabs="tabs"
      :on-change-tab="(id) => activeTab = id as TabId"
    />

    <ContactsListSection
      v-if="activeTab === 'contacts'"
      :search-q="searchQ"
      :filter-group="filterGroup"
      :sort-by="sortBy"
      :on-update-search-q="(v) => searchQ = v"
      :on-update-filter-group="(v) => filterGroup = v"
      :on-update-sort-by="(v) => sortBy = v"
      :loading="loading"
      :contacts="filteredContacts"
      :groups="groups"
      :initials="initials"
      :group-color-of="groupColorOf"
      :group-name-of="groupNameOf"
      :on-open-contact="() => openContactForm(null)"
      :on-edit-contact="openContactForm"
      :on-delete-contact="deleteContact"
      :toggle-favorite-action="toggleFavorite"
      :log-call-action="logCall"
    />

    <GroupsSection
      v-else-if="activeTab === 'groups'"
      :groups="groups"
      :contacts-per-group="contactsPerGroup"
      :on-open-group="() => openGroupForm(null)"
      :on-edit-group="openGroupForm"
      :on-delete-group="deleteGroup"
    />

    <StatsSection
      v-else-if="activeTab === 'stats'"
      :contacts-count="contacts.length"
      :recent-contacts-count="recentContacts.length"
      :groups="groups"
      :contacts-per-group="contactsPerGroup"
    />

    <div v-else class="bpt-history">
      <p v-if="!callHistory.length" class="bpt-empty">Sin llamadas registradas.</p>
      <div v-for="call in sortedCallHistory" :key="call.id" class="bpt-history-item">
        <strong>{{ call.contactName }}</strong>
        <span>{{ new Date(call.date).toLocaleString('es-ES') }}</span>
      </div>
      <button v-if="callHistory.length" class="bpt-btn danger" @click="clearCallHistory">Limpiar historial</button>
    </div>

    <dialog v-if="showContactModal" class="bpt-modal" open>
      <h3>{{ editingContactId !== null ? 'Editar contacto' : 'Nuevo contacto' }}</h3>
      <form class="bpt-form" @submit.prevent="submitContactForm">
        <input v-model.trim="cForm.name" type="text" placeholder="Nombre" />
        <small v-if="cErrors.name" class="error">{{ cErrors.name }}</small>

        <input v-model.trim="cForm.surname" type="text" placeholder="Apellidos" />
        <small v-if="cErrors.surname" class="error">{{ cErrors.surname }}</small>

        <input v-model.trim="cForm.phone" type="tel" placeholder="Teléfono" />
        <small v-if="cErrors.phone" class="error">{{ cErrors.phone }}</small>

        <input v-model.trim="cForm.email" type="email" placeholder="Email (opcional)" />
        <small v-if="cErrors.email" class="error">{{ cErrors.email }}</small>

        <select v-model="cForm.groupId">
          <option :value="null">Selecciona grupo</option>
          <option v-for="group in groups" :key="group.id" :value="group.id">{{ group.name }}</option>
        </select>
        <small v-if="cErrors.groupId" class="error">{{ cErrors.groupId }}</small>

        <select v-model="cForm.city">
          <option value="">Selecciona ciudad</option>
          <option v-for="city in PADEL_CITIES" :key="city" :value="city">{{ city }}</option>
        </select>

        <div class="bpt-form-actions">
          <button type="button" class="bpt-btn ghost" @click="showContactModal = false">Cancelar</button>
          <button type="submit" class="bpt-btn" :disabled="!isContactFormValid">Guardar</button>
        </div>
      </form>
    </dialog>

    <dialog v-if="showGroupModal" class="bpt-modal" open>
      <h3>{{ editingGroupId !== null ? 'Editar grupo' : 'Nuevo grupo' }}</h3>
      <form class="bpt-form" @submit.prevent="submitGroupForm">
        <input v-model.trim="gForm.name" type="text" placeholder="Nombre del grupo" />
        <small v-if="gErrors.name" class="error">{{ gErrors.name }}</small>

        <input v-model="gForm.color" type="color" />

        <div class="bpt-form-actions">
          <button type="button" class="bpt-btn ghost" @click="showGroupModal = false">Cancelar</button>
          <button type="submit" class="bpt-btn">Guardar</button>
        </div>
      </form>
    </dialog>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue';
import ContactsListSection from './components/ContactsListSection.vue';
import ContactsTopbar from './components/ContactsTopbar.vue';
import GroupsSection from './components/GroupsSection.vue';
import StatsSection from './components/StatsSection.vue';
import {
  PADEL_CITIES, DEFAULT_GROUPS,
  type Contact, type Group, type CallRecord,
  type ContactFormData, type GroupFormData,
} from './data/contact-types';
import {
  loadContacts, saveContacts,
  loadGroups, saveGroups,
  loadCallHistory, saveCallHistory,
} from './data/contacts-api';

// --- CONFIGURACIÓN DE PESTAÑAS ---
const tabs = [
  { id: 'contacts', label: 'Contactos' },
  { id: 'groups',   label: 'Grupos' },
  { id: 'stats',    label: 'Estadísticas' },
  { id: 'history',  label: 'Historial' },
] as const;
type TabId = typeof tabs[number]['id'];

// REGLAS REGEX (Validación estructurada de datos)
const PHONE_RE = /^\+?[1-9]\d{7,14}$/;
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

const EMPTY_CONTACT_FORM: ContactFormData = { name: '', surname: '', phone: '', email: '', groupId: null, city: '' };
const EMPTY_GROUP_FORM: GroupFormData = { name: '', color: '#c9ff00' };

// --- ESTADO REACTIVO PRINCIPAL ---
const activeTab = ref<TabId>('contacts');
const contacts = ref<Contact[]>([]);
  const groups = ref<Group[]>([]);
const loading = ref(false);

// Filtros y Ordenación
const searchQ = ref('');
const filterGroup = ref<number | ''>('');
const sortBy = ref<'name' | 'date'>('name');

// Control de Modales y Formularios
const showContactModal = ref(false);
const editingContactId = ref<number | null>(null);
const cForm = reactive<ContactFormData>({ ...EMPTY_CONTACT_FORM });
const cErrors = reactive<Partial<Record<keyof ContactFormData, string>>>({});

const showGroupModal = ref(false);
const editingGroupId = ref<number | null>(null);
const gForm = reactive<GroupFormData>({ ...EMPTY_GROUP_FORM });
const gErrors = reactive<Partial<Record<keyof GroupFormData, string>>>({});

const callHistory = ref<CallRecord[]>([]);

// --- PROPIEDADES COMPUTADAS (Mínimo 3 requeridas) ---

// 1. Filtrado y ordenación combinados sin mutación destructiva
const filteredContacts = computed(() => {
  const q = searchQ.value.toLowerCase();
  let list = contacts.value.filter(c => {
    const matchSearch = !q || c.name.toLowerCase().includes(q) || c.surname.toLowerCase().includes(q) || c.phone.includes(q);
    const matchGroup = filterGroup.value === '' || c.groupId === filterGroup.value;
    return matchSearch && matchGroup;
  });
  return sortBy.value === 'name' 
    ? [...list].sort((a, b) => a.name.localeCompare(b.name))
    : [...list].sort((a, b) => b.createdAt.localeCompare(a.createdAt));
});

// 2. Cálculo de altas en los últimos 7 días
const recentContacts = computed(() => {
  const limit = Date.now() - 7 * 24 * 3600 * 1000;
  return contacts.value.filter(c => new Date(c.createdAt).getTime() >= limit);
});

// 3. Distribución agregada de contactos por ID de grupo
const contactsPerGroup = computed(() => {
  const map: Record<number, number> = {};
  groups.value.forEach(g => map[g.id] = 0);
  contacts.value.forEach(c => { if (map[c.groupId] !== undefined) map[c.groupId]++; });
  return map;
});

// Validador de formulario en tiempo real para activar/desactivar botón de guardado
const isContactFormValid = computed(() => 
  cForm.name.length >= 2 && cForm.surname.length >= 2 && PHONE_RE.test(cForm.phone) && cForm.groupId !== null
);

const sortedCallHistory = computed(() => [...callHistory.value].reverse());

// --- MÉTODOS HELPER ---
const initials = (n: string, s: string) => ((n[0] ?? '') + (s[0] ?? '')).toUpperCase();
const groupColorOf = (id: number) => groups.value.find(g => g.id === id)?.color ?? '#555';
const groupNameOf = (id: number) => groups.value.find(g => g.id === id)?.name ?? '—';
const clearErrors = (obj: object) => Object.keys(obj).forEach(k => delete obj[k as keyof typeof obj]);

// --- OPERACIONES DEL CRUD: CONTACTOS ---
function resetContactForm(contact: Contact | null) {
  editingContactId.value = contact?.id ?? null;
  Object.assign(cForm, contact ? { ...contact } : EMPTY_CONTACT_FORM);
  clearErrors(cErrors);
}

function openContactForm(contact: Contact | null) {
  resetContactForm(contact);
  showContactModal.value = true;
}

function validateContactForm(): boolean {
  cErrors.name = cForm.name.length < 2 ? 'Mínimo 2 caracteres' : undefined;
  cErrors.surname = cForm.surname.length < 2 ? 'Mínimo 2 caracteres' : undefined;
  cErrors.phone = !PHONE_RE.test(cForm.phone) ? 'Formato inválido (+34612345678)' : undefined;
  cErrors.groupId = !cForm.groupId ? 'Selecciona un grupo' : undefined;
  cErrors.email = cForm.email && !EMAIL_RE.test(cForm.email) ? 'Correo inválido' : undefined;

  const isDuplicate = contacts.value.some(c => c.phone === cForm.phone && c.id !== editingContactId.value);
  if (isDuplicate) cErrors.phone = 'Teléfono ya existente';

  return !Object.values(cErrors).some(Boolean);
}

function submitContactForm() {
  if (!validateContactForm()) return;
  const payload = { name: cForm.name, surname: cForm.surname, phone: cForm.phone, email: cForm.email, groupId: cForm.groupId as number, city: cForm.city };

  if (editingContactId.value !== null) {
    const idx = contacts.value.findIndex(c => c.id === editingContactId.value);
    if (idx !== -1) contacts.value[idx] = { ...contacts.value[idx], ...payload };
  } else {
    contacts.value.push({ id: Date.now(), ...payload, createdAt: new Date().toISOString() });
  }
  saveContacts(contacts.value);
  showContactModal.value = false;
}

function deleteContact(id: number) {
  if (confirm('¿Eliminar este contacto?')) {
    contacts.value = contacts.value.filter(c => c.id !== id);
    saveContacts(contacts.value);
  }
}

// --- FUNCIONALIDADES COMPLEMENTARIAS ---
function toggleFavorite(id: number) {
  const idx = contacts.value.findIndex(c => c.id === id);
  if (idx !== -1) {
    contacts.value[idx].favorite = !contacts.value[idx].favorite;
    saveContacts(contacts.value);
  }
}

function logCall(contact: Contact) {
  callHistory.value.push({ id: Date.now(), contactId: contact.id, contactName: `${contact.name} ${contact.surname}`, date: new Date().toISOString() });
  saveCallHistory(callHistory.value);
}

function clearCallHistory() {
  callHistory.value = [];
  saveCallHistory([]);
}

// --- OPERACIONES DEL CRUD: GRUPOS ---
function resetGroupForm(group: Group | null) {
  editingGroupId.value = group?.id ?? null;
  Object.assign(gForm, group ? { ...group } : EMPTY_GROUP_FORM);
  clearErrors(gErrors);
}

function openGroupForm(group: Group | null) {
  resetGroupForm(group);
  showGroupModal.value = true;
}

function submitGroupForm() {
  if (gForm.name.length < 2) { gErrors.name = 'Mínimo 2 caracteres'; return; }

  if (editingGroupId.value !== null) {
    const idx = groups.value.findIndex(g => g.id === editingGroupId.value);
    if (idx !== -1) groups.value[idx] = { ...groups.value[idx], name: gForm.name, color: gForm.color };
  } else {
    groups.value.push({ id: Date.now(), name: gForm.name, color: gForm.color });
  }
  saveGroups(groups.value);
  showGroupModal.value = false;
}

function deleteGroup(id: number) {
  if (contacts.value.some(c => c.groupId === id)) return alert('No puedes eliminar un grupo con contactos asignados.');
  if (DEFAULT_GROUPS.some(g => g.id === id)) return alert('No puedes eliminar un grupo por defecto.');
  
  if (confirm('¿Eliminar este grupo?')) {
    groups.value = groups.value.filter(g => g.id !== id);
    saveGroups(groups.value);
  }
}

// --- HOOK DE INICIALIZACIÓN ---
onMounted(async () => {
  groups.value = loadGroups();
  loading.value = true;
  contacts.value = await loadContacts();
  loading.value = false;
  callHistory.value = loadCallHistory();
});
</script>