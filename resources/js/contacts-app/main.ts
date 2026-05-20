import { createApp } from 'vue';
import ContactosPadelApp from './ContactosPadelApp.vue';
import '../../css/contacts-app.css';

const root = document.getElementById('contactos-padel-app');

if (root) {
    createApp(ContactosPadelApp).mount(root);
}
