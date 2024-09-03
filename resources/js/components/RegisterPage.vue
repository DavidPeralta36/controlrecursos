<template>
    <div class="w-100 h-100">
        <h3 style="margin-left: 20px;">Registro de usuarios para el acceso al sistema</h3>
        <nav class="navbar flex justify-content-start">
        <router-link to="/users/add" class="nav-link">Nuevo usuario</router-link>
        </nav>
        
        <router-view class="content"></router-view>
        <Notifications position="bottom left" />
    </div>
</template>
<script setup>
import { ref } from 'vue';
import { Notifications, notify } from '@kyvg/vue3-notification';
import axios from 'axios';

const formData = ref({})



const handleSubmit = async () => {
    try{
        const formDt = JSON.stringify(formData);

        const response = await axios.post('/register', formDt, {
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if(response.status === 200){
            notify({
                title:"Registro exitoso",
                text: "El usuario se ha registrado correctamente",
                type: "success",
                duration: 5000,
            })
        }

    }catch(e){
        notify({
            title:"Error",
            text: "Se encontro un error al enviar el formulario: "+e,
            type: "error",
            duration: 5000,
        })
    }
}

const handleError = (errors) => {
    notify({
        title:"Error, no se ha enviado el formulario",
        text: "No se ha validado correctamente el formulario, revise los campos",
        type: "error",
        duration: 5000,
    })
}

const handleChange = (data) => {
    console.log(data);
    formData.value = data;
}

</script>
<style lang="css">
.navbar{
    padding-left: 20px;

}
</style>