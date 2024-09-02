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
                <ul class="navbar-nav"  v-if="props.user">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/home">Consulta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/carga">Carga</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/llenado">Llenado</a>
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
                                <li><a class="dropdown-item" href="#">Administrar usuarios</a></li>
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
                        <li class="nav-item">
                            <a class="nav-link" :class="{ 'disabled': currentRoute === 'register' }" href="/register">Registrarse</a>
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
    margin-right: 100px;
}
.ml {
    margin-left: 100px;
}
</style>
