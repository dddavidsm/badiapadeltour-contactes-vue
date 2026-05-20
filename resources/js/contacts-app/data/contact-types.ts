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
    city: string;
    groupId: number;
    createdAt: string;
    favorite?: boolean;
}

export interface ContactFormData {
    name: string;
    surname: string;
    phone: string;
    email: string;
    city: string;
    groupId: number | null;
}

export interface GroupFormData {
    name: string;
    color: string;
}

export const PADEL_CITIES = [
    'Badia del Valles',
    'Sabadell',
    'Cerdanyola del Valles',
    'Castellar del Valles',
    'Rubi',
    'Barbera del Valles',
    'Ripollet',
    'Sant Quirze del Valles',
    'Santa Perpetua de Mogoda',
];

export const DEFAULT_GROUPS: Group[] = [
    { id: 1, name: 'Companeros', color: '#c9ff00' },
    { id: 2, name: 'Rivales', color: '#ff6b6b' },
    { id: 3, name: 'Entrenadores', color: '#4fc3f7' },
    { id: 4, name: 'Organizadores', color: '#ce93d8' },
];

export const DEFAULT_CONTACTS: Contact[] = [
    {
        id: 1001,
        name: 'Marc',
        surname: 'Puig',
        phone: '612345678',
        email: 'marc.puig@bpt.cat',
        city: 'Sabadell',
        groupId: 1,
        createdAt: new Date(Date.now() - 2 * 24 * 3600 * 1000).toISOString(),
    },
    {
        id: 1002,
        name: 'Laia',
        surname: 'Ferrer',
        phone: '623456789',
        email: 'laia.ferrer@bpt.cat',
        city: 'Badia del Valles',
        groupId: 1,
        createdAt: new Date(Date.now() - 5 * 24 * 3600 * 1000).toISOString(),
    },
    {
        id: 1003,
        name: 'Jordi',
        surname: 'Roca',
        phone: '634567890',
        email: 'jordi.roca@bpt.cat',
        city: 'Rubi',
        groupId: 2,
        createdAt: new Date(Date.now() - 10 * 24 * 3600 * 1000).toISOString(),
    },
];