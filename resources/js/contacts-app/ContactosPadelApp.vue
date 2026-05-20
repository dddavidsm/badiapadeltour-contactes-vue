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

// --- CONFIGURACIÓN BASE ---
const tabs = [
  { id: 'contacts', label: 'Contactos' },
  { id: 'groups',   label: 'Grupos' },
  { id: 'stats',    label: 'Estadísticas' },
] as const;
type TabId = typeof tabs[number]['id'];

// REGEX: Reglas de validación para teléfono (empieza por +, opcional y de 7 a 14 números) y correo
const PHONE_RE = /^\+?[1-9]\d{7,14}$/;
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// ESTRUCTURAS VACÍAS: Se usan para reiniciar los modales al abrirlos
const EMPTY_CONTACT_FORM: ContactFormData = {
  name: '', surname: '', phone: '', email: '', groupId: null, city: '',
};
const EMPTY_GROUP_FORM: GroupFormData = {
  name: '', color: '#c9ff00',
};

// --- ESTADO PRINCIPAL (Variables Reactivas) ---
const activeTab = ref<TabId>('contacts'); // Controla qué sección se ve
const contacts = ref<Contact[]>([]);      // Array maestro con los datos crudos
const groups = ref<Group[]>([]);          // Array maestro de grupos
const loading = ref(false);               // Control de carga

// Estado para los filtros de la lista
const searchQ = ref('');
const filterGroup = ref<number | ''>('');
const sortBy = ref<'name' | 'date'>('name');

// Estado del Modal de Contacto
const showContactModal = ref(false);
const editingContactId = ref<number | null>(null); // Si tiene ID = Modo Edición, null = Modo Alta
const cForm = reactive<ContactFormData>({ ...EMPTY_CONTACT_FORM }); // Datos introducidos en vivo
const cErrors = reactive<Partial<Record<keyof ContactFormData, string>>>({}); // Mensajes de error en rojo

// Estado del Modal de Grupo
const showGroupModal = ref(false);
const editingGroupId = ref<number | null>(null);
const gForm = reactive<GroupFormData>({ ...EMPTY_GROUP_FORM });
const gErrors = reactive<Partial<Record<keyof GroupFormData, string>>>({});

// --- COMPUTED PROPERTIES (Lógica derivada) ---

// EL CORAZÓN DE LA TABLA: Filtra y Ordena en tiempo real según lo que escribe/selecciona el usuario.
const filteredContacts = computed<Contact[]>(() => {
  const q = searchQ.value.toLowerCase();
  
  // 1. Filtrado
  let list = contacts.value.filter((c) => {
    // Si la búsqueda está vacía o el texto coincide con nombre, apellido o teléfono...
    const matchSearch = !q
      || c.name.toLowerCase().includes(q)
      || c.surname.toLowerCase().includes(q)
      || c.phone.includes(q);
    // Y si no hay filtro de grupo o el contacto pertenece al grupo filtrado...
    const matchGroup = filterGroup.value === '' || c.groupId === filterGroup.value;
    
    return matchSearch && matchGroup;
  });
  
  // 2. Ordenación. IMPORTANTE: [...list] clona el array para no mutar el reactivo 'list'
  if (sortBy.value === 'name') list = [...list].sort((a, b) => a.name.localeCompare(b.name));
  if (sortBy.value === 'date') list = [...list].sort((a, b) => b.createdAt.localeCompare(a.createdAt));
  return list;
});

// Calcula los contactos de los últimos 7 días (para la pestaña estadísticas)
const recentContacts = computed(() => {
  const week = Date.now() - 7 * 24 * 3600 * 1000;
  return contacts.value.filter((c) => new Date(c.createdAt).getTime() >= week);
});

// Calcula el conteo de personas en cada grupo (devuelve algo como { 1: 5, 2: 0, 3: 2 })
const contactsPerGroup = computed<Record<number, number>>(() => {
  const map: Record<number, number> = {};
  for (const g of groups.value) map[g.id] = 0; // Inicializa todos en 0
  for (const c of contacts.value) {
    map[c.groupId] = (map[c.groupId] ?? 0) + 1; // Suma 1 por cada contacto
  }
  return map;
});

// Validador rápido: Bloquea el botón del HTML si no se cumplen mínimos
const isContactFormValid = computed(() =>
  cForm.name.length >= 2 && cForm.surname.length >= 2 && PHONE_RE.test(cForm.phone) && cForm.groupId !== null
);

// --- FUNCIONES HELPER (Ayudantes) ---
const initials = (name: string, surname: string) => ((name[0] ?? '') + (surname[0] ?? '')).toUpperCase();
const groupColorOf = (groupId: number) => groups.value.find((g) => g.id === groupId)?.color ?? '#555';
const groupNameOf = (groupId: number) => groups.value.find((g) => g.id === groupId)?.name ?? '—';

function clearErrors<T extends string>(errors: Partial<Record<T, string>>): void {
  Object.keys(errors).forEach((key) => delete errors[key as T]);
}

function persistContacts(): void { saveContacts(contacts.value); }
function persistGroups(): void { saveGroups(groups.value); }

// --- LÓGICA DE FORMULARIO DE CONTACTO ---

