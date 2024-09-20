<template>
    <div>
        <button @click="deleteRecord" class="btn btn-danger btn-sm">Eliminar</button>
    </div>
</template>
<script setup>
import { defineProps, defineEmits } from 'vue';
import axios from 'axios';
import { notify } from '@kyvg/vue3-notification';

const props = defineProps(['params']);
const emit = defineEmits(['record-deleted']);

const deleteRecord = async () => {
    try {
        const response = await axios.delete(`/delete_programacion_rubro/${props.params.data.id}`, {
            headers: {
                'Content-Type': 'application/json',
            },
            params: {
                id: props.params.data.id
            }
        });
        
        if (response.status === 200) {
            notify({
                title: 'Partida programada eliminada exitosamente',
                text: 'La partida se ha eliminado correctamente',
                type: 'success',
                duration: 5000,
            });
            emit('record-deleted'); // Emitir evento después de la eliminación
        }
    } catch (e) {
        notify({
            title: 'Error al eliminar partida programada',
            text: 'Error: ' + e.response.data.message,
            type: 'error',
            duration: 5000,
        });
    }
};
</script>