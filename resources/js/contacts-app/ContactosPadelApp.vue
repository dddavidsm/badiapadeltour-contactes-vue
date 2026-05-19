<template>
  <div class="bpt-app">
    <ContactsTopbar
      :active-tab="activeTab"
      :tabs="tabs"
      @change-tab="activeTab = $event as TabId"
    />

    <ContactsListSection
      v-if="activeTab === 'contacts'"
      :contacts="filteredContacts"
      :filter-group="filterGroup"
      :group-color-of="groupColorOf"
      :group-name-of="groupNameOf"
      :groups="groups"
      :initials="initials"
      :loading="loading"
      :search-q="searchQ"
      :sort-by="sortBy"
      @delete-contact="deleteContact"
      @edit-contact="openContactForm"
      @open-contact="openContactForm(null)"
      @update:filter-group="filterGroup = $event"
      @update:search-q="searchQ = $event"
      @update:sort-by="sortBy = $event"
    />

    <GroupsSection
      v-if="activeTab === 'groups'"
      :contacts-per-group="contactsPerGroup"
      :groups="groups"
      @delete-group="deleteGroup"
      @edit-group="openGroupForm"
      @open-group="openGroupForm(null)"
    />

    <StatsSection
      v-if="activeTab === 'stats'"
      :contacts-count="contacts.length"
      :contacts-per-group="contactsPerGroup"
      :groups="groups"
      :recent-contacts-count="recentContacts.length"
    />

    <!-- ═══════════════════ MODAL: Contacte ════════════════════════════ -->
    <div v-if="showContactModal" class="bpt-overlay" @click.self="closeContactModal">
      <div class="bpt-modal">
        <h3>{{ editingContactId ? 'Editar contacte' : 'Nou contacte' }}</h3>
        <form @submit.prevent="submitContactForm" novalidate>
          <div class="bpt-form-grid">
            <div class="bpt-field">
              <label>Nom *</label>
              <input v-model.trim="cForm.name" type="text" class="bpt-input" placeholder="Marc" />
              <span class="bpt-err" v-if="cErrors.name">{{ cErrors.name }}</span>
            </div>
            <div class="bpt-field">
              <label>Cognoms *</label>
              <input v-model.trim="cForm.surname" type="text" class="bpt-input" placeholder="Puig Roca" />
              <span class="bpt-err" v-if="cErrors.surname">{{ cErrors.surname }}</span>
            </div>
            <div class="bpt-field">
              <label>Telèfon * <small>(E.164)</small></label>
              <input v-model.trim="cForm.phone" type="tel" class="bpt-input" placeholder="+34612345678" />
              <span class="bpt-err" v-if="cErrors.phone">{{ cErrors.phone }}</span>
            </div>
            <div class="bpt-field">
              <label>Correu</label>
              <input v-model.trim="cForm.email" type="email" class="bpt-input" placeholder="marc@bpt.cat" />
              <span class="bpt-err" v-if="cErrors.email">{{ cErrors.email }}</span>
            </div>
            <div class="bpt-field">
              <label>Grup *</label>
              <select v-model="cForm.groupId" class="bpt-input">
                <option :value="null">— Selecciona —</option>
                <option v-for="g in groups" :key="g.id" :value="g.id">{{ g.name }}</option>
              </select>
              <span class="bpt-err" v-if="cErrors.groupId">{{ cErrors.groupId }}</span>
            </div>
            <div class="bpt-field">
              <label>Ciutat</label>
              <select v-model="cForm.city" class="bpt-input">
                <option value="">— Selecciona —</option>
                <option v-for="city in PADEL_CITIES" :key="city" :value="city">{{ city }}</option>
              </select>
            </div>
          </div>
          <div class="bpt-modal-actions">
            <button type="submit" class="bpt-btn bpt-btn-primary" :disabled="!isContactFormValid">
              {{ editingContactId ? 'Guardar' : 'Afegir' }}
            </button>
            <button type="button" class="bpt-btn bpt-btn-ghost" @click="closeContactModal">Cancel·lar</button>
          </div>
        </form>
      </div>
    </div>

    <!-- ═══════════════════ MODAL: Grup ════════════════════════════════ -->
    <div v-if="showGroupModal" class="bpt-overlay" @click.self="closeGroupModal">
      <div class="bpt-modal">
        <h3>{{ editingGroupId ? 'Editar grup' : 'Nou grup' }}</h3>
        <form @submit.prevent="submitGroupForm" novalidate>
          <div class="bpt-form-grid">
            <div class="bpt-field">
              <label>Nom *</label>
              <input v-model.trim="gForm.name" type="text" class="bpt-input" placeholder="Ex: Companys" />
              <span class="bpt-err" v-if="gErrors.name">{{ gErrors.name }}</span>
            </div>
            <div class="bpt-field">
              <label>Color</label>
              <input v-model="gForm.color" type="color" class="bpt-input bpt-color-input" />
            </div>
          </div>
          <div class="bpt-modal-actions">
            <button type="submit" class="bpt-btn bpt-btn-primary">
              {{ editingGroupId ? 'Guardar' : 'Crear' }}
            </button>
            <button type="button" class="bpt-btn bpt-btn-ghost" @click="closeGroupModal">Cancel·lar</button>
          </div>
        </form>
      </div>
    </div>
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
  type Contact, type Group,
  type ContactFormData, type GroupFormData,
} from './data/contact-types';
import {
  loadContacts, saveContacts,
  loadGroups, saveGroups,
} from './data/contacts-api';

