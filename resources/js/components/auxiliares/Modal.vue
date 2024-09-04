<template lang="html">
    <div class="modal fade" :id="props.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ props.title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="submitForm">
                        <div>
                            <label :for="`${formIdPrefix}-form`" class="form-label">Nombre</label>
                            <input type="text" class="form-control" :readonly="formIdPrefix === 'delete' ? true : false" :id="`${formIdPrefix}-form`" v-model="formData.name" required>
                        </div>
                        <div>
                            <label :for="`${formIdPrefix}-email`" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" :readonly="formIdPrefix === 'delete' ? true : false" :id="`${formIdPrefix}-email`" v-model="formData.email" required>
                        </div>
                        <div>
                            <label :for="`${formIdPrefix}-password`" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" :readonly="formIdPrefix === 'delete' ? true : false" :id="`${formIdPrefix}-password`" v-model="formData.password">
                        </div>
                        <div>
                            <label :for="`${formIdPrefix}-password_confirmation`" class="form-label">Confirmar contraseña</label>
                            <input type="password" class="form-control" :readonly="formIdPrefix === 'delete' ? true : false" :id="`${formIdPrefix}-password_confirmation`" v-model="formData.password_confirmation">
                        </div>
                        <div>
                            <label :for="`${formIdPrefix}-role`" class="form-label">Rol</label>
                            <select class="form-select" :disabled="formIdPrefix === 'delete' ? true : false"  :id="`${formIdPrefix}-role`" v-model="formData.role" required>
                                <option value="proyectos">Proyectos especiales</option>
                                <option value="almacen">Almacén</option>
                                <option value="obras">Subdirección de obras</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="formIdPrefix === 'edit' ? submitForm() : deleteUser()">{{ formIdPrefix === 'delete' ? 'Eliminar' : 'Guardar' }}</button>
                </div>
            </div>
        </div>
        <Notifications position="bottom left" />
    </div>
</template>

<script setup>
import { ref, defineProps, watch } from 'vue';
import axios from 'axios';
import { Notifications, notify } from '@kyvg/vue3-notification';
import { readonly } from 'vue';

const props = defineProps({
    id: String,
    title: String,
    user: Object,
    getUsers: Function,
});

const formIdPrefix = props.id === 'editModal' ? 'edit' : 'delete';

const formData = ref({});

watch(props, (newValue) => {
    formData.value = { ...newValue.user };  // Asegurarte de clonar el objeto para no modificar directamente el prop
});

const submitForm = async () => {
    try {
        const response = await axios.put(`/updateuser/${props.user.id}`, formData.value);
        console.log('User updated successfully', response.data);
        // Aquí podrías agregar lógica adicional, como cerrar el modal o actualizar una lista de usuarios.
        notify({
            title: 'Éxito',
            text: 'El usuario se ha actualizado correctamente',
            type: 'success',
            duration: 5000,
        });
        props.getUsers();
        closeModal()
    } catch (error) {
        console.error('Error updating user:', error);
        notify({
            title: 'Error',
            text: 'No se puedo actualizar el usuario',
            type: 'error',
            duration: 5000,
        });
    }
};

const deleteUser = async () => {
    try {
        const response = await axios.delete(`/deleteuser/${props.user.id}`);
        console.log('User deleted successfully', response.data);
        // Aquí podrías agregar lógica adicional, como cerrar el modal o actualizar una lista de usuarios.
        notify({
            title: 'Éxito',
            text: 'El usuario se ha actualizado correctamente',
            type: 'success',
            duration: 5000,
        });
        props.getUsers();
        //close the modal
        closeModal()
    } catch (error) {
        console.error('Error updating user:', error);
        notify({
            title: 'Error',
            text: 'No se puedo actualizar el usuario',
            type: 'error',
            duration: 5000,
        });
    }
}

const closeModal = () => {
    const modalElement = document.getElementById(props.id);
    console.log(modalElement);
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    console.log(modalInstance);
    modalInstance.hide();
};

</script>

<style lang="css" scoped>
/* Tu estilo aquí */
</style>
