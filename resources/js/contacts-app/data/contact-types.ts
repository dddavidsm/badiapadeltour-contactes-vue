export type GroupName = string;

export interface Group {
    id: number;
    name: string;
    color: string;
}

export interface Contact {
    id: number;
    name: string;
    surname: string;
    phone: string;
    email: string;
    groupId: number;
    city: string;
    favorite: boolean;
    createdAt: string; // ISO date string
}

export interface CallEntry {
    id: number;
    contactId: number;
    type: 'call' | 'message';
    date: string; // ISO date string
    note: string;
}

export interface ContactFormData {
    name: string;
    surname: string;
    phone: string;
    email: string;
    groupId: number | null;
    city: string;
}

export interface GroupFormData {
    name: string;
    color: string;
}

export const PADEL_CITIES = [
    'Badia del Vallès',
    'Sabadell',
    'Cerdanyola del Vallès',
    'Castellar del Vallès',
    'Castellbisbal',
    'Rubí',
    'Barberà del Vallès',
    'Ripollet',
    'Sant Quirze del Vallès',
    'Santa Perpètua de Mogoda',
];

export const DEFAULT_GROUPS: Group[] = [
    { id: 1, name: 'Companys', color: '#c9ff00' },
    { id: 2, name: 'Rivals', color: '#ff6b6b' },
    { id: 3, name: 'Entrenadors', color: '#4fc3f7' },
    { id: 4, name: 'Organitzadors', color: '#ce93d8' },
];

export const DEFAULT_CONTACTS: Contact[] = [
    {
        id: 1001, name: 'Marc', surname: 'Puig', phone: '+34612345678', email: 'marc.puig@bpt.cat',
        groupId: 1, city: 'Sabadell', favorite: true,
        createdAt: new Date(Date.now() - 2 * 24 * 3600 * 1000).toISOString(),
    },
    {
        id: 1002, name: 'Laia', surname: 'Ferrer', phone: '+34623456789', email: 'laia.ferrer@bpt.cat',
        groupId: 1, city: 'Badia del Vallès', favorite: false,
        createdAt: new Date(Date.now() - 5 * 24 * 3600 * 1000).toISOString(),
    },
    {
        id: 1003, name: 'Jordi', surname: 'Roca', phone: '+34634567890', email: 'jordi.roca@bpt.cat',
        groupId: 2, city: 'Rubí', favorite: false,
        createdAt: new Date(Date.now() - 10 * 24 * 3600 * 1000).toISOString(),
    },
    {
        id: 1004, name: 'Núria', surname: 'Vila', phone: '+34645678901', email: 'nuria.vila@bpt.cat',
        groupId: 3, city: 'Cerdanyola del Vallès', favorite: true,
        createdAt: new Date(Date.now() - 1 * 24 * 3600 * 1000).toISOString(),
    },
    {
        id: 1005, name: 'Pau', surname: 'Mas', phone: '+34656789012', email: 'pau.mas@bpt.cat',
        groupId: 4, city: 'Castellar del Vallès', favorite: false,
        createdAt: new Date(Date.now() - 20 * 24 * 3600 * 1000).toISOString(),
    },
];
