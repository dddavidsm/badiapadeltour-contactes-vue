import type { ContactApiPayload, PadelContact } from './contact-types';

const API_URL = 'https://jsonplaceholder.typicode.com/users';

function normalizeLevel(index: number): PadelContact['level'] {
    const levels: PadelContact['level'][] = ['iniciacion', 'intermedio', 'avanzado'];
    return levels[index % levels.length];
}

function normalizeAvailability(index: number): PadelContact['availability'] {
    const slots: PadelContact['availability'][] = ['mananas', 'tardes', 'noches', 'fin-semana'];
    return slots[index % slots.length];
}

export async function getContacts(): Promise<PadelContact[]> {
    const response = await fetch(`${API_URL}?_limit=8`);
    if (!response.ok) {
        throw new Error('No se pudieron cargar los contactos.');
    }

    const users = (await response.json()) as Array<{
        id: number;
        name: string;
        phone?: string;
        address?: { city?: string };
    }>;

    return users.map((user, index) => ({
        id: user.id,
        name: user.name,
        city: user.address?.city ?? 'Barcelona',
        level: normalizeLevel(index),
        availability: normalizeAvailability(index),
        phone: user.phone ?? '600000000',
    }));
}

export async function createContact(payload: ContactApiPayload): Promise<PadelContact> {
    const response = await fetch(API_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
    });

    if (!response.ok) {
        throw new Error('No se pudo crear el contacto.');
    }

    const result = (await response.json()) as { id?: number };

    return {
        id: result.id ?? Date.now(),
        ...payload,
    };
}

export async function updateContact(id: number, payload: ContactApiPayload): Promise<PadelContact> {
    const response = await fetch(`${API_URL}/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
    });

    if (!response.ok) {
        throw new Error('No se pudo actualizar el contacto.');
    }

    return {
        id,
        ...payload,
    };
}

export async function deleteContact(id: number): Promise<void> {
    const response = await fetch(`${API_URL}/${id}`, {
        method: 'DELETE',
    });

    if (!response.ok) {
        throw new Error('No se pudo eliminar el contacto.');
    }
}
