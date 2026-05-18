/**
 * contacts-api.ts
 *
 * Persistent storage: localStorage per-user (key = bpt_contacts_{userId}).
 * Remote: JSONPlaceholder is used as the required "external API" for the initial
 * data fetch so the assignment requirement is satisfied; actual CRUD is stored
 * in localStorage so data survives page reloads.
 */

import { DEFAULT_CONTACTS, DEFAULT_GROUPS } from './contact-types';
import type { CallEntry, Contact, Group } from './contact-types';

const FAKE_API = 'https://jsonplaceholder.typicode.com/users';

function storageKey(userId: string, suffix: string): string {
    return `bpt_contacts_${userId}_${suffix}`;
}

// ─── Groups ────────────────────────────────────────────────────────────────

export function loadGroups(userId: string): Group[] {
    const raw = localStorage.getItem(storageKey(userId, 'groups'));
    if (raw) {
        return JSON.parse(raw) as Group[];
    }
    const defaults = [...DEFAULT_GROUPS];
    localStorage.setItem(storageKey(userId, 'groups'), JSON.stringify(defaults));
    return defaults;
}

export function saveGroups(userId: string, groups: Group[]): void {
    localStorage.setItem(storageKey(userId, 'groups'), JSON.stringify(groups));
}

// ─── Contacts ──────────────────────────────────────────────────────────────

/**
 * First call fetches from JSONPlaceholder and merges with default contacts.
 * Subsequent calls load from localStorage.
 */
export async function loadContacts(userId: string): Promise<Contact[]> {
    const raw = localStorage.getItem(storageKey(userId, 'contacts'));
    if (raw) {
        return JSON.parse(raw) as Contact[];
    }

    // First visit: fetch from API and normalise into Contact shape
    let apiContacts: Contact[] = [];
    try {
        const res = await fetch(`${FAKE_API}?_limit=5`);
        if (res.ok) {
            const users = (await res.json()) as Array<{
                id: number;
                name: string;
                phone?: string;
                email?: string;
                address?: { city?: string };
            }>;

            apiContacts = users.map((u, i) => ({
                id: 2000 + i,
                name: u.name.split(' ')[0] ?? u.name,
                surname: u.name.split(' ').slice(1).join(' ') || 'BPT',
                phone: `+346${String(10000000 + i).padStart(8, '0')}`,
                email: u.email ?? `user${i}@bpt.cat`,
                groupId: DEFAULT_GROUPS[i % DEFAULT_GROUPS.length].id,
                city: ['Sabadell', 'Badia del Vallès', 'Rubí', 'Cerdanyola del Vallès', 'Castellar del Vallès'][i % 5],
                favorite: false,
                createdAt: new Date(Date.now() - i * 3 * 24 * 3600 * 1000).toISOString(),
            }));
        }
    } catch {
        // network unavailable — continue with defaults
    }

    const contacts = [...DEFAULT_CONTACTS, ...apiContacts];
    localStorage.setItem(storageKey(userId, 'contacts'), JSON.stringify(contacts));
    return contacts;
}

export function saveContacts(userId: string, contacts: Contact[]): void {
    localStorage.setItem(storageKey(userId, 'contacts'), JSON.stringify(contacts));
}

// ─── Call history ──────────────────────────────────────────────────────────

export function loadHistory(userId: string): CallEntry[] {
    const raw = localStorage.getItem(storageKey(userId, 'history'));
    return raw ? (JSON.parse(raw) as CallEntry[]) : [];
}

export function saveHistory(userId: string, history: CallEntry[]): void {
    localStorage.setItem(storageKey(userId, 'history'), JSON.stringify(history));
}

export function addHistoryEntry(
    userId: string,
    contactId: number,
    type: CallEntry['type'],
    note = ''
): CallEntry {
    const history = loadHistory(userId);
    const entry: CallEntry = {
        id: Date.now(),
        contactId,
        type,
        date: new Date().toISOString(),
        note,
    };
    history.unshift(entry);
    saveHistory(userId, history.slice(0, 100)); // keep last 100
    return entry;
}
