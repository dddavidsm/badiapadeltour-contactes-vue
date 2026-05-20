import type { Grupo } from '../data/contact-types';
import {
  createGrupoApi,
  deleteGrupoApi,
  getGrupoByIdApi,
  getGruposApi,
  updateGrupoApi,
  type GrupoPayload,
} from '../api/grupoApi';

export type { GrupoPayload } from '../api/grupoApi';

export const GrupoService = {
  async getGrupos(): Promise<Grupo[]> {
    try {
      return await getGruposApi();
    } catch (error) {
      console.error(error);
      throw error;
    }
  },

  async getGrupoById(id: number): Promise<Grupo> {
    try {
      return await getGrupoByIdApi(id);
    } catch (error) {
      console.error(error);
      throw error;
    }
  },

  async saveGrupo(payload: GrupoPayload, id?: number): Promise<Grupo> {
    try {
      return id
        ? await updateGrupoApi(id, payload)
        : await createGrupoApi(payload);
    } catch (error) {
      console.error(error);
      throw error;
    }
  },

  async removeGrupo(id: number): Promise<void> {
    try {
      await deleteGrupoApi(id);
    } catch (error) {
      console.error(error);
      throw error;
    }
  },
};
