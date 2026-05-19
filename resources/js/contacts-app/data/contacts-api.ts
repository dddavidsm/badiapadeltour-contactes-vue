/**
 * contacts-api.ts
 *
 * Persistencia: localStorage en el navegador actual.
 * Fuente remota: JSONPlaceholder se usa como API externa para la carga inicial.
 * El CRUD real se guarda en localStorage para mantener los cambios tras recargar.
 */

import { DEFAULT_CONTACTS, DEFAULT_GROUPS } from './contact-types';
import type { Contact, Group } from './contact-types';

const FAKE_API = 'https://jsonplaceholder.typicode.com/users';

function storageKey(suffix: string): string {
    return `bpt_contacts_${suffix}`;
}

// ─── Groups ────────────────────────────────────────────────────────────────

export function loadGroups(): Group[] {
    const raw = localStorage.getItem(storageKey('groups'));
    if (raw) {
        return JSON.parse(raw) as Group[];
    }
    const defaults = [...DEFAULT_GROUPS];
    localStorage.setItem(storageKey('groups'), JSON.stringify(defaults));
    return defaults;
}

export function saveGroups(groups: Group[]): void {
    localStorage.setItem(storageKey('groups'), JSON.stringify(groups));
}

// ─── Contacts ──────────────────────────────────────────────────────────────

/**
 * La primera carga consulta JSONPlaceholder y mezcla esos datos con contactos por defecto.
 * Las siguientes cargas leen directamente de localStorage.
 */
export async function loadContacts(): Promise<Contact[]> {
    const raw = localStorage.getItem(storageKey('contacts'));
    if (raw) {
        return JSON.parse(raw) as Contact[];
    }

    // Primera visita: consulta API y normaliza al formato Contact
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
                createdAt: new Date(Date.now() - i * 3 * 24 * 3600 * 1000).toISOString(),
            }));
        }
    } catch {
        // Si falla la red, continúa con datos por defecto
    }

    const contacts = [...DEFAULT_CONTACTS, ...apiContacts];
    localStorage.setItem(storageKey('contacts'), JSON.stringify(contacts));
    return contacts;
}

export function saveContacts(contacts: Contact[]): void {
    localStorage.setItem(storageKey('contacts'), JSON.stringify(contacts));
}
