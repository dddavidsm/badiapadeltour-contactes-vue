import type { HistorialItem } from '../data/contact-types';
import request from './request';

export type HistorialPayload = Omit<HistorialItem, 'id'>;

export async function getHistorialApi(): Promise<HistorialItem[]> {
  return request<HistorialItem[]>('/historial');
}

export async function getHistorialByIdApi(id: number): Promise<HistorialItem> {
  return request<HistorialItem>(`/historial/${id}`);
}

export async function createHistorialApi(payload: HistorialPayload): Promise<HistorialItem> {
  return request<HistorialItem>('/historial', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload),
  });
}

export async function updateHistorialApi(id: number, payload: HistorialPayload): Promise<HistorialItem> {
  return request<HistorialItem>(`/historial/${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ ...payload, id }),
  });
}

export async function deleteHistorialApi(id: number): Promise<void> {
  return request<void>(`/historial/${id}`, {
    method: 'DELETE',
  });
}
