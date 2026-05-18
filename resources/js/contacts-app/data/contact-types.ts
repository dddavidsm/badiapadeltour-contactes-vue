export interface PadelContact {
    id: number;
    name: string;
    city: string;
    level: 'iniciacion' | 'intermedio' | 'avanzado';
    availability: 'mananas' | 'tardes' | 'noches' | 'fin-semana';
    phone: string;
}

export interface ContactFormData {
    name: string;
    city: string;
    level: PadelContact['level'];
    availability: PadelContact['availability'];
    phone: string;
}

export type ContactApiPayload = Omit<PadelContact, 'id'>;
