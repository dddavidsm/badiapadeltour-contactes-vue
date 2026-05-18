<template>
  <div class="bpt-app">
    <!-- ── Top bar ──────────────────────────────────────────────────────── -->
    <div class="bpt-topbar">
      <div class="bpt-topbar-left">
        <span class="bpt-logo">🎾 Contactes Pàdel</span>
        <span v-if="userName" class="bpt-user">{{ userName }}</span>
      </div>
      <div class="bpt-tabs">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          :class="['bpt-tab', { active: activeTab === tab.id }]"
          @click="activeTab = tab.id"
        >{{ tab.label }}</button>
      </div>
    </div>

    <!-- ═══════════════════ TAB: CONTACTES ══════════════════════════════ -->
    <div v-if="activeTab === 'contacts'" class="bpt-section">

      <!-- Filters bar -->
      <div class="bpt-filters">
        <input v-model="searchQ" type="search" placeholder="🔍 Cerca per nom, cognom…" class="bpt-input" />
        <select v-model="filterGroup" class="bpt-input">
          <option value="">Tots els grups</option>
          <option v-for="g in groups" :key="g.id" :value="g.id">{{ g.name }}</option>
        </select>
        <select v-model="sortBy" class="bpt-input">
          <option value="name">Ordenar per nom</option>
          <option value="date">Ordenar per data</option>
        </select>
        <button class="bpt-btn bpt-btn-primary" @click="openContactForm(null)">+ Nou contacte</button>
      </div>

      <!-- Contact list -->
      <div v-if="loading" class="bpt-state">Carregant…</div>
      <div v-else-if="!filteredContacts.length" class="bpt-state">Cap contacte trobat.</div>
      <div v-else class="bpt-grid">
        <div
          v-for="c in filteredContacts"
          :key="c.id"
          class="bpt-card bpt-contact-card"
        >
          <div class="bpt-avatar" :style="{ background: groupColorOf(c.groupId) }">
            {{ initials(c.name, c.surname) }}
          </div>
          <div class="bpt-contact-info">
            <strong>{{ c.name }} {{ c.surname }}</strong>
            <span>📞 {{ c.phone }}</span>
            <span>✉️ {{ c.email }}</span>
            <span>📍 {{ c.city }}</span>
            <span class="bpt-group-badge" :style="{ background: groupColorOf(c.groupId) + '33', color: groupColorOf(c.groupId) }">
              {{ groupNameOf(c.groupId) }}
            </span>
          </div>
          <div class="bpt-card-actions">
            <button class="bpt-icon-btn" @click="openContactForm(c)" title="Editar">✏️</button>
            <button class="bpt-icon-btn danger" @click="deleteContact(c.id)" title="Eliminar">🗑️</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════ TAB: GRUPS ══════════════════════════════════ -->
    <div v-if="activeTab === 'groups'" class="bpt-section">
      <div class="bpt-filters">
        <button class="bpt-btn bpt-btn-primary" @click="openGroupForm(null)">+ Nou grup</button>
      </div>
      <div class="bpt-grid">
        <div v-for="g in groups" :key="g.id" class="bpt-card bpt-group-card">
          <div class="bpt-group-dot" :style="{ background: g.color }"></div>
          <div class="bpt-group-info">
            <strong>{{ g.name }}</strong>
            <span class="bpt-muted">{{ contactsPerGroup[g.id] ?? 0 }} contactes</span>
          </div>
          <div class="bpt-card-actions">
            <button class="bpt-icon-btn" @click="openGroupForm(g)">✏️</button>
            <button class="bpt-icon-btn danger" @click="deleteGroup(g.id)">🗑️</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════ TAB: ESTADÍSTIQUES ══════════════════════════ -->
    <div v-if="activeTab === 'stats'" class="bpt-section">
      <div class="bpt-stats-grid">
        <div class="bpt-stat-card">
          <span class="bpt-stat-val">{{ contacts.length }}</span>
          <span class="bpt-stat-label">Total contactes</span>
        </div>
        <div class="bpt-stat-card">
          <span class="bpt-stat-val">{{ recentContacts.length }}</span>
          <span class="bpt-stat-label">Últims 7 dies</span>
        </div>
      </div>

      <div class="bpt-card" style="margin-top:1rem">
        <h4 style="margin:0 0 .75rem;color:var(--electric)">Contactes per grup</h4>
        <div v-for="g in groups" :key="g.id" class="bpt-stat-row">
          <span class="bpt-group-dot" :style="{ background: g.color }"></span>
          <span>{{ g.name }}</span>
          <span class="bpt-bar-wrap">
            <span
              class="bpt-bar"
              :style="{ width: barWidth(contactsPerGroup[g.id] ?? 0), background: g.color }"
            ></span>
          </span>
          <span class="bpt-muted">{{ contactsPerGroup[g.id] ?? 0 }}</span>
        </div>
      </div>
    </div>

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
import {
  PADEL_CITIES, DEFAULT_GROUPS,
  type Contact, type Group,
  type ContactFormData, type GroupFormData,
} from './data/contact-types';
import {
  loadContacts, saveContacts,
  loadGroups, saveGroups,
} from './data/contacts-api';

