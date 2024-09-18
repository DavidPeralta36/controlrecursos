<template lang="html">
    <div class="container w-100 h-100 con">
        <header>
            <h1 class="h3 text-center">Programacion de recursos por partida</h1>
            <p class="text-center">Ingrese los datos necesarios para programar recurso disponible durante el ejercicio seleccionado</p> 
        </header>
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link" :class="activeTab === 'new' ? 'active' : ''" aria-current="page" href="#" @click="handleTabClick('new')">Nuevo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="activeTab === 'modify' ? 'active' : '' " aria-current="page" href="#" @click="handleTabClick('modify')">Modificar</a>
            </li>
        </ul>
        <main>
            <div class="container" v-if="activeTab === 'new'">
                <source-picker :fuentes="fuentes" :handleSelect="handleSelect"/>
                <section class="mt-2">
                    <p class="h5 ">Ingrese el ejercicio</p>
                    <input type="text" class="form-control" placeholder="Ejercicio" aria-label="Ejercicio" v-model="ejercicio" required>
                </section>
                <section class="mt-2">
                    <p>Partida</p>
                    <VueSelect :options="partidasFormateadas" label="label" class="mt-1" v-model="partidaSeleccionada"/>
                    <p>Monto a programar</p>
                    <input type="number" class="form-control" placeholder="Monto a programar" aria-label="Monto a programar" v-model="monto">
                    <button class="btn btn-primary mt-2" @click="handleProgramar">Programar recursos</button>
                </section>
                <hr/>
                <section class="mt-2">
                    <div v-if="partidasProgramadas.length > 0" v-for="partida in partidasProgramadas" :key="partida.id">
                        <p class="h5">{{ partida.descripcion }}</p>
                        <p>Partida: {{ partida.partida }}</p>
                        <p>Monto programado: {{ partida.monto_programado }}</p>
                        <div class="d-flex ">
                            <button class="btn btn-warning">Editar</button>
                            <button class="btn btn-danger mx-2">Eliminar</button>
                        </div>
                    </div>
                </section>
            </div>
            
            <hr/>
            <p>Total programado: {{ partidasProgramadas.reduce((total, partida) => total + partida.monto_programado, 0) }}</p>
            <button class="btn btn-primary mt-2" @click="handleSave">Guardar programacion del ejercicio</button>
            <Notifications position="bottom left" />
        </main>
    </div>
</template>

<script setup>
import SourcePicker from './homepage_components/SourcePicker.vue';
import { ref, defineProps, nextTick, watch, onBeforeMount } from 'vue';
import { Notifications, notify } from '@kyvg/vue3-notification';
import axios from 'axios';
import VueSelect from 'vue-select';
import "vue-select/dist/vue-select.css"

const props = defineProps({
    user: Object,
    fuentes: Array,
    periodos: Array,
    partidas: Array
});

onBeforeMount(() => {
    // concatenate partida - descripcion from partidas
    partidasFormateadas.value = props.partidas.map(partida => ({
        ...partida,
        label: `${partida.partida} - ${partida.descripcion}`
    }));
})

const ejercicio = ref(null);
const partidasFormateadas = ref([]);
const partidaSeleccionada = ref(null);
const selectedSource = ref(null);   
const partidasProgramadas = ref([]);
const monto = ref(null);
const activeTab = ref('new');

watch(partidaSeleccionada, () => {
    console.log(partidaSeleccionada.value);
});

const handleSelect = async (source) => {
  selectedSource.value = source.id;
}

const handleTabClick = async (tab) => {
    activeTab.value = tab;
}

const handleProgramar = async () => {
    partidasProgramadas.value.push({
        idfuente: selectedSource.value,
        ejercicio: ejercicio.value,
        idpartida: partidaSeleccionada.value.id,
        descripcion: partidaSeleccionada.value.descripcion,
        idcapitulo: partidaSeleccionada.value.idcapitulo,
        monto_programado: monto.value
    });
}

const handleSave = async () => {
    try {
        const formData = new FormData();
        formData.append('partidas', JSON.stringify(partidasProgramadas.value));
        const response = await axios.post('/save_programacion_partidas', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        if(response.status === 200){
            notify({
                title: 'Programacion guardada exitosamente',
                text: 'La programacion se ha guardado correctamente',
                type: 'success',
                duration: 5000,
            })
        }
    } catch (e) {
        notify({
            title: 'Error al guardar programacion',
            text: 'Error: ' + e.response.data.message,
            type: 'error',
            duration: 5000,
        })
    }
}

</script>

<style scoped>
.con{
    margin-top: 10vh;
}
</style>