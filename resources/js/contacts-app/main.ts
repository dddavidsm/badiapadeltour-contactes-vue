import { createApp } from 'vue';
import App from './App.vue';
import '../../css/contacts-app.css';

const root = document.getElementById('contactos-padel-app');

if (root) {
    createApp(App).mount(root);
}
