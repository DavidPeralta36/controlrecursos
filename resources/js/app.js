/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import axios from 'axios';
import { createApp } from 'vue'; // Importar createApp desde Vue 3
import Popper from "vue3-popper";

// Configuración global de Axios para incluir credenciales y token CSRF
axios.defaults.withCredentials = true;
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Cargar los componentes de Vue (versión Vue 3)
import HomePage from './components/HomePage.vue'; // Importa tu componente

// Crear la instancia de la aplicación con Vue 3
const app = createApp({
    components: {
        'home-page': HomePage // Registra tu componente,
    }
});

app.use(Popper);

// Montar la aplicación a un elemento específico
app.mount('#app');
