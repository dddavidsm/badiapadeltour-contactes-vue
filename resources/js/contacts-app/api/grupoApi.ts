import type { Grupo } from '../data/contact-types';
import request from './request';

export type GrupoPayload = Omit<Grupo, 'id'>;

export async function getGruposApi(): Promise<Grupo[]> {
  return request<Grupo[]>('/grupos');
}

export async function getGrupoByIdApi(id: number): Promise<Grupo> {
  return request<Grupo>(`/grupos/${id}`);
}

export async function createGrupoApi(payload: GrupoPayload): Promise<Grupo> {
  return request<Grupo>('/grupos', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload),
  });
}

export async function updateGrupoApi(id: number, payload: GrupoPayload): Promise<Grupo> {
  return request<Grupo>(`/grupos/${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ ...payload, id }),
  });
}

export async function deleteGrupoApi(id: number): Promise<void> {
  return request<void>(`/grupos/${id}`, {
    method: 'DELETE',
  });
}