// Root component owns all state; sections below are presentational children.
const tabs = [
  { id: 'contacts', label: 'Contactes' },
  { id: 'groups',   label: 'Grups' },
  { id: 'stats',    label: 'Estadístiques' },
] as const;

type TabId = typeof tabs[number]['id'];
const PHONE_RE = /^\+?[1-9]\d{7,14}$/;
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const EMPTY_CONTACT_FORM: ContactFormData = {
  name: '',
  surname: '',
  phone: '',
  email: '',
  groupId: null,
  city: '',
};
const EMPTY_GROUP_FORM: GroupFormData = {
  name: '',
  color: '#c9ff00',
};

const activeTab = ref<TabId>('contacts');
const contacts = ref<Contact[]>([]);
const groups = ref<Group[]>([]);
const loading = ref(false);
const searchQ = ref('');
const filterGroup = ref<number | ''>('');
const sortBy = ref<'name' | 'date'>('name');

// Contact modal state: when showContactModal=true, template renders add/edit modal.
const showContactModal = ref(false);
const editingContactId = ref<number | null>(null);
const cForm = reactive<ContactFormData>({ ...EMPTY_CONTACT_FORM });
const cErrors = reactive<Partial<Record<keyof ContactFormData, string>>>({});

// Group modal state follows the same pattern as contact modal.
const showGroupModal = ref(false);
const editingGroupId = ref<number | null>(null);
const gForm = reactive<GroupFormData>({ ...EMPTY_GROUP_FORM });
const gErrors = reactive<Partial<Record<keyof GroupFormData, string>>>({});

const filteredContacts = computed<Contact[]>(() => {
  const q = searchQ.value.toLowerCase();
  let list = contacts.value.filter((c) => {
    const matchSearch = !q
      || c.name.toLowerCase().includes(q)
      || c.surname.toLowerCase().includes(q)
      || c.phone.includes(q);
    const matchGroup = filterGroup.value === '' || c.groupId === filterGroup.value;
    return matchSearch && matchGroup;
  });
  if (sortBy.value === 'name') list = [...list].sort((a, b) => a.name.localeCompare(b.name));
  if (sortBy.value === 'date') list = [...list].sort((a, b) => b.createdAt.localeCompare(a.createdAt));
  return list;
});

