// Exportamos un tipo básico para el nombre del grupo
export type GroupName = string;

// INTERFAZ GROUP: Define la estructura exacta que debe tener un Grupo en la app
export interface Group {
    id: number;
    name: string;
    color: string;
}

// INTERFAZ CONTACT: Define cómo se guarda un contacto internamente en la lista general
export interface Contact {
    id: number;
    name: string;
    surname: string;
    phone: string;
    email: string;
    groupId: number; // Relación con el ID del grupo al que pertenece
    city: string;
    createdAt: string; // ISO date string (ej. "2026-05-20T13:43:00.000Z")
}

// INTERFACES PARA FORMULARIOS: No tienen 'id' ni 'createdAt' porque se están creando en el momento
export interface ContactFormData {
    name: string;
    surname: string;
    phone: string;
    email: string;
    groupId: number | null; // null al inicio porque no se ha seleccionado nada
    city: string;
}

export interface GroupFormData {
    name: string;
    color: string;
}

// DATOS ESTÁTICOS: Ciudades de la zona para reutilizar en selects sin repetir código
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

// DATOS POR DEFECTO: Lo que se carga si la aplicación está vacía (Grupos)
export const DEFAULT_GROUPS: Group[] = [
    { id: 1, name: 'Compañeros', color: '#c9ff00' },
    { id: 2, name: 'Rivales', color: '#ff6b6b' },
    { id: 3, name: 'Entrenadores', color: '#4fc3f7' },
    { id: 4, name: 'Organizadores', color: '#ce93d8' },
];

// DATOS POR DEFECTO: Contactos semilla para que la app nunca se vea vacía la primera vez
export const DEFAULT_CONTACTS: Contact[] = [
    {
        id: 1001, name: 'Marc', surname: 'Puig', phone: '+34612345678', email: 'marc.puig@bpt.cat',
        groupId: 1, city: 'Sabadell',
        createdAt: new Date(Date.now() - 2 * 24 * 3600 * 1000).toISOString(),
    },
    {
        id: 1002, name: 'Laia', surname: 'Ferrer', phone: '+34623456789', email: 'laia.ferrer@bpt.cat',
        groupId: 1, city: 'Badia del Vallès',
        createdAt: new Date(Date.now() - 5 * 24 * 3600 * 1000).toISOString(),
    },
    {
        id: 1003, name: 'Jordi', surname: 'Roca', phone: '+34634567890', email: 'jordi.roca@bpt.cat',
        groupId: 2, city: 'Rubí',
        createdAt: new Date(Date.now() - 10 * 24 * 3600 * 1000).toISOString(),
    },
    {
        id: 1004, name: 'Núria', surname: 'Vila', phone: '+34645678901', email: 'nuria.vila@bpt.cat',
        groupId: 3, city: 'Cerdanyola del Vallès',
        createdAt: new Date(Date.now() - 1 * 24 * 3600 * 1000).toISOString(),
    },
    {
        id: 1005, name: 'Pau', surname: 'Mas', phone: '+34656789012', email: 'pau.mas@bpt.cat',
        groupId: 4, city: 'Castellar del Vallès',
        createdAt: new Date(Date.now() - 20 * 24 * 3600 * 1000).toISOString(),
    },
];