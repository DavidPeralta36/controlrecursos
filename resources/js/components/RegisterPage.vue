<template>
    <div class="w-100 h-100">
        <h3 style="margin-left: 20px; " class="rojo">Administracion de usuarios</h3>
        <nav class="navbar flex justify-content-start ">
            <router-link to="/users/add" class="nav-link formatted linkF" >NUEVO USUARIO</router-link>
            <router-link to="/users/management" class="nav-link formatted mx-2 linkF">USUARIOS EXISTENTES</router-link>
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

}

.navbar{
    padding-left: 20px;
}
.formatted{
    margin-top: -10px;
}

</style>