const recentContacts = computed(() => {
  const week = Date.now() - 7 * 24 * 3600 * 1000;
  return contacts.value.filter((c) => new Date(c.createdAt).getTime() >= week);
});

const contactsPerGroup = computed<Record<number, number>>(() => {
  const map: Record<number, number> = {};
  for (const g of groups.value) map[g.id] = 0;
  for (const c of contacts.value) {
    map[c.groupId] = (map[c.groupId] ?? 0) + 1;
  }
  return map;
});

const isContactFormValid = computed(() =>
  cForm.name.length >= 2 &&
  cForm.surname.length >= 2 &&
  PHONE_RE.test(cForm.phone) &&
  cForm.groupId !== null
);

const initials = (name: string, surname: string) =>
  ((name[0] ?? '') + (surname[0] ?? '')).toUpperCase();

const groupColorOf = (groupId: number) =>
  groups.value.find((g) => g.id === groupId)?.color ?? '#555';

const groupNameOf = (groupId: number) =>
  groups.value.find((g) => g.id === groupId)?.name ?? '—';

function clearErrors<T extends string>(errors: Partial<Record<T, string>>): void {
  Object.keys(errors).forEach((key) => delete errors[key as T]);
}

function persistContacts(): void {
  saveContacts(contacts.value);
}

function persistGroups(): void {
  saveGroups(groups.value);
}

function resetContactForm(contact: Contact | null): void {
  editingContactId.value = contact?.id ?? null;
  Object.assign(cForm, {
    ...EMPTY_CONTACT_FORM,
    name: contact?.name ?? '',
    surname: contact?.surname ?? '',
    phone: contact?.phone ?? '',
    email: contact?.email ?? '',
    groupId: contact?.groupId ?? null,
    city: contact?.city ?? '',
  });
  clearErrors(cErrors);
}

function resetGroupForm(group: Group | null): void {
  editingGroupId.value = group?.id ?? null;
  Object.assign(gForm, {
    ...EMPTY_GROUP_FORM,
    name: group?.name ?? '',
    color: group?.color ?? EMPTY_GROUP_FORM.color,
  });
  clearErrors(gErrors);
}

function setContactError(field: keyof ContactFormData, message: string | null): boolean {
  if (message) {
    cErrors[field] = message;
    return false;
  }
  delete cErrors[field];
  return true;
}

// Called from child section events:
// - openContactForm(null) => add modal
// - openContactForm(contact) => edit modal
function openContactForm(contact: Contact | null): void {
  resetContactForm(contact);
  showContactModal.value = true;
}

function closeContactModal(): void { showContactModal.value = false; }

function validateContactForm(): boolean {
  let ok = true;
  ok = setContactError('name', cForm.name.length < 2 ? 'Mínim 2 caràcters' : null) && ok;
  ok = setContactError('surname', cForm.surname.length < 2 ? 'Mínim 2 caràcters' : null) && ok;
  ok = setContactError('phone', !PHONE_RE.test(cForm.phone) ? 'Format invàlid (+34612345678)' : null) && ok;
  ok = setContactError('groupId', !cForm.groupId ? 'Selecciona un grup' : null) && ok;
  ok = setContactError('email', cForm.email && !EMAIL_RE.test(cForm.email) ? 'Correu invàlid' : null) && ok;

  const dup = contacts.value.find(
    (c) => c.phone === cForm.phone && c.id !== editingContactId.value
  );
  if (dup) {
    cErrors.phone = 'Telèfon ja existent';
    ok = false;
  }
  return ok;
}