function resetContactForm(contact: Contact | null): void {
  // Prepara el modal, ya sea vaciándolo (si contact es null) o rellenándolo (si estamos editando)
  editingContactId.value = contact?.id ?? null;
  Object.assign(cForm, {
    ...EMPTY_CONTACT_FORM,
    name: contact?.name ?? '', surname: contact?.surname ?? '', phone: contact?.phone ?? '',
    email: contact?.email ?? '', groupId: contact?.groupId ?? null, city: contact?.city ?? '',
  });
  clearErrors(cErrors);
}

function resetGroupForm(group: Group | null): void {
  // Igual que el anterior, pero para grupos
  editingGroupId.value = group?.id ?? null;
  Object.assign(gForm, {
    ...EMPTY_GROUP_FORM, name: group?.name ?? '', color: group?.color ?? EMPTY_GROUP_FORM.color,
  });
  clearErrors(gErrors);
}

// Función que pinta los errores en el formulario
function setContactError(field: keyof ContactFormData, message: string | null): boolean {
  if (message) {
    cErrors[field] = message; return false;
  }
  delete cErrors[field]; return true;
}

function openContactForm(contact: Contact | null): void {
  resetContactForm(contact); showContactModal.value = true;
}
function closeContactModal(): void { showContactModal.value = false; }

function validateContactForm(): boolean {
  let ok = true;
  ok = setContactError('name', cForm.name.length < 2 ? 'Mínimo 2 caracteres' : null) && ok;
  ok = setContactError('surname', cForm.surname.length < 2 ? 'Mínimo 2 caracteres' : null) && ok;
  ok = setContactError('phone', !PHONE_RE.test(cForm.phone) ? 'Formato inválido (+34612345678)' : null) && ok;
  ok = setContactError('groupId', !cForm.groupId ? 'Selecciona un grupo' : null) && ok;
  ok = setContactError('email', cForm.email && !EMAIL_RE.test(cForm.email) ? 'Correo inválido' : null) && ok;

  // VERIFICACIÓN DE REGLA: Teléfono duplicado. 
  // Busca si existe alguien con ese número, excluyendo al usuario que estamos editando actualmente.
  const dup = contacts.value.find((c) => c.phone === cForm.phone && c.id !== editingContactId.value);
  if (dup) {
    cErrors.phone = 'Teléfono ya existente'; ok = false;
  }
  return ok;
}

// FUNCIÓN PRINCIPAL DE GUARDADO (ALTA Y EDICIÓN)
function submitContactForm(): void {
  if (!validateContactForm()) return; // Si la validación superior falla, aborta la operación

  const payload = {
    name: cForm.name, surname: cForm.surname, phone: cForm.phone,
    email: cForm.email, groupId: cForm.groupId as number, city: cForm.city,
  };

  if (editingContactId.value !== null) {
    // ESTAMOS EN EDICIÓN: Encuentra al usuario y fusiona (...payload) lo nuevo con lo viejo
    const idx = contacts.value.findIndex((c) => c.id === editingContactId.value);
    if (idx !== -1) {
      contacts.value[idx] = { ...contacts.value[idx], ...payload };
    }
  } else {
    // ESTAMOS EN ALTA: Lo mete en el array y le simula un ID de BD usando Date.now()
    contacts.value.push({
      id: Date.now(),
      ...payload,
      createdAt: new Date().toISOString(),
    });
  }
  // Termina el proceso guardando en el navegador y cerrando la ventana
  persistContacts();
  closeContactModal();
}

function deleteContact(id: number): void {
  if (!confirm('¿Eliminar este contacto?')) return;
  // Para borrar, simplemente filtra la matriz de contactos sacando el que coincida con el ID
  contacts.value = contacts.value.filter((c) => c.id !== id);
  persistContacts();
}

// --- LÓGICA DE GRUPOS --- (Sigue patrones idénticos al de Contactos)
function openGroupForm(group: Group | null): void { resetGroupForm(group); showGroupModal.value = true; }
function closeGroupModal(): void { showGroupModal.value = false; }

function submitGroupForm(): void {
  if (gForm.name.length < 2) { gErrors.name = 'Mínimo 2 caracteres'; return; }

  if (editingGroupId.value !== null) {
    const idx = groups.value.findIndex((g) => g.id === editingGroupId.value);
    if (idx !== -1) groups.value[idx] = { ...groups.value[idx], name: gForm.name, color: gForm.color };
  } else {
    groups.value.push({ id: Date.now(), name: gForm.name, color: gForm.color });
  }
  persistGroups(); closeGroupModal();
}

function deleteGroup(id: number): void {
  // REGLAS ESTRICTAS DE BORRADO DE GRUPO:
  if (contacts.value.some((c) => c.groupId === id)) {
    alert('No puedes eliminar un grupo con contactos asignados.'); return;
  }
  if (DEFAULT_GROUPS.some((g) => g.id === id)) {
    alert('No puedes eliminar un grupo por defecto.'); return;
  }
  
  if (!confirm('¿Eliminar este grupo?')) return;
  groups.value = groups.value.filter((g) => g.id !== id);
  persistGroups();
}

// --- ARRANQUE DE LA APP ---
onMounted(async () => {
  // Esto se ejecuta al iniciar. Llena la app de datos y avisa a la UI cambiando 'loading' a true/false.
  groups.value = loadGroups();
  loading.value = true;
  contacts.value = await loadContacts();
  loading.value = false;
});
</script>