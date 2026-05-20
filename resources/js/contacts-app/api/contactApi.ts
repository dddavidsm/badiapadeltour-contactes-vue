import type { Contacto } from '../data/contact-types';
import request from './request';

export type ContactoPayload = Omit<Contacto, 'id' | 'createdAt' | 'favorite'>;

export async function getContactosApi(): Promise<Contacto[]> {
  return request<Contacto[]>('/contactos');
}

export async function getContactoByIdApi(id: number): Promise<Contacto> {
  return request<Contacto>(`/contactos/${id}`);
}

export async function createContactoApi(payload: ContactoPayload): Promise<Contacto> {
  const nuevoContacto = {
    ...payload,
    createdAt: new Date().toISOString(),
  };

  return request<Contacto>('/contactos', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(nuevoContacto),
  });
}

export async function updateContactoApi(id: number, payload: ContactoPayload): Promise<Contacto> {
  return request<Contacto>(`/contactos/${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ ...payload, id, createdAt: new Date().toISOString() }),
  });
}

export async function deleteContactoApi(id: number): Promise<void> {
  return request<void>(`/contactos/${id}`, {
    method: 'DELETE',
  });
}
