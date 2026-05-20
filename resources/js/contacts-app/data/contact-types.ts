export interface Grupo {
    id: number;
    name: string;
    color: string;
}

export interface Contacto {
    id: number;
    name: string;
    surname: string;
    phone: string;
    email: string;
    city: string;
    groupId: number;
    createdAt?: string;
    favorite?: boolean;
}

export interface HistorialItem {
    id: number;
    contactId: number;
    contactName: string;
    date: string;
}

export interface ContactoFormData {
    name: string;
    surname: string;
    phone: string;
    email: string;
    city: string;
    groupId: number | null;
}

export interface GrupoFormData {
    name: string;
    color: string;
}