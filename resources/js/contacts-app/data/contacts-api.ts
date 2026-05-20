/**
 * contacts-api.ts
 *
 * Persistencia: localStorage en el navegador actual.
 * Fuente remota: JSONPlaceholder se usa como API externa para la carga inicial.
 * El CRUD real se guarda en localStorage para mantener los cambios tras recargar.
 */

import { DEFAULT_CONTACTS, DEFAULT_GROUPS } from './contact-types';
import type { Contact, Group, CallRecord } from './contact-types';

const FAKE_API = 'https://jsonplaceholder.typicode.com/users';

// HELPER: Para generar la clave de localStorage siempre igual y no equivocarse
function storageKey(suffix: string): string {
    return `bpt_contacts_${suffix}`;
}

// ─── Groups ─────────────────────

export function loadGroups(): Group[] {
    // 1. Intenta leer grupos guardados anteriormente en el navegador
    const raw = localStorage.getItem(storageKey('groups'));
    if (raw) {
        return JSON.parse(raw) as Group[];
    }
    // 2. Si no hay nada, carga los grupos por defecto (Compañeros, Rivales...) y los guarda
    const defaults = [...DEFAULT_GROUPS];
    localStorage.setItem(storageKey('groups'), JSON.stringify(defaults));
    return defaults;
}

export function saveGroups(groups: Group[]): void {
    // Sobrescribe el almacenamiento local con el nuevo array de grupos
    localStorage.setItem(storageKey('groups'), JSON.stringify(groups));
}

// ─── Contacts ──────────────────────────────────────────────────────────────

/**
 * La primera carga consulta JSONPlaceholder y mezcla esos datos con contactos por defecto.
 * Las siguientes cargas leen directamente de localStorage.
 */
export async function loadContacts(): Promise<Contact[]> {
    // 1. Intenta cargar los contactos ya guardados
    const raw = localStorage.getItem(storageKey('contacts'));
    if (raw) {
        return JSON.parse(raw) as Contact[];
    }

    // Primera visita: consulta API y normaliza al formato Contact
    let apiContacts: Contact[] = [];
    try {
        const res = await fetch(`${FAKE_API}?_limit=5`); // Pide 5 usuarios de prueba
        if (res.ok) {
            const users = (await res.json()) as Array<{
                id: number; name: string; phone?: string; email?: string; address?: { city?: string };
            }>;

            // Transforma la respuesta de la API para que encaje en el molde (interfaz Contact) de nuestra app
            apiContacts = users.map((u, i) => ({
                id: 2000 + i,
                name: u.name.split(' ')[0] ?? u.name,
                surname: u.name.split(' ').slice(1).join(' ') || 'BPT',
                phone: `+346${String(10000000 + i).padStart(8, '0')}`, // Genera un teléfono válido +346...
                email: u.email ?? `user${i}@bpt.cat`,
                groupId: DEFAULT_GROUPS[i % DEFAULT_GROUPS.length].id, // Reparte en diferentes grupos
                city: ['Sabadell', 'Badia del Vallès', 'Rubí', 'Cerdanyola del Vallès', 'Castellar del Vallès'][i % 5],
                createdAt: new Date(Date.now() - i * 3 * 24 * 3600 * 1000).toISOString(), // Fechas retroactivas
            }));
        }
    } catch {
        // Si falla la red (sin internet), ignora el error silenciosamente. apiContacts se queda vacío [].
    }

    // Une los contactos por defecto con los que acaban de llegar de la API (si hubo)
    const contacts = [...DEFAULT_CONTACTS, ...apiContacts];
    // Los guarda para futuras visitas
    localStorage.setItem(storageKey('contacts'), JSON.stringify(contacts));
    return contacts;
}

export function saveContacts(contacts: Contact[]): void {
    // Sobrescribe el almacenamiento local con el array de contactos completo (se llama tras editar/borrar/añadir)
    localStorage.setItem(storageKey('contacts'), JSON.stringify(contacts));
}

// ─── Call History ──────────────────────────────────────────────────────────────

export function loadCallHistory(): CallRecord[] {
    // Lee el historial guardado; si no existe aún, devuelve un array vacío
    const raw = localStorage.getItem(storageKey('calls'));
    return raw ? JSON.parse(raw) as CallRecord[] : [];
}

export function saveCallHistory(calls: CallRecord[]): void {
    // Sobrescribe el historial completo (se llama tras añadir o limpiar entradas)
    localStorage.setItem(storageKey('calls'), JSON.stringify(calls));
}