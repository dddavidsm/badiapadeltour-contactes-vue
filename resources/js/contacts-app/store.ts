import { computed, ref } from 'vue';
import { DEFAULT_CONTACTS, DEFAULT_GROUPS, type Contact, type Group } from './data/contact-types';

const CONTACTS_KEY = 'bpt_contacts_simple';
const GROUPS_KEY = 'bpt_groups_simple';

function safeParse<T>(raw: string | null, fallback: T): T {
  if (!raw) return fallback;
  try {
    return JSON.parse(raw) as T;
  } catch {
    return fallback;
  }
}

export const contacts = ref<Contact[]>(
  safeParse<Contact[]>(localStorage.getItem(CONTACTS_KEY), [...DEFAULT_CONTACTS])
);

export const groups = ref<Group[]>(
  safeParse<Group[]>(localStorage.getItem(GROUPS_KEY), [...DEFAULT_GROUPS])
);

function persistContacts() {
  localStorage.setItem(CONTACTS_KEY, JSON.stringify(contacts.value));
}

function persistGroups() {
  localStorage.setItem(GROUPS_KEY, JSON.stringify(groups.value));
}

export const contactsPerGroup = computed(() => {
  const map: Record<number, number> = {};
  for (const group of groups.value) {
    map[group.id] = 0;
  }
  for (const contact of contacts.value) {
    if (map[contact.groupId] !== undefined) {
      map[contact.groupId] += 1;
    }
  }
  return map;
});

export const recentContactsCount = computed(() => {
  const limit = Date.now() - 7 * 24 * 3600 * 1000;
  return contacts.value.filter((c) => new Date(c.createdAt).getTime() >= limit).length;
});

export function saveContact(payload: Omit<Contact, 'id' | 'createdAt'>, id?: number) {
  if (id) {
    const index = contacts.value.findIndex((c) => c.id === id);
    if (index >= 0) {
      contacts.value[index] = {
        ...contacts.value[index],
        ...payload,
      };
      persistContacts();
    }
    return;
  }

  contacts.value.push({
    id: Date.now(),
    createdAt: new Date().toISOString(),
    ...payload,
  });
  persistContacts();
}

export function deleteContact(id: number) {
  contacts.value = contacts.value.filter((c) => c.id !== id);
  persistContacts();
}

export function toggleFavorite(id: number) {
  const contact = contacts.value.find((c) => c.id === id);
  if (!contact) return;
  contact.favorite = !contact.favorite;
  persistContacts();
}

export function saveGroup(payload: Omit<Group, 'id'>, id?: number) {
  if (id) {
    const index = groups.value.findIndex((g) => g.id === id);
    if (index >= 0) {
      groups.value[index] = {
        ...groups.value[index],
        ...payload,
      };
      persistGroups();
    }
    return;
  }

  groups.value.push({
    id: Date.now(),
    ...payload,
  });
  persistGroups();
}

export function deleteGroup(id: number) {
  contacts.value = contacts.value.map((contact) =>
    contact.groupId === id ? { ...contact, groupId: groups.value[0]?.id ?? id } : contact
  );
  groups.value = groups.value.filter((g) => g.id !== id);
  persistGroups();
  persistContacts();
}
