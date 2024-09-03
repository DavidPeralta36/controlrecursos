import axios from 'axios';
import { createApp } from 'vue'; 
import Popper from 'vue3-popper';
import '../sass/theme.css'
import Notifications from '@kyvg/vue3-notification'
import { OhVueIcon, addIcons } from "oh-vue-icons";
import { FaChartBar } from 'oh-vue-icons/icons';
import HomePage from './components/HomePage.vue'; 
import CargaPage from './components/CargaPage.vue';
import NavBar from './components/auxiliares/NavBar.vue';
import RegisterPage from './components/RegisterPage.vue';
import { createDynamicForms } from '@asigloo/vue-dynamic-forms';

const VueDinamicForms = createDynamicForms({

})

axios.defaults.withCredentials = true;
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

addIcons(FaChartBar);

const app = createApp({
    components: {
        'home-page': HomePage, 
        'carga-page': CargaPage, 
        'navbar': NavBar, 
        'register-page': RegisterPage,
    }
});

app.component('v-icon', OhVueIcon);
app.use(Popper);
app.use(Notifications);
app.use(VueDinamicForms);

app.mount('#app');
