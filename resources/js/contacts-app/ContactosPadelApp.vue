<template>
    <div class="contactos-shell">
        <section class="contactos-hero">
            <p class="kicker">Comunidad BPT</p>
            <h1>Contactos para buscar pareja de padel</h1>
            <p class="intro">Apartado sencillo para publicar disponibilidad, encontrar gente por nivel y gestionar tus contactos.</p>
        </section>

        <section class="contactos-grid">
            <article class="panel">
                <h2>{{ isEditing ? 'Editar contacto' : 'Nuevo contacto' }}</h2>
                <form @submit.prevent="submitForm" class="contact-form">
                    <label>
                        Nombre
                        <input v-model.trim="form.name" type="text" placeholder="Ej: Marta R." />
                    </label>

                    <label>
                        Ciudad
                        <input v-model.trim="form.city" type="text" placeholder="Ej: Sabadell" />
                    </label>

                    <label>
                        Nivel
                        <select v-model="form.level">
                            <option value="iniciacion">Iniciacion</option>
                            <option value="intermedio">Intermedio</option>
                            <option value="avanzado">Avanzado</option>
                        </select>
                    </label>

                    <label>
                        Disponibilidad
                        <select v-model="form.availability">
                            <option value="mananas">Mañanas</option>
                            <option value="tardes">Tardes</option>
                            <option value="noches">Noches</option>
                            <option value="fin-semana">Fin de semana</option>
                        </select>
                    </label>

                    <label>
                        Telefono
                        <input v-model.trim="form.phone" type="text" placeholder="Ej: 612345678" />
                    </label>

                    <p v-if="validationError" class="form-error">{{ validationError }}</p>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" :disabled="loading || !isFormValid">
                            {{ isEditing ? 'Guardar cambios' : 'Crear contacto' }}
                        </button>
                        <button v-if="isEditing" type="button" class="btn" @click="cancelEdit">Cancelar</button>
                    </div>
                </form>
            </article>

            <article class="panel">
                <h2>Buscar contactos</h2>
                <div class="filters">
                    <input v-model.trim="searchTerm" type="text" placeholder="Buscar por nombre o ciudad" />

                    <select v-model="selectedLevel">
                        <option value="all">Todos los niveles</option>
                        <option value="iniciacion">Iniciacion</option>
                        <option value="intermedio">Intermedio</option>
                        <option value="avanzado">Avanzado</option>
                    </select>

                    <select v-model="selectedAvailability">
                        <option value="all">Toda disponibilidad</option>
                        <option value="mananas">Mañanas</option>
                        <option value="tardes">Tardes</option>
                        <option value="noches">Noches</option>
                        <option value="fin-semana">Fin de semana</option>
                    </select>
                </div>

                <p class="results-info">{{ filteredContacts.length }} de {{ totalContacts }} contactos</p>

                <ul class="contact-list">
                    <li v-for="contact in filteredContacts" :key="contact.id" class="contact-item">
                        <div>
                            <h3>{{ contact.name }}</h3>
                            <p>{{ contact.city }} · {{ prettyLevel(contact.level) }}</p>
                            <small>{{ prettyAvailability(contact.availability) }} · {{ contact.phone }}</small>
                        </div>

                        <div class="item-actions">
                            <button class="btn" @click="startEdit(contact)">Editar</button>
                            <button class="btn btn-danger" @click="remove(contact.id)">Eliminar</button>
                        </div>
                    </li>
                </ul>

                <p v-if="!filteredContacts.length" class="empty-state">No hay resultados con los filtros actuales.</p>
            </article>
        </section>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { createContact, deleteContact, getContacts, updateContact } from './data/contacts-api';
import type { ContactFormData, PadelContact } from './data/contact-types';

type LevelFilter = PadelContact['level'] | 'all';
type AvailabilityFilter = PadelContact['availability'] | 'all';

function getDefaultForm(): ContactFormData {
    return {
        name: '',
        city: '',
        level: 'iniciacion',
        availability: 'tardes',
        phone: '',
    };
}

