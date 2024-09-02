/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');
import axios from 'axios';
import { createApp } from 'vue'; // Importar createApp desde Vue 3
import Popper from 'vue3-popper';
import '../sass/theme.css'
import Notifications from '@kyvg/vue3-notification'
import { OhVueIcon, addIcons } from "oh-vue-icons";
import { FaChartBar } from 'oh-vue-icons/icons';
import HomePage from './components/HomePage.vue'; // Importa tu componente
import CargaPage from './components/CargaPage.vue';
import NavBar from './components/auxiliares/NavBar.vue';
import RegisterPage from './components/RegisterPage.vue';

axios.defaults.withCredentials = true;
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

addIcons(FaChartBar);


// Crear la instancia de la aplicación con Vue 3
const app = createApp({
    components: {
        'home-page': HomePage, // Registra tu componente,
        'carga-page': CargaPage, // Registra tu componente,
        'navbar': NavBar, // Registra tu componente,
        'register-page': RegisterPage, // Registra tu componente,
    }
});

app.component('v-icon', OhVueIcon);
app.use(Popper);
app.use(Notifications);

// Montar la aplicación a un elemento específico
app.mount('#app');
