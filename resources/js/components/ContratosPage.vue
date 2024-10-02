<template lang="html">
    <div class="container con">
        <div class="mt-3">
            <div>
                <p class="h2 nunito-bold mt-2" style="font-style: normal">Modificacion de informacion del banco</p>
                <hr/>
                <div style="min-height: 16vh">
                    <SourcePicker :fuentes="fuentes" :handleSelect="handleSelect"/>
                </div>
                <div class="d-flex mt-3 w-100 justify-content-between">
                    <div>
                        <p class="nunito h5">Selecciona un ejercicio existente</p>
                        <VueSelect v-model="selectedPeriod" :options="periodos" label="ejercicio" style="min-width: 11vw;" />
                    </div>
                    <div> 
                        <button class="btn btn-primary" @click="handleFindPeriod" :disabled="loading">
                            <v-icon name="fa-chart-bar" animation="ring" hover/>
                            Consulta informacion del periodo
                        </button>
                    </div>
                </div>
                <hr/>
                <div class="mb-2 btns" v-if="editingValues" ref="editButtonsContainer">
                    <div ref="editButtons" v-if="animatedButtonsContainer">
                        <button class="btn btn-warning" @click="animateOutEditButtonsContainer">
                            <v-icon name="fa-edit" animation="spin" hover />
                            Cancelar edición
                        </button>
                        <button class="btn btn-success mx-2" @click="handleEdit" :disabled="loadingEdit">
                            Guardar
                        </button>
                    </div>
                </div>
                <div :class="!loads.loaded ? 'imgContainer' : 'tableContainer'">
                    <div v-if="loads.loading" class="text-center" ref="loadingDiv">
                      <div ref="spinner" class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                      </div>
                      <p ref="loadingText">Generando reporte</p>
                    </div>
                    <div v-if="loads.skeleton" ref="skeletonDiv">
                      <img src="../../../public/assets/vacio.png" alt="vacio" class="img"/>
                      <p class="font-weight-bold nunito font-italic h5 text-center">Nada por aquí todavía...</p>
                    </div> 
                    <div v-if="loads.loaded" class="w-100 my-4 pb-5" ref="tableDiv" >
                      <p class="nunito h5">Banco generado para el periodo con las fuentes de financiamiento seleccionadas:</p>
                      <AgGridVue
                        :rowData="registros"
                        :columnDefs="colDefs"
                        :pagination="agProps.pagination"
                        :paginationPageSize="agProps.paginationPageSize"
                        :paginationPageSizeSelector="agProps.paginationPageSizeSelector"
                        style="height: 500px"
                        class="ag-theme-quartz mb-5"
                        :frameworkComponents="{ customCellRenderer }"
                        :animateRows="true"
                        @cellValueChanged="onCellValueChanged"
                      >
                      </AgGridVue>  
                    </div>
                </div>
                <hr class="my-2"/>
            </div>
        </div>
        <Notifications position="bottom left" />
    </div>
</template>

<script setup>
import { ref, defineProps, nextTick, watch, onBeforeMount } from 'vue';
import SourcePicker from './homepage_components/SourcePicker.vue';
import '@vueform/toggle/themes/default.css'
import VueSelect from 'vue-select';
import "vue-select/dist/vue-select.css"
import anime from 'animejs';
import { Notifications, notify } from '@kyvg/vue3-notification';
import axios from 'axios';
import * as XLSX from 'xlsx';
import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the Data Grid
import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the Data Grid
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component
import {
  animateSkeletonOut,
  animateLoadingOut,
  startAnimations,
} from '../utils/animations.js';
import { U013, ALE, E001, S200 } from '../lib/Headers.js';

const props = defineProps({
    user: Object,
    fuentes: Array,
    periodos: Array
});
//#region Variables
const selectedSource = ref(null);
const selectedPeriod = ref('Periodo requerido *');
const newBankYear = ref(null);
const valid = ref(false);
const registros = ref([]);
const loadingText = ref(null); 
const spinner = ref(null); 
const skeletonDiv = ref(null);
const loadingDiv = ref(null);
const tableDiv = ref(null);
const loads = ref({
  loading: false,
  skeleton: true,
  loaded: false
})
const editingRecords = ref([]);
const colDefs = ref();
const agProps = ref({
  pagination: true,
  paginationPageSize: 500,
  paginationPageSizeSelector: [500, 1000, 5000],
});
const editButtons = ref(null);
const editButtonsContainer = ref(null);
const editingValues = ref(false);
const animatedButtonsContainer = ref(false);
const loading = ref(false);
const loadingEdit = ref(false);
//#endregion

onBeforeMount(() => {
    /*if(props.user.role !== 'almacen' && props.user.role !== 'obras'){
        window.location.href = '/';
    }*/
})

watch(selectedSource, () => {
  switch (selectedSource.value) {
    case 1:
        const colDefsEditableU013 = U013.map(col => ({
            ...col, 
            editable: getEditable(col.field) ,
            cellClass: getClass(col.field) ? 'validClass' : 'invalidClass'
        }));
        console.log(colDefsEditableU013);
        colDefs.value = colDefsEditableU013;
        break;
    case 2:
        const colDefsEditableS200 = S200.map(col => ({
            ...col, 
            editable: getEditable(col.field),
            cellClass: getClass(col.field) ? 'validClass' : 'invalidClass'
        }));
        colDefs.value = colDefsEditableS200;
        break;
    case 4:
        const colDefsEditableALE = ALE.map(col => ({
            ...col, 
            editable: getEditable(col.field),
            cellClass: getClass(col.field) ? 'validClass' : 'invalidClass'
        }));
        colDefs.value = colDefsEditableALE;
        break;
    case 5:
        const colDefsEditableE001 = E001.map(col => ({
            ...col,
            editable: getEditable(col.field),
            cellClass: getClass(col.field) ? 'validClass' : 'invalidClass'
        }));
        colDefs.value = colDefsEditableE001;
        break;
    default:
      colDefs.value = [];
  }
});

