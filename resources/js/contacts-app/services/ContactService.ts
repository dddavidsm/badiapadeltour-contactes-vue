import type { Contacto } from '../data/contact-types';
import request from './api';

export type ContactPayload = Omit<Contacto, 'id' | 'createdAt'>;

export const ContactService = {
  async getContacts(): Promise<Contacto[]> {
    return request<Contacto[]>('/contactos');
  },

  async getContactById(id: number): Promise<Contacto> {
    return request<Contacto>(`/contactos/${id}`);
  },

  async createContact(payload: ContactPayload): Promise<Contacto> {
    const newContact = {
      ...payload,
      createdAt: new Date().toISOString(),
    };

    return request<Contacto>('/contactos', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(newContact),
    });
  },

  async updateContact(id: number, payload: ContactPayload): Promise<Contacto> {
    return request<Contacto>(`/contactos/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ ...payload, id }),
    });
  },

  async deleteContact(id: number): Promise<void> {
    return request<void>(`/contactos/${id}`, {
      method: 'DELETE',
    });
  },
};