function submitContactForm(): void {
  if (!validateContactForm()) return;

  const payload = {
    name: cForm.name,
    surname: cForm.surname,
    phone: cForm.phone,
    email: cForm.email,
    groupId: cForm.groupId as number,
    city: cForm.city,
  };

  if (editingContactId.value !== null) {
    // Edit flow: replace the found contact while keeping immutable fields.
    const idx = contacts.value.findIndex((c) => c.id === editingContactId.value);
    if (idx !== -1) {
      contacts.value[idx] = { ...contacts.value[idx], ...payload };
    }
  } else {
    // Create flow: append a new contact with timestamp id/date.
    contacts.value.push({
      id: Date.now(),
      ...payload,
      createdAt: new Date().toISOString(),
    });
  }
  persistContacts();
  closeContactModal();
}

function deleteContact(id: number): void {
  if (!confirm('Eliminar aquest contacte?')) return;
  contacts.value = contacts.value.filter((c) => c.id !== id);
  persistContacts();
}

function openGroupForm(group: Group | null): void {
  resetGroupForm(group);
  showGroupModal.value = true;
}

function closeGroupModal(): void { showGroupModal.value = false; }

function submitGroupForm(): void {
  if (gForm.name.length < 2) {
    gErrors.name = 'Mínim 2 caràcters';
    return;
  }

  if (editingGroupId.value !== null) {
    const idx = groups.value.findIndex((g) => g.id === editingGroupId.value);
    if (idx !== -1) groups.value[idx] = { ...groups.value[idx], name: gForm.name, color: gForm.color };
  } else {
    groups.value.push({ id: Date.now(), name: gForm.name, color: gForm.color });
  }
  persistGroups();
  closeGroupModal();
}

function deleteGroup(id: number): void {
  if (contacts.value.some((c) => c.groupId === id)) {
    alert('No pots eliminar un grup amb contactes assignats.'); return;
  }
  if (DEFAULT_GROUPS.some((g) => g.id === id)) {
    alert('No pots eliminar un grup per defecte.'); return;
  }
  if (!confirm('Eliminar aquest grup?')) return;
  groups.value = groups.value.filter((g) => g.id !== id);
  persistGroups();
}

onMounted(async () => {
  // Load local state when module starts.
  groups.value = loadGroups();
  loading.value = true;
  contacts.value = await loadContacts();
  loading.value = false;
});
</script>

<style>
.bpt-app {
  --electric: #c9ff00;
  --bg: #111;
  --card: #181818;
  --border: #282828;
  --text: #e0e0e0;
  --muted: #777;
  font-family: 'Gopher', sans-serif;
  color: var(--text);
  min-height: 60vh;
  background: var(--bg);
  border-radius: 12px;
  overflow: hidden;
}

/* Top bar */
.bpt-topbar {
  display: flex; flex-wrap: wrap; align-items: center;
  justify-content: space-between; gap: .5rem;
  padding: .9rem 1.25rem;
  border-bottom: 1px solid var(--border);
}
.bpt-topbar-left { display: flex; align-items: center; gap: .75rem; }
.bpt-logo { font-size: 1.1rem; font-weight: 900; color: var(--electric); }

/* Tabs */
.bpt-tabs { display: flex; gap: .2rem; flex-wrap: wrap; }
.bpt-tab {
  background: none; border: 1px solid var(--border); border-radius: 6px;
  color: var(--muted); padding: .3rem .75rem; cursor: pointer;
  font-family: inherit; font-size: .82rem; transition: all .15s;
}
.bpt-tab.active {
  background: var(--electric); color: #111;
  border-color: var(--electric); font-weight: 700;
}

/* Section */
.bpt-section { padding: 1rem 1.25rem; }

/* Filters */
.bpt-filters { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: .75rem; }

/* Input */
.bpt-input {
  background: #1d1d1d; border: 1px solid var(--border);
  border-radius: 7px; color: var(--text);
  padding: .4rem .65rem; font-size: .85rem;
  font-family: inherit; outline: none; min-width: 130px;
}
.bpt-input:focus { border-color: var(--electric); }

