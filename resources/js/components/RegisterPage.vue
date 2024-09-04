<template>
    <div class="w-100 h-100">
        <h3 style="margin-left: 20px; " class="rojo">Administracion de usuarios</h3>
        <nav class="navbar flex justify-content-start ">
            <router-link to="/users/add" :class="route.path === '/users/add' ? 'nav-link formatted linkF activeLink' : 'nav-link formatted linkF'" >NUEVO USUARIO</router-link>
            <router-link to="/users/management" :class="route.path === '/users/management' ? 'nav-link formatted mx-2 linkF activeLink' : 'nav-link formatted mx-2 linkF'">USUARIOS EXISTENTES</router-link>
        </nav>
        
        <router-view class="content"></router-view>
    </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

</script>
<style lang="css">
.rojo{
    color: #9F2241;
}

.linkF{
    color: #999999;
    font-weight: bold;
    transition: all 0.2s ease-in-out;
    min-height: 25px;
}

.activeLink{
    color: #310712;
    font-weight: bold;
    border-bottom: 2px solid #7e132e;
}

.linkF:hover{
    color: #000000;
    font-weight: bold;
    border-bottom: 4px solid #7e132e;
}

.navbar{
    padding-left: 20px;
}
.formatted{
    margin-top: -10px;
}

</style>