// ── Props ──────────────────────────────────────────────────────────────────
const props = defineProps<{ userId: string; userName: string }>();

// ── Tabs ───────────────────────────────────────────────────────────────────
const tabs = [
  { id: 'contacts', label: 'Contactes' },
  { id: 'groups',   label: 'Grups' },
  { id: 'stats',    label: 'Estadístiques' },
] as const;
type TabId = typeof tabs[number]['id'];
const activeTab = ref<TabId>('contacts');

// ── Core state ─────────────────────────────────────────────────────────────
const contacts = ref<Contact[]>([]);
const groups   = ref<Group[]>([]);
const loading  = ref(false);

// ── Filters / sort ─────────────────────────────────────────────────────────
const searchQ     = ref('');
const filterGroup = ref<number | ''>('');
const sortBy      = ref<'name' | 'date'>('name');

// ── Contact modal ─────────────────────────────────────────────────────────
const showContactModal  = ref(false);
const editingContactId  = ref<number | null>(null);
const cForm = reactive<ContactFormData>({ name: '', surname: '', phone: '', email: '', groupId: null, city: '' });
const cErrors = reactive<Partial<Record<keyof ContactFormData, string>>>({});

// ── Group modal ───────────────────────────────────────────────────────────
const showGroupModal  = ref(false);
const editingGroupId  = ref<number | null>(null);
const gForm  = reactive<GroupFormData>({ name: '', color: '#c9ff00' });
const gErrors = reactive<Partial<Record<keyof GroupFormData, string>>>({});

// ── Computed properties ────────────────────────────────────────────────────

/** Contacts filtered by search + group + sorted */
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

/** Contacts added in the last 7 days */
const recentContacts = computed(() => {
  const week = Date.now() - 7 * 24 * 3600 * 1000;
  return contacts.value.filter((c) => new Date(c.createdAt).getTime() >= week);
});

/** Count per group id */
const contactsPerGroup = computed<Record<number, number>>(() => {
  const map: Record<number, number> = {};
  for (const g of groups.value) map[g.id] = 0;
  for (const c of contacts.value) {
    map[c.groupId] = (map[c.groupId] ?? 0) + 1;
  }
  return map;
});

/** Contact form is valid enough to submit */
const isContactFormValid = computed(() =>
  cForm.name.length >= 2 &&
  cForm.surname.length >= 2 &&
  /^\+?[1-9]\d{7,14}$/.test(cForm.phone) &&
  cForm.groupId !== null
);

// ── Helpers ───────────────────────────────────────────────────────────────
const initials = (name: string, surname: string) =>
  ((name[0] ?? '') + (surname[0] ?? '')).toUpperCase();

const groupColorOf = (groupId: number) =>
  groups.value.find((g) => g.id === groupId)?.color ?? '#555';

const groupNameOf = (groupId: number) =>
  groups.value.find((g) => g.id === groupId)?.name ?? '—';

const barWidth = (count: number) => {
  const max = Math.max(...Object.values(contactsPerGroup.value), 1);
  return `${Math.round((count / max) * 100)}%`;
};

