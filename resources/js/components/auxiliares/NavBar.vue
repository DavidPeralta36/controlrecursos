<template lang="html">
    <nav class="navbar navbar-expand-lg fixed-top bg-body-tertiary shadow-sm">
        <div class="container-fluid">
            <a href="/home" class="ml">
                <img src="../../../../public/images/LOGO2021.png" alt="logo" class="img-fluid" style="width:180px; height: 50px;" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse flex justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav "  v-if="props.user">
                    <li class="nav-item hov" :class="route.path === '/home' ? 'borderItem' : ''" v-if="props.user.role !== 'almacen' && props.user.role !== 'obras'">
                        <a class="nav-link" :class="route.path === '/home' ? 'activeLink' : ''" aria-current="page" href="/home">Consulta</a>
                    </li>
                    <li class="nav-item hov" :class="route.path === '/carga' ? 'borderItem' : ''" v-if="props.user.role === 'proyectos'">
                        <a class="nav-link" href="/carga" :class="route.path === '/carga' ? 'activeLink' : ''">Carga</a>
                    </li>
                    <li class="nav-item hov" :class="route.path === '/contratos' ? 'borderItem' : '' " v-if="props.user.role === 'almacen' || props.user.role === 'obras' || props.user.role === 'proyectos'">
                        <a class="nav-link" href="/contratos" :class="route.path === '/llenado' ? 'activeLink' : ''">Contratos</a>
                    </li>
                </ul>

                <!-- Aquí empieza la lógica de autenticación -->
                <div v-if="props.user">
                    <!-- Usuario autenticado -->
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown mr">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ props.user.name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li v-if="props.user.role === 'proyectos'"><a class="dropdown-item" href="/programacion">Programacion de recursos</a></li>
                                <li v-if="props.user.role === 'proyectos'"><a class="dropdown-item" href="/catalogos">Administrar catalogos</a></li>
                                <li v-if="props.user.role === 'proyectos'"><a class="dropdown-item" href="/users/add">Administrar usuarios</a></li>
                                <li>
                                    <a class="dropdown-item" href="#" @click="logout">Cerrar sesión</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div v-else>
                    <!-- Usuario no autenticado -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" :class="{ 'disabled': currentRoute === 'login' }" href="/login">Entrar</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <slot></slot>
</template>

<script setup>
import { ref, defineProps } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const props = defineProps({
    user: Object,
});

const currentRoute = window.location.pathname;

function logout() {
    document.getElementById('logout-form').submit();
}

</script>

<style lang="css" scoped>
.mr {
    margin-right: 150px;
}
.ml {
    margin-left: 150px;
}

@media (max-width: 990px) {
    .ml {
        margin-left: 0px;
    }
    
    .mr {
        margin-right: 0px;
    }
}

.hov{
    transition: all 0.3s ease-in-out;
}
.hov:hover{
    color: #9F2241;
}

.borderItem{
    border-bottom: 2px solid #9F2241;
}
.activeLink{
    color: #9F2241;
}
</style>
