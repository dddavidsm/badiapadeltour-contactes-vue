import { createApp } from 'vue';
import ContactosPadelApp from './ContactosPadelApp.vue';

const root = document.getElementById('contactos-padel-app');

if (root) {
    createApp(ContactosPadelApp, {
        userId:   root.dataset.userId   ?? '0',
        userName: root.dataset.userName ?? '',
    }).mount(root);
}