const getEditable = (field) => {
    var editable = false;

    switch(field){
        case "tipo_adjudicacion":
            editable = true;
            break;
        case "num_adj_contrato":
            editable = true;
            break;
        case "num_techo_financiero":
            editable = true;
            break;
        case "num_suficiencia_presupuestal":
            editable = true;
            break;
        case "orden_servicio_compra":
            editable = true;
            break;
        default:
            editable = false;
            break;
    }
    return editable;
}

const getClass = (field) => {
    var validClass = false;

    switch(field){
        case "tipo_adjudicacion":
            validClass = true;
            break;
        case "num_adj_contrato":
            validClass = true;
            break;
        case "num_techo_financiero":
            validClass = true;
            break;
        case "num_suficiencia_presupuestal":
            validClass = true;
            break;
        case "orden_servicio_compra":
            validClass = true;
            break;
        default:
            validClass = false;
            break;
    }

    return validClass;
}

const handleSelect = async (source) => {
  selectedSource.value = source.id;
  if((selectedSource.value && selectedPeriod.value !== "Periodo requerido *") || (selectedSource.value && newBankYear.value)) {
    valid.value = true;
  }
}

const handleFindPeriod = async () => {
    if(selectedSource && selectedPeriod.value != "Periodo requerido *"){
        loading.value = true;
        await animateSkeletonOut(skeletonDiv, loadingDiv, loads);
        startAnimations(loadingText, spinner);
        
        const response = await axios.get('/report_by_period', {
            params: {
                source: selectedSource.value,
                period: selectedPeriod.value.ejercicio
            }
        });

        registros.value = response.data;
        
        await nextTick();
        await animateLoadingOut(loadingDiv, tableDiv, loads);
        loading.value = false;
    }else{
        notify({
            title: 'Error al buscar el periodo',
            text: 'Por favor seleccione un periodo válido',
            type: 'error',
            duration: 5000,
            speed: 1000,
        });
    }
}

function customCellRenderer(params) {
  const value = params.valueFormatted ? params.valueFormatted : params.value;

  if (!value) {
    return '';
  }

  return `
    <span class="custom-cell">
      ${value}
    </span>
  `;
}

const onCellValueChanged = async (params) => {
  const updatedData = params.data;
  editingRecords.value.push(updatedData);
  if(editingRecords.value.length > 0){
    editingValues.value = true;
    await nextTick();
    animateInEditButtonsContainer();
  }
};

const handleEdit = async () => {
    if(editingRecords.value.length > 0){
        loadingEdit.value = true;
        try{
            const formData = new FormData();
            formData.append('records', JSON.stringify(editingRecords.value));
            for (let pair of formData.entries()) {
                console.log(`${pair[0]}, ${pair[1]}`);
            }
            const response = await axios.post('/edit_records', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            if(response.status === 200){
                notify({
                    title: 'Registros editados correctamente',
                    text: 'Los registros se han editado correctamente',
                    type: 'success',
                    duration: 5000,
                    speed: 1000,
                });
                loadingEdit.value = false;
            }else{
                notify({
                    title: 'Error al editar los registros',
                    text: 'Error: ' + response.data.message,
                    type: 'error',
                    duration: 5000,
                    speed: 1000,
                });
            }
        }catch(e){
            notify({
                title: 'Error al editar los registros',
                text: 'Error: ' + e.response.data.message,
                type: 'error',
                duration: 5000,
                speed: 1000,
            });
        }
    }else{
        notify({
            title: 'Error al editar los registros',
            text: 'Por favor verifique los registros seleccionados',
            type: 'error',
            duration: 5000,
            speed: 1000,
        });
    }
}

//#region animations
const animateInEditButtonsContainer = () => {
    anime({
        targets: editButtonsContainer.value,
        height: ['0px', '60px'],
        duration: 500,
        easing: 'easeInOutQuad',
        complete: async () => {
            animatedButtonsContainer.value = true;
            await nextTick();
            anime({
                targets: editButtons.value,
                opacity: [0, 1],
                translateX: [100, 0],
                duration: 500,
                easing: 'easeInOutQuad',
            })
        }
    });
}

const animateOutEditButtonsContainer = () => {
    editingRecords.value = [];
    anime({
        targets: editButtons.value,
        opacity: [1, 0],
        translateY: [0, 100],
        duration: 500,
        easing: 'easeInOutQuad',
        complete: async () => {
            animatedButtonsContainer.value = false;
            await nextTick();
            anime({
                targets: editButtonsContainer.value,
                height: ['60px', '0px'],
                duration: 500,
                easing: 'easeInOutQuad',
                complete: async () => {
                    animatedButtonsContainer.value = false;
                }
            })
        }
    });
}
//#endregion
</script>


<style >

.con{
    margin-top: 10vh;
}


.fuentes{
    height: 10vh;
    width: 100%;
    background-color: rgb(240, 240, 240);
    border-radius: 10px;
}
.btns{
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgb(226, 226, 226);
    border-radius: 10px;
}

.custom-cell {
    display: flex;
    align-items: center;
  }

.validClass{
    color: #0ab404;
    background-color: #d2ffd2;
    font-weight: bold;
}

.invalidClass{
    background-color: #fdecea;
    opacity: 0.5;
}
</style>