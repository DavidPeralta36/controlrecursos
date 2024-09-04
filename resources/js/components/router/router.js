// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import RegisterPage from '../RegisterPage.vue'; // ajusta la ruta según tu estructura de proyecto
import AddUser from '../management/AddUser.vue';
import UserManagement from '../management/UserManagement.vue';

const routes = [
  {
    path: '/users',
    name: 'Register',
    component: RegisterPage,
    children: [
        {
            path: '/users/add',
            name: 'AddUser',
            component: AddUser,
        },
        {
            path: '/users/management',
            name: 'UserManagement',
            component: UserManagement,
        }
    ]
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
