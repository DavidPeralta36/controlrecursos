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

        <div class="flex ">
            <button v-if="editedRecords.length > 0" class="btn btn-warning mt-2" >Cancelar edición</button>
            <button v-if="editedRecords.length > 0" class="btn btn-secondary mt-2 mx-3" @click="handleSave">Guardar registros editados</button> 
        </div>
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
        <div v-if="activeTab === 'clues'" calass="container my-5">
            <h5>Administracion de clues</h5>
            <AgGridVue
                :rowData="clues"
                :columnDefs="colDefsClues"
                style="height: 500px"
                class="ag-theme-quartz mb-5"
                :animateRows="true"
                :rowHeight="48"
                @cellValueChanged="onCellValueChanged"
                :frameworkComponents="{ CluesRenderer }"
            />
            <hr>
            <div class="my-5">
                <p>Agregar nueva clue</p>
                <div class="">
                    <label for="clue" class="ml-1">Clue</label>
                    <input type="text" class="form-control" placeholder="Clue" aria-label="Clue"  v-model="formClue.clue" required>
                </div>
                <div>
                    <label for="clue_homologada" class="ml-1">Clue homologada</label>
                    <input type="text" class="form-control" placeholder="Clue homologada" aria-label="Clue homologada" v-model="formClue.clue_homologada" required>
                </div>
                <div class="">
                    <label for="nombre_clue" class="ml-1">Nombre clue</label>
                    <input type="text" class="form-control" placeholder="Nombre clue" aria-label="Nombre clue" v-model="formClue.nombre_clue" required>
                </div>

                <button class="btn rojo mt-2" type="submit" @click="handleSaveNewClue">Agregar clue</button>
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
import CluesRenderer from './auxiliares/renderers/CluesRenderer.vue';
import '@vueform/toggle/themes/default.css'
import VueSelect from 'vue-select';

const props = defineProps({
    user: Object,
    partidas: Array,
    capitulos: Array,
    clues: Array
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

const colDefsClues = ref([
    { field: 'clue', headerName: 'Clue', filter: true, sortable: true, editable: true,  flex: 1 },
    { field: 'clue_homologada', headerName: 'Clue homologada', filter: true, sortable: true, editable: true, flex: 1 },
    { field: 'nombre_clue', headerName: 'Nombre clue', filter: true, sortable: true, editable: true, flex: 1 },
    { field: 'actions', headerName: 'Acciones', cellRenderer: CluesRenderer},
])

const editedRecords = ref([]);
const formPartida = ref({
    partida: null,
    descripcion: null,
    capitulo: null,
});
const formClue = ref({
    clue: null,
    clue_homologada: null,
    nombre_clue: null,
});

const handleTabClick = async (tab) => {
    activeTab.value = tab;
    editedRecords.value = [];
}

const onCellValueChanged = async (params) => {
  const updatedData = params.data;
  editedRecords.value.push(updatedData);
  console.log(editedRecords.value);
};

const savePartidasEdited = async () => {
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
}

const saveCluesEdited = async () => {
    try {   
        const formData = new FormData();
        formData.append('records', JSON.stringify(editedRecords.value));
        const response = await axios.post('/save_edited_clues', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
        });
        if (response.status === 200) {
            notify({
                title: 'Clue programada actualizada exitosamente',
                text: 'La clue se ha actualizado correctamente',
                type: 'success',
                duration: 5000,
            });
            getClues();
        }
    } catch (e) {
        notify({
            title: 'Error al actualizar clue programada',
            text: 'Error: ' + e.message,
            type: 'error',
            duration: 5000,
        });
    }
}

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

    if(activeTab.value === 'partidas'){
        await savePartidasEdited();
    }else if(activeTab.value === 'clues'){
        await saveCluesEdited();
    }

    editedRecords.value = [];
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
                getPartidas();
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

const handleSaveNewClue = async () => {
    console.log(formClue.value);
    if(formClue.value.clue && formClue.value.clue_homologada && formClue.value.nombre_clue){
        try{
            const response = await axios.post('/save_new_clue', {
                clue: formClue.value.clue,
                clue_homologada: formClue.value.clue_homologada,
                nombre_clue: formClue.value.nombre_clue
            });
            if(response.status === 200){
                notify({
                    title: 'Clue guardada exitosamente',
                    text: 'La clue se ha guardado correctamente',
                    type: 'success',
                    duration: 5000,
                })
            }else{
                notify({
                    title: 'Error al guardar clue',
                    text: 'Error: ' + response.message,
                    type: 'error',
                    duration: 5000,
                })
            }
        }catch(e){
            notify({
                title: 'Error al guardar clue',
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

const getPartidas = async () => {
    try{
        const response = await axios.get('/get_partidas');
        partidasDB.value = response.data;
    }catch(e){
        notify({
            title: 'Error al obtener partidas',
            text: 'Error: ' + e.message,
            type: 'error',
            duration: 5000,
        })
    }
}

const getCapitulos = async () => {
    try{
        const response = await axios.get('/get_capitulos');
        if(response.status === 200){
            capitulos.value = response.data;
        }else{
            notify({
                title: 'Error al obtener capitulos',
                text: 'Error: ' + e.message,
                type: 'error',
                duration: 5000,
            })
        }
    }catch(e){
        notify({
            title: 'Error al obtener capitulos',
            text: 'Error: ' + e.message,
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