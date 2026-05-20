import type { Grupo } from '../data/contact-types';
import request from './api';

export type GroupPayload = Omit<Grupo, 'id'>;

export const GrupoService = {
  async getGroups(): Promise<Grupo[]> {
    return request<Grupo[]>('/grupos');
  },

  async getGroupById(id: number): Promise<Grupo> {
    return request<Grupo>(`/grupos/${id}`);
  },

  async createGroup(payload: GroupPayload): Promise<Grupo> {
    return request<Grupo>('/grupos', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });
  },

  async updateGroup(id: number, payload: GroupPayload): Promise<Grupo> {
    return request<Grupo>(`/grupos/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ ...payload, id }),
    });
  },

  async deleteGroup(id: number): Promise<void> {
    return request<void>(`/grupos/${id}`, {
      method: 'DELETE',
    });
  },
};