/* Buttons */
.bpt-btn {
  padding: .4rem .9rem; border: none; border-radius: 7px;
  cursor: pointer; font-family: inherit; font-size: .85rem; font-weight: 600;
}
.bpt-btn-primary { background: var(--electric); color: #111; }
.bpt-btn-primary:disabled { opacity: .4; cursor: not-allowed; }
.bpt-btn-ghost { background: transparent; border: 1px solid var(--border); color: var(--text); }

.bpt-icon-btn {
  background: none; border: none; cursor: pointer;
  font-size: .9rem; padding: .15rem .2rem;
  border-radius: 4px; opacity: .65; transition: opacity .12s, background .12s;
}
.bpt-icon-btn:hover { opacity: 1; background: var(--border); }
.bpt-icon-btn.danger:hover { color: #ff6b6b; background: rgba(255,107,107,.12); }

/* Grid */
.bpt-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: .75rem;
}

/* Card */
.bpt-card {
  background: var(--card); border: 1px solid var(--border);
  border-radius: 10px; padding: .85rem;
}

/* Contact card */
.bpt-contact-card { display: flex; gap: .65rem; align-items: flex-start; }
.bpt-avatar {
  width: 38px; height: 38px; border-radius: 50%;
  color: #111; font-weight: 900; font-size: .88rem;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.bpt-contact-info {
  flex: 1; display: flex; flex-direction: column;
  gap: .12rem; font-size: .78rem; color: var(--muted);
}
.bpt-contact-info strong { font-size: .88rem; color: var(--text); }
.bpt-group-badge {
  display: inline-block; border-radius: 99px;
  padding: .08rem .45rem; font-size: .7rem; font-weight: 600;
  width: fit-content; margin-top: .15rem;
}
.bpt-card-actions { display: flex; flex-direction: column; gap: .2rem; flex-shrink: 0; }

/* Group card */
.bpt-group-card { display: flex; align-items: center; gap: .65rem; }
.bpt-group-dot { width: 13px; height: 13px; border-radius: 50%; flex-shrink: 0; }
.bpt-group-info { flex: 1; display: flex; flex-direction: column; gap: .08rem; font-size: .83rem; }

/* Stats */
.bpt-stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
  gap: .65rem;
}
.bpt-stat-card {
  background: var(--card); border: 1px solid var(--border); border-radius: 10px;
  padding: .9rem; display: flex; flex-direction: column; align-items: center; gap: .25rem;
}
.bpt-stat-val { font-size: 1.8rem; font-weight: 900; color: var(--electric); }
.bpt-stat-label { font-size: .75rem; color: var(--muted); text-align: center; }
.bpt-stat-row {
  display: flex; align-items: center; gap: .65rem;
  font-size: .83rem; margin-bottom: .45rem;
}
.bpt-bar-wrap { flex: 1; height: 7px; background: #222; border-radius: 99px; overflow: hidden; }
.bpt-bar { height: 100%; border-radius: 99px; transition: width .4s; }

/* Overlay & Modal */
.bpt-overlay {
  position: fixed; inset: 0; background: rgba(0,0,0,.75);
  display: flex; align-items: center; justify-content: center; z-index: 9999;
}
.bpt-modal {
  background: var(--card); border: 1px solid var(--border); border-radius: 14px;
  padding: 1.35rem; width: 100%; max-width: 460px; max-height: 90vh; overflow-y: auto;
}
.bpt-modal h3 { margin: 0 0 .9rem; font-size: .95rem; color: var(--electric); }
.bpt-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .65rem; }
.bpt-field { display: flex; flex-direction: column; gap: .25rem; }
.bpt-field label { font-size: .75rem; color: var(--muted); }
.bpt-err { color: #ff6b6b; font-size: .72rem; }
.bpt-modal-actions { display: flex; gap: .45rem; margin-top: .9rem; }
.bpt-color-input { padding: .1rem .2rem; height: 2.1rem; cursor: pointer; }

/* State */
.bpt-state { text-align: center; padding: 2rem; color: var(--muted); }
.bpt-muted { color: var(--muted); font-size: .78rem; }
</style>
