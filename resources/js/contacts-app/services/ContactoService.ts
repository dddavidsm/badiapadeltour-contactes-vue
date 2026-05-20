import type { Contacto, Grupo } from '../data/contact-types';
import {
  createContactoApi,
  deleteContactoApi,
  getContactoByIdApi,
  getContactosApi,
  updateContactoApi,
  type ContactoPayload,
} from '../api/contactApi';
import { HistorialService } from './HistorialService';
import { GrupoService } from './GrupoService';

export type { ContactoPayload } from '../api/contactApi';

export const ContactoService = {
  async getContactosViewData(): Promise<{ contactos: Contacto[]; grupos: Grupo[] }> {
    try {
      const [contactos, grupos] = await Promise.all([
        getContactosApi(),
        GrupoService.getGrupos(),
      ]);

      return { contactos, grupos };
    } catch (error) {
      console.error(error);
      throw error;
    }
  },

  async saveContacto(payload: ContactoPayload, id?: number): Promise<Contacto> {
    try {
      const contacto = id
        ? await updateContactoApi(id, payload)
        : await createContactoApi(payload);

      await HistorialService.createHistorial({
        contactId: contacto.id,
        contactName: `${contacto.name} ${contacto.surname}`,
        date: new Date().toISOString(),
      });

      return contacto;
    } catch (error) {
      console.error(error);
      throw error;
    }
  },

  async removeContacto(id: number): Promise<void> {
    try {
      const contacto = await getContactoByIdApi(id);
      await deleteContactoApi(id);

      await HistorialService.createHistorial({
        contactId: contacto.id,
        contactName: `${contacto.name} ${contacto.surname}`,
        date: new Date().toISOString(),
      });
    } catch (error) {
      console.error(error);
      throw error;
    }
  },
};