// ── Contact CRUD ───────────────────────────────────────────────────────────
function openContactForm(contact: Contact | null): void {
  editingContactId.value = contact?.id ?? null;
  Object.assign(cForm, {
    name:    contact?.name    ?? '',
    surname: contact?.surname ?? '',
    phone:   contact?.phone   ?? '',
    email:   contact?.email   ?? '',
    groupId: contact?.groupId ?? null,
    city:    contact?.city    ?? '',
  });
  Object.keys(cErrors).forEach((k) => delete (cErrors as Record<string, string>)[k]);
  showContactModal.value = true;
}

function closeContactModal(): void { showContactModal.value = false; }

function validateContactForm(): boolean {
  let ok = true;
  const set = (k: keyof ContactFormData, msg: string | null) => {
    if (msg) { (cErrors as Record<string, string>)[k] = msg; ok = false; }
    else      delete (cErrors as Record<string, string>)[k];
  };
  set('name',    cForm.name.length    < 2 ? 'Mínim 2 caràcters' : null);
  set('surname', cForm.surname.length < 2 ? 'Mínim 2 caràcters' : null);
  set('phone',   !/^\+?[1-9]\d{7,14}$/.test(cForm.phone) ? 'Format invàlid (+34612345678)' : null);
  set('groupId', !cForm.groupId ? 'Selecciona un grup' : null);
  set('email',   cForm.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(cForm.email) ? 'Correu invàlid' : null);

  const dup = contacts.value.find(
    (c) => c.phone === cForm.phone && c.id !== editingContactId.value
  );
  if (dup) { (cErrors as Record<string, string>).phone = 'Telèfon ja existent'; ok = false; }
  return ok;
}

function submitContactForm(): void {
  if (!validateContactForm()) return;
  if (editingContactId.value !== null) {
    const idx = contacts.value.findIndex((c) => c.id === editingContactId.value);
    if (idx !== -1) {
      contacts.value[idx] = { ...contacts.value[idx], ...cForm, groupId: cForm.groupId as number };
    }
  } else {
    contacts.value.push({
      id: Date.now(),
      name: cForm.name, surname: cForm.surname,
      phone: cForm.phone, email: cForm.email,
      groupId: cForm.groupId as number, city: cForm.city,

      createdAt: new Date().toISOString(),
    });
  }
  saveContacts(props.userId, contacts.value);
  closeContactModal();
}

function deleteContact(id: number): void {
  if (!confirm('Eliminar aquest contacte?')) return;
  contacts.value = contacts.value.filter((c) => c.id !== id);
  saveContacts(props.userId, contacts.value);
}

// ── Group CRUD ────────────────────────────────────────────────────────────
function openGroupForm(group: Group | null): void {
  editingGroupId.value = group?.id ?? null;
  gForm.name  = group?.name  ?? '';
  gForm.color = group?.color ?? '#c9ff00';
  Object.keys(gErrors).forEach((k) => delete (gErrors as Record<string, string>)[k]);
  showGroupModal.value = true;
}

function closeGroupModal(): void { showGroupModal.value = false; }

function submitGroupForm(): void {
  if (gForm.name.length < 2) { (gErrors as Record<string, string>).name = 'Mínim 2 caràcters'; return; }
  if (editingGroupId.value !== null) {
    const idx = groups.value.findIndex((g) => g.id === editingGroupId.value);
    if (idx !== -1) groups.value[idx] = { ...groups.value[idx], name: gForm.name, color: gForm.color };
  } else {
    groups.value.push({ id: Date.now(), name: gForm.name, color: gForm.color });
  }
  saveGroups(props.userId, groups.value);
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
  saveGroups(props.userId, groups.value);
}

// ── Lifecycle ─────────────────────────────────────────────────────────────
onMounted(async () => {
  groups.value  = loadGroups(props.userId);
  loading.value = true;
  contacts.value = await loadContacts(props.userId);
  loading.value = false;
});
</script>

<style scoped>
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
.bpt-user {
  font-size: .75rem; color: var(--muted);
  background: #222; padding: .15rem .55rem; border-radius: 99px;
}

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
