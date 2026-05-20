import type { HistorialItem } from '../data/contact-types';
import {
  createHistorialApi,
  deleteHistorialApi,
  getHistorialApi,
  getHistorialByIdApi,
  updateHistorialApi,
  type HistorialPayload,
} from '../api/historialApi';

export type { HistorialPayload } from '../api/historialApi';

export const HistorialService = {
  async getHistorial(): Promise<HistorialItem[]> {
    try {
      return await getHistorialApi();
    } catch (error) {
      console.error(error);
      throw error;
    }
  },

  async getHistorialById(id: number): Promise<HistorialItem> {
    try {
      return await getHistorialByIdApi(id);
    } catch (error) {
      console.error(error);
      throw error;
    }
  },

  async createHistorial(payload: HistorialPayload): Promise<HistorialItem> {
    try {
      return await createHistorialApi(payload);
    } catch (error) {
      console.error(error);
      throw error;
    }
  },

  async updateHistorial(id: number, payload: HistorialPayload): Promise<HistorialItem> {
    try {
      return await updateHistorialApi(id, payload);
    } catch (error) {
      console.error(error);
      throw error;
    }
  },

  async deleteHistorial(id: number): Promise<void> {
    try {
      await deleteHistorialApi(id);
    } catch (error) {
      console.error(error);
      throw error;
    }
  },
};
