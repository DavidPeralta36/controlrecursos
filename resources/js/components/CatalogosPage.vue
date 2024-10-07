<template>
    <div class="container w-100 h-100 con">
        <h5>Administracion de catalogos</h5>
        <p>Selecciona el catalogo que deseas administrar</p>
        <hr>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" :class="activeTab === 'partidas' ? 'active' : ''" aria-current="page" href="#" @click="handleTabClick('partidas')">Partidas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="activeTab === 'clues' ? 'active' : '' " aria-current="page" href="#" @click="handleTabClick('clues')">Clues</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="activeTab === 'capitulos' ? 'active' : ''" aria-current="page" href="#" @click="handleTabClick('capitulos')">Capitulos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="activeTab === 'proveedores' ? 'active' : '' " aria-current="page" href="#" @click="handleTabClick('proveedores')">Proveedores</a>
            </li>
        </ul>

        <div v-if="activeTab === 'partidas'" calass="container my-5">
            <h5>Administracion de partidas</h5>
            <AgGridVue
                :rowData="partidas"
                :columnDefs="colDefsPartidas"
                style="height: 500px"
                class="ag-theme-quartz mb-5"
                :animateRows="true"
                :rowHeight="48"
                @cellValueChanged="onCellValueChanged"
                :frameworkComponents="{ PartidasRenderer }"
            />
            <div class="flex ">
                <button v-if="editedRecords.length > 0" class="btn btn-warning mt-2" >Cancelar edición</button>
                <button v-if="editedRecords.length > 0" class="btn btn-secondary mt-2 mx-3" @click="handleSave">Guardar registros editados</button> 
            </div>
            <hr>
            <div class="my-5">
                <p>Agregar nueva partida</p>
                <div class="">
                    <label for="partida" class="ml-1">Partida</label>
                    <input type="text" class="form-control" placeholder="Partida" aria-label="Partida"  v-model="formPartida.partida" required>
                </div>
                <div class="">
                    <label for="descripcion" class="ml-1">Descripcion</label>
                    <input type="text" class="form-control" placeholder="Descripcion" aria-label="Descripcion" v-model="formPartida.descripcion" required>
                </div>
                <div class="">
                    <label for="capitulo" class="ml-1">Capitulo</label>
                    <VueSelect label="nombre" :options="capitulos" v-model="formPartida.capitulo" class="mt-1"/>
                </div>
                <button class="btn rojo mt-2" type="submit" @click="handleSaveNewPartida">Agregar partida</button>
            </div>
        </div>
        <Notifications position="bottom left" />
    </div>
</template>
<script setup>
import { ref, defineProps, nextTick, watch, onBeforeMount } from 'vue';
import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the Data Grid
import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the Data Grid
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component
import { notify, Notifications } from '@kyvg/vue3-notification';
import axios from 'axios';
import PartidasRenderer from '../components/auxiliares/renderers/PartidasRenderer.vue';
import '@vueform/toggle/themes/default.css'
import VueSelect from 'vue-select';

const props = defineProps({
    user: Object,
    partidas: Array,
    capitulos: Array
});

const activeTab = ref('partidas');

const colDefsPartidas = ref([
    { field: 'partida', headerName: 'Partida', filter: true, sortable: true, editable: true, },
    { field: 'descripcion', headerName: 'Descripcion', filter: true, sortable: true, editable: true},
    { field: 'aportacion_federal', headerName: 'Aportacion federal', filter: true, sortable: true, editable: true},
    { field: 'aportacion_estatal', headerName: 'Aportacion estatal', filter: true, sortable: true, editable: true},
    { field: 'req_auth_a_imb', headerName: 'Requisitos auth a IMB', filter: true, sortable: true, editable: true},
    { field: 'req_auth_af', headerName: 'Requisitos auth af', filter: true, sortable: true, editable: true},
    { field: 'req_auth_aeg', headerName: 'Requisitos auth aeg', filter: true, sortable: true, editable: true},
    { field: 'remuneracionPersonal', headerName: 'Remuneracion personal', filter: true, sortable: true, editable: true},
    { field: 'adminInsumosMed', headerName: 'Admin insumos med', filter: true, sortable: true, editable: true},
    { field: 'gastosOperacion', headerName: 'Gastos operacion', filter: true, sortable: true, editable: true},
    { field: 'idcapitulo', headerName: 'Capitulo', filter: true, sortable: true, editable: true},
    { field: 'actions', headerName: 'Acciones', cellRenderer: PartidasRenderer},
]);

const editedRecords = ref([]);
const formPartida = ref({
    partida: null,
    descripcion: null,
    capitulo: null,
});

const handleTabClick = async (tab) => {
    activeTab.value = tab;
}

const onCellValueChanged = async (params) => {
  const updatedData = params.data;
  editedRecords.value.push(updatedData);
  console.log(editedRecords.value);
};

const handleSave = async () => {
    if(editedRecords.value.length <= 0){
        notify({
            title: 'Error al guardar registros editados',
            text: 'Debe completar los registros editados',
            type: 'error',
            duration: 5000,
            speed: 1000,
        });
        return;
    }

    try {   
        const formData = new FormData();
        formData.append('records', JSON.stringify(editedRecords.value));
        const response = await axios.post('/save_edited_partidas', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
        });
        if (response.status === 200) {
        notify({
            title: 'Partida programada actualizada exitosamente',
            text: 'La partida se ha actualizado correctamente',
            type: 'success',
            duration: 5000,
        });
        }
    } catch (e) {
        notify({
            title: 'Error al actualizar partida programada',
            text: 'Error: ' + e.message,
            type: 'error',
            duration: 5000,
        });
    }
};

const handleSaveNewPartida = async () => {
    console.log(formPartida.value);
    if(formPartida.value.partida && formPartida.value.descripcion && formPartida.value.capitulo){
        try{
            const response = await axios.post('/save_new_partida', {
                partida: formPartida.value.partida,
                descripcion: formPartida.value.descripcion,
                capitulo: formPartida.value.capitulo.id
            });
            if(response.status === 200){
                notify({
                    title: 'Partida guardada exitosamente',
                    text: 'La partida se ha guardado correctamente',
                    type: 'success',
                    duration: 5000,
                })
            }else{
                notify({
                    title: 'Error al guardar partida',
                    text: 'Error: ' + response.message,
                    type: 'error',
                    duration: 5000,
                })
            }
        }catch(e){
            notify({
                title: 'Error al guardar partida',
                text: 'Error: ' + e.message,
                type: 'error',
                duration: 5000,
            })
        }
    }else{
        notify({
            title: 'Error al guardar partida',
            text: 'Debe completar todos los campos',
            type: 'error',
            duration: 5000,
            speed: 1000,
        });
        return;
    }
}

</script>
<style scoped>
.con{
    margin-top: 10vh;
}

</style>