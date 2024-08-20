<template >
    <div class="h-100 w-100 ">
        {{message}}
        <p>Usuarios desde el componente de vue</p>
        <button :class="disabled ? 'btn btn-danger' : 'btn btn-primary'" @click="getUsers" >Consultar usuarios</button>
        <ul>
            <li v-for="user in users" :key="user.id">{{ user.name }}</li>
        </ul>
    </div>
</template>
<script setup>
import { ref } from 'vue'
import axios from 'axios'

const message = ref('Hello World!')
const users = ref([])
const disabled = ref(false)

const getUsers = async () => {
    try {
        alert('/user')
        disabled.value = true
        message.value = 'Cargando usuarios...'

        // Hacer la solicitud GET a la ruta de la API
        const response = await axios.get('/user')

        // Asignar los datos de los usuarios a la variable `users`
        users.value = response.data
        console.log(users.value)
    } catch (error) {
        console.error('Error al cargar los usuarios:', error)
    } finally {
        disabled.value = false
        message.value = 'Listo'
    }
}


</script>
<style >
    
</style>