export default defineComponent({
    name: 'ContactosPadelApp',
    data() {
        return {
            contacts: [] as PadelContact[],
            form: getDefaultForm() as ContactFormData,
            editingId: null as number | null,
            searchTerm: '',
            selectedLevel: 'all' as LevelFilter,
            selectedAvailability: 'all' as AvailabilityFilter,
            loading: false,
            validationError: '',
        };
    },
    computed: {
        isEditing(): boolean {
            return this.editingId !== null;
        },
        totalContacts(): number {
            return this.contacts.length;
        },
        isFormValid(): boolean {
            const phonePattern = /^[0-9]{9}$/;
            return Boolean(this.form.name && this.form.city && phonePattern.test(this.form.phone));
        },
        filteredContacts(): PadelContact[] {
            const normalizedSearch = this.searchTerm.toLowerCase();

            return this.contacts.filter((contact) => {
                const matchesSearch =
                    contact.name.toLowerCase().includes(normalizedSearch) ||
                    contact.city.toLowerCase().includes(normalizedSearch);

                const matchesLevel = this.selectedLevel === 'all' || contact.level === this.selectedLevel;
                const matchesAvailability =
                    this.selectedAvailability === 'all' || contact.availability === this.selectedAvailability;

                return matchesSearch && matchesLevel && matchesAvailability;
            });
        },
    },
    methods: {
        async loadContacts() {
            this.loading = true;
            try {
                this.contacts = await getContacts();
            } catch (error) {
                this.validationError = error instanceof Error ? error.message : 'Error al cargar contactos.';
            } finally {
                this.loading = false;
            }
        },
        prettyLevel(level: PadelContact['level']): string {
            if (level === 'iniciacion') {
                return 'Iniciacion';
            }
            if (level === 'intermedio') {
                return 'Intermedio';
            }
            return 'Avanzado';
        },
        prettyAvailability(slot: PadelContact['availability']): string {
            if (slot === 'mananas') {
                return 'Mananas';
            }
            if (slot === 'tardes') {
                return 'Tardes';
            }
            if (slot === 'noches') {
                return 'Noches';
            }
            return 'Fin de semana';
        },
        validateForm(): boolean {
            if (!this.form.name || !this.form.city) {
                this.validationError = 'Nombre y ciudad son obligatorios.';
                return false;
            }

            if (!/^[0-9]{9}$/.test(this.form.phone)) {
                this.validationError = 'El telefono debe tener 9 digitos.';
                return false;
            }

            this.validationError = '';
            return true;
        },
        async submitForm() {
            if (!this.validateForm()) {
                return;
            }

            this.loading = true;
            try {
                if (this.editingId !== null) {
                    const updated = await updateContact(this.editingId, this.form);
                    this.contacts = this.contacts.map((contact) => (contact.id === updated.id ? updated : contact));
                } else {
                    const created = await createContact(this.form);
                    this.contacts = [{ ...created, id: this.nextContactId() }, ...this.contacts];
                }

                this.cancelEdit();
            } catch (error) {
                this.validationError = error instanceof Error ? error.message : 'No se pudo guardar el contacto.';
            } finally {
                this.loading = false;
            }
        },
        startEdit(contact: PadelContact) {
            this.editingId = contact.id;
            this.form = {
                name: contact.name,
                city: contact.city,
                level: contact.level,
                availability: contact.availability,
                phone: contact.phone,
            };
            this.validationError = '';
        },
        cancelEdit() {
            this.editingId = null;
            this.form = getDefaultForm();
            this.validationError = '';
        },
        async remove(id: number) {
            this.loading = true;
            try {
                await deleteContact(id);
                this.contacts = this.contacts.filter((contact) => contact.id !== id);
                if (this.editingId === id) {
                    this.cancelEdit();
                }
            } catch (error) {
                this.validationError = error instanceof Error ? error.message : 'No se pudo eliminar el contacto.';
            } finally {
                this.loading = false;
            }
        },
        nextContactId(): number {
            if (!this.contacts.length) {
                return 1;
            }
            return Math.max(...this.contacts.map((contact) => contact.id)) + 1;
        },
    },
    mounted() {
        this.loadContacts();
    },
});
</script>

<style scoped>
.contactos-shell {
    --bpt-electric: #c9ff00;
    --bpt-black: #0f0f0f;
    --bpt-panel: #1a1a1a;
    --bpt-text-soft: #b8b8b8;
    max-width: 1200px;
    margin: 0 auto;
    padding: 44px 48px 72px;
    color: #fff;
}

.contactos-hero {
    margin-bottom: 26px;
}

.kicker {
    margin: 0 0 8px;
    color: var(--bpt-electric);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-weight: 800;
}

h1 {
    margin: 0;
    font-size: clamp(2rem, 4vw, 2.8rem);
    line-height: 1.12;
}

.intro {
    margin: 12px 0 0;
    color: var(--bpt-text-soft);
    max-width: 760px;
}

.contactos-grid {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 20px;
}

.panel {
    background: var(--bpt-panel);
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    padding: 18px;
}

.panel h2 {
    margin-top: 0;
    font-size: 1.05rem;
}

.contact-form,
.filters {
    display: grid;
    gap: 10px;
}

label {
    display: grid;
    gap: 6px;
    font-size: 0.9rem;
    color: var(--bpt-text-soft);
}

input,
select {
    width: 100%;
    border: 1px solid #383838;
    border-radius: 8px;
    background: #111;
    color: #fff;
    padding: 10px;
    font-family: inherit;
}

.form-actions {
    margin-top: 8px;
    display: flex;
    gap: 10px;
}

.btn {
    border: 1px solid #4a4a4a;
    background: transparent;
    color: #fff;
    border-radius: 8px;
    padding: 8px 12px;
    cursor: pointer;
    font-weight: 700;
}

.btn-primary {
    border-color: var(--bpt-electric);
    background: var(--bpt-electric);
    color: #0f0f0f;
}

.btn-danger {
    border-color: #f15f5f;
    color: #ff9f9f;
}

.btn:disabled {
    opacity: 0.55;
    cursor: not-allowed;
}

.form-error {
    margin: 0;
    color: #ff9f9f;
    font-size: 0.88rem;
}

.results-info {
    color: var(--bpt-text-soft);
    font-size: 0.9rem;
}

.contact-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: grid;
    gap: 10px;
}

.contact-item {
    border: 1px solid #2f2f2f;
    border-radius: 10px;
    padding: 12px;
    display: flex;
    justify-content: space-between;
    gap: 10px;
    align-items: center;
    background: #121212;
}

.contact-item h3 {
    margin: 0 0 2px;
    font-size: 1rem;
}

.contact-item p,
.contact-item small {
    margin: 0;
    color: var(--bpt-text-soft);
}

.item-actions {
    display: flex;
    gap: 8px;
}

.empty-state {
    color: var(--bpt-text-soft);
    text-align: center;
    margin: 14px 0 0;
}

@media (max-width: 960px) {
    .contactos-shell {
        padding: 34px 24px 56px;
    }

    .contactos-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .contactos-shell {
        padding-inline: 16px;
    }

    .contact-item {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
