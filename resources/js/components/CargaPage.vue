<template lang="html">
    <div class="container con">
        <div class="mt-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" :class="activeTab === 'upload' ? 'active' : ''" aria-current="page" href="#" @click="handleTabClick('upload')">Carga</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="activeTab === 'modify' ? 'active' : '' " aria-current="page" href="#" @click="handleTabClick('modify')">Edición</a>
                </li>
            </ul>
            <div v-if="activeTab === 'upload'" ref="uploadMainContainer">
                <p class="h2 nunito-bold mt-2" style="font-style: normal">Carga de banco a la base de datos</p>
                <hr/>
                <div style="min-height: 16vh">
                    <SourcePicker :fuentes="fuentes" :handleSelect="handleSelect"/>
                </div>
                <div class="d-flex mt-3 w-100 justify-content-between">
                    <div class="d-flex align-items-start ">
                        <Toggle v-model="newBank" />
                        <p class="nunito ml-2">{{ newBank ? 'Nuevo banco' : 'Banco existente' }}</p>
                    </div>
                    <div v-if="!newBank">
                        <p class="nunito">Selecciona el ejercicio</p>
                        <VueSelect v-model="selectedPeriod" :options="periodos" label="ejercicio" style="min-width: 11vw;" />
                    </div>
                    <div v-else>
                        <p class="nunito">Ingresa el ejercicio</p>
                        <input v-model="newBankYear" type="number" class="form-control" placeholder="Ejercicio" aria-label="Ejercicio" style="min-width: 11vw;" @input="handleYearChange" />
                    </div>
                </div>
                <hr/>
                <div ref="uploadContainer" style="opacity: 0; " v-if="valid">
                    <p class="nunito h4">Origen de datos</p>
                    <div class="input-group mb-3" >
                        <div class="custom-file">
                            <input type="file" class="form-control" id="bankFile" aria-describedby="ariaBankFile" @change="handleFileChange">
                            <label class="form-label" for="bankFile">{{ bankFile ? bankFile.name : 'Seleccionar archivo' }}</label>
                        </div>
                    </div>
                </div>
                <button v-if="readyToSend" ref="sendButton" class="btn btn-primary" :disabled="loading" @click="habdleUploadFile">{{ sendButtonAnimated ? 'Subir origen de datos' : ''}}</button>
                <ModalPreliminarData v-if="showPreliminarData" ref="modalPreliminarData" :preliminarData="datosPreliminares" @cancelData="handleCancelData" :selectedSource="selectedSource"/>
            </div>

            <div v-if="activeTab === 'modify'" ref="modifyContainer">
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
                        <button class="btn btn-primary" @click="handleFindPeriod">
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
                        <button class="btn btn-success mx-2" @click="handleEdit">
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
import { ref, defineProps, nextTick, watch } from 'vue';
import SourcePicker from './homepage_components/SourcePicker.vue';
import Toggle from '@vueform/toggle/src/Toggle';
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
import ModalPreliminarData from './auxiliares/ModalPreliminarData.vue';
import { U013, ALE, E001 } from '../lib/Headers.js';

const props = defineProps({
    user: Object,
    fuentes: Array,
    periodos: Array
});

//#region Variables
const selectedSource = ref(null);
const uploadContainer = ref(null);
const newBank = ref(true);
const selectedPeriod = ref('Periodo requerido *');
const newBankYear = ref(null);
const valid = ref(false);
const bankFile = ref(null);
const excelData = ref(null);
const readyToSend = ref(false);
const sendButton = ref(null);
const sendButtonAnimated = ref(false);
const activeTab = ref('upload');
const uploadMainContainer = ref(null);
const modifyContainer = ref(null);
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
const totales = ref({
  registros: 0,
  depositos: 0,
  retiros: 0,
  saldo: 0
});
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
const headerColumnsSearch = ['FECHA', 'MES'];
const datosPreliminares = ref([]);
const showPreliminarData = ref(false);
const modalPreliminarData = ref(null);
const loading = ref(false);
//#endregion

watch(selectedSource, () => {
  switch (selectedSource.value) {
    case 1:
      colDefs.value = U013;
      break;
    case 4:
      colDefs.value = ALE;
      break;
    case 5:
      colDefs.value = E001;
      break;
    default:
      colDefs.value = [];
  }
});

const handleSelect = async (source) => {
  selectedSource.value = source.id;
  if((selectedSource.value && selectedPeriod.value !== "Periodo requerido *") || (selectedSource.value && newBankYear.value)) {
    valid.value = true;

    await nextTick();

    animateUploadContainer();
  }
}

const handleYearChange = async (e) => {
    if (e.target.value.length === 4) {
        newBankYear.value = e.target.value;
        if(newBankYear.value && selectedSource.value) {
            valid.value = true;
            await nextTick();
            animateUploadContainer();
        }else{
            valid.value = false;
        }
    }else{
        valid.value = false;
    }
}

const handleFileChange = async (e) => {
  const file = e.target.files[0];
  bankFile.value = file;

  if (bankFile.value) {
    if(selectedSource && selectedSource.value == 1){
        loadU013();
    }

    if(selectedSource && selectedSource.value == 4){
        loadASLE();
    }

    if(selectedSource && selectedSource.value == 5){
        await loadE001();
    }
    
  }
}

const handleCancelData = () => {
    registros.value = [];
    datosPreliminares.value = [];
    valid.value = false;
    newBankYear.value = null;
    selectedPeriod.value = null;
    selectedSource.value = null;
    readyToSend.value = false;
}

const openModal = () => {
    modalPreliminarData.value.openModal();
};

const loadE001 = async () => {
    const reader = new FileReader();

    const formatFile = [
        "FECHA",
        "MES",
        "METODO \r\nDE PAGO",
        "RFC",
        "PROVEDOR",
        "FACTURA ",
        "PARCIAL",
        "RETIROS",
        "DEPOSITOS",
        "SALDO",
        "R",
        "PARTIDA PRESUPUESTAL",
        "FECHA DE FACTURA",
        "FOLIO FISCAL",
        "TIPO DE \r\nADJUDICACION",
        "NUMERO DE ADJUDICACION \r\nO CONTRATO",
        "NUMERO DE \r\nTECHO FINANCIERO",
        "ORDEN DE SERVICIO \r\nO COMPRA",
        "CLC",
        "POLIZA",
        "NUMERO DE CUENTA\r\nDEL PROVEEDOR",
        "REFERENCIA BANCARIA",
        "CLUE",
        "APLICA EN:",
        "NOMBRE DE LA PARTIDA"
    ];


    reader.onload = async (event) => {
      const data = new Uint8Array(event.target.result);
      const woorkbook = XLSX.read(data, { type: 'array' });

      const sheetName = woorkbook.SheetNames[3];
    
      if(sheetName){
        const woorksheet = woorkbook.Sheets[sheetName];

        const sheetJson = XLSX.utils.sheet_to_json(woorksheet, { header: 1, blankrows: false });


        const matchFileFormat = sheetJson.length > 0 && formatFile.every(column => 
            sheetJson[6].includes(column)
        );

        if(!matchFileFormat){{
            notify({
                title: 'Error al leer el archivo',
                text: 'El archivo no tiene el formato correcto',
                type: 'error',
                duration: 5000,
                speed: 1000,
            });
            return;
        }}

        const headerRowIndex = sheetJson.findIndex(row => 
            headerColumnsSearch.some(col => row.includes(col))
        );

        if(headerRowIndex !== -1){
            const dataRows = sheetJson.slice(headerRowIndex);
          
            const maxColumns = dataRows.reduce((max, row) => Math.max(max, row.length), 0);

            const formattedDataRows = dataRows.map(row => {
                const formattedRow = row.map(cell => {
                    if (typeof cell === 'number' && cell === row[0]) {
                        if (cell > 59 && cell < 2958465) { 
                            return XLSX.SSF.format("yyyy-mm-dd", cell);
                        }
                    }

                    if (typeof cell === 'string' && cell === row[11]) {
                        if(cell.length === 0 || cell === '' || cell === ' '){
                            return null;
                        }
                    }

                    return cell;
                });

                while (formattedRow.length < maxColumns) {
                formattedRow.push(null);
                }

                return formattedRow;
            });

            excelData.value = formattedDataRows;

            if(!newBank.value){
                if (excelData.value && excelData.value.length > 0) {
                    datosPreliminares.value = formattedDataRows;;
                    showPreliminarData.value = true;
                    await nextTick();
                    openModal();
                }
            }
        }

        notify({
            title: 'Archivo cargado y listo para enviar',
            text: 'Favor de verificar el archivo antes de enviar',
            type: 'info',
            duration: 5000,
            speed: 1000,
            })
        }

        readyToSend.value = true;
        await nextTick();
        animateSendButton();
    }

    reader.onerror = () => {
      notify({
        title: 'Error al leer el archivo',
        text: 'Ocurrió un problema al leer el archivo, por favor intente nuevamente.',
        type: 'error',
        duration: 5000,
        speed: 1000,
      });
    };

    reader.readAsArrayBuffer(bankFile.value);
}

const loadASLE = () => {
    const reader = new FileReader();

    const formatFile = [
        "FECHA",
        "MES",
        "METODO \r\nDE PAGO",
        "RFC",
        "PROVEDOR",
        "FACTURA ",
        "PARCIAL",
        "DEPOSITOS",
        "RETIROS",
        "SALDO",
        "R",
        "PARTIDA PRESUPUESTAL",
        "FECHA DE FACTURA",
        "FOLIO FISCAL",
        "TIPO DE \r\nADJUDICACION",
        "NUMERO DE ADJUDICACION \r\nO CONTRATO",
        "NUMERO DE \r\nTECHO FINANCIERO",
        "ORDEN DE SERVICIO \r\nO COMPRA",
        "CLC",
        "POLIZA",
        "NUMERO DE CUENTA\r\nDEL PROVEEDOR",
        "REFERENCIA BANCARIA",
        "CLUE",
        "APLICA EN:",
        "NOMBRE DE LA PARTIDA"
    ];

    
    reader.onload = async (event) => {
      const data = new Uint8Array(event.target.result);
      const woorkbook = XLSX.read(data, { type: 'array' });

      const sheetName = woorkbook.SheetNames[3];
    
      if(sheetName){
        const woorksheet = woorkbook.Sheets[sheetName];

        const sheetJson = XLSX.utils.sheet_to_json(woorksheet, { header: 1, blankrows: false });


        const matchFileFormat = sheetJson.length > 0 && formatFile.every(column => 
            sheetJson[5].includes(column)
        );

        if(!matchFileFormat){{
            notify({
                title: 'Error al leer el archivo',
                text: 'El archivo no tiene el formato correcto',
                type: 'error',
                duration: 5000,
                speed: 1000,
            });
            return;
        }}

        const headerRowIndex = sheetJson.findIndex(row => 
            headerColumnsSearch.some(col => row.includes(col))
        );

        if(headerRowIndex !== -1){
            const dataRows = sheetJson.slice(headerRowIndex);
          
            const maxColumns = dataRows.reduce((max, row) => Math.max(max, row.length), 0);

            const formattedDataRows = dataRows.map(row => {
                const formattedRow = row.map(cell => {
                    if (typeof cell === 'number' && cell === row[1]) {
                        if (cell > 59 && cell < 2958465) { 
                            return XLSX.SSF.format("yyyy-mm-dd", cell);
                        }
                    }

                    if (typeof cell === 'string' && cell === row[12]) {
                        if(cell.length === 0 || cell === '' || cell === ' '){
                            return null;
                        }
                    }

                    return cell;
                });

                while (formattedRow.length < maxColumns) {
                formattedRow.push(null);
                }

                return formattedRow;
            });

            excelData.value = formattedDataRows;

            if(!newBank.value){
                if (excelData.value && excelData.value.length > 0) {
                    datosPreliminares.value = formattedDataRows;;
                    showPreliminarData.value = true;
                    await nextTick();
                    openModal();
                }
            }
        }

        notify({
            title: 'Archivo cargado y listo para enviar',
            text: 'Favor de verificar el archivo antes de enviar',
            type: 'info',
            duration: 5000,
            speed: 1000,
            })
        }

        readyToSend.value = true;
        await nextTick();
        animateSendButton();
    }

    reader.onerror = () => {
      notify({
        title: 'Error al leer el archivo',
        text: 'Ocurrió un problema al leer el archivo, por favor intente nuevamente.',
        type: 'error',
        duration: 5000,
        speed: 1000,
      });
    };

    reader.readAsArrayBuffer(bankFile.value);
}

const loadU013= () => { 
    const reader = new FileReader();

    const formatFile = [
        "FECHA",
        "MES",
        "FORMA\r\nDE PAGO",
        "METODO DE PAGO",
        "RFC",
        "PROVEDOR",
        "FACTURA ",
        "PARCIAL",
        "DEPOSITOS",
        "RETIROS",
        "SALDO",
        "R",
        "PARTIDA PRESUPUESTAL",
        "FECHA DE FACTURA",
        "FOLIO FISCAL",
        "TIPO DE \r\nADJUDICACION",
        "NUMERO DE ADJUDICACION \r\nO CONTRATO",
        "NUMERO DE SUFICIENCIA PRESUPUESTAL",
        "ORDEN DE SERVICIO \r\nO COMPRA",
        "CLC",
        "POLIZA",
        "NUMERO DE CUENTA\r\nDEL PROVEEDOR",
        "REFERENCIA BANCARIA",
        "CLUE",
        "APLICA EN:",
        "NOMBRE DE LA PARTIDA",
        "MES DE SERVICIO"
    ];


    reader.onload = async (event) => {
      const data = new Uint8Array(event.target.result);
      const woorkbook = XLSX.read(data, { type: 'array' });

      const sheetName = woorkbook.SheetNames[3];
    
      if(sheetName){
        const woorksheet = woorkbook.Sheets[sheetName];

        const sheetJson = XLSX.utils.sheet_to_json(woorksheet, { header: 1, blankrows: false });


        const matchFileFormat = sheetJson.length > 0 && formatFile.every(column => 
            sheetJson[2].includes(column)
        );

        if(!matchFileFormat){{
            notify({
                title: 'Error al leer el archivo',
                text: 'El archivo no tiene el formato correcto',
                type: 'error',
                duration: 5000,
                speed: 1000,
            });
            return;
        }}

        const headerRowIndex = sheetJson.findIndex(row => 
            headerColumnsSearch.some(col => row.includes(col))
        );

        if(headerRowIndex !== -1){
            const dataRows = sheetJson.slice(headerRowIndex);
          
            const maxColumns = dataRows.reduce((max, row) => Math.max(max, row.length), 0);

            const formattedDataRows = dataRows.map(row => {
                const formattedRow = row.map(cell => {
                    if (typeof cell === 'number' && cell === row[1]) {
                        if (cell > 59 && cell < 2958465) { 
                            return XLSX.SSF.format("yyyy-mm-dd", cell);
                        }
                    }

                    if (typeof cell === 'string' && cell === row[5]) {
                        if(cell.length === 0 || cell === '' || cell === ' '){
                            return null;
                        }
                    }

                    if (typeof cell === 'string' && cell === row[13]) {
                        if(cell.length === 0 || cell === '' || cell === ' '){
                            return null;
                        }
                    }

                    if (typeof cell === 'string' && cell === row[24]) {
                        if(cell.length === 0 || cell === '' || cell === ' '){
                            return null;
                        }
                    }

                    return cell;
                });

                while (formattedRow.length < maxColumns) {
                formattedRow.push(null);
                }

                return formattedRow;
            });

            excelData.value = formattedDataRows;

            if(!newBank.value){
                if (excelData.value && excelData.value.length > 0) {
                    datosPreliminares.value = formattedDataRows;;
                    showPreliminarData.value = true;
                    await nextTick();
                    openModal();
                }
            }
        }

        notify({
            title: 'Archivo cargado y listo para enviar',
            text: 'Favor de verificar el archivo antes de enviar',
            type: 'info',
            duration: 5000,
            speed: 1000,
            })
        }

        readyToSend.value = true;
        await nextTick();
        animateSendButton();
    }

    reader.onerror = () => {
      notify({
        title: 'Error al leer el archivo',
        text: 'Ocurrió un problema al leer el archivo, por favor intente nuevamente.',
        type: 'error',
        duration: 5000,
        speed: 1000,
      });
    };

    reader.readAsArrayBuffer(bankFile.value);
}

const habdleUploadFile = async () => {
    if(newBank.value){
        if(!newBankYear.value || !selectedSource.value || !excelData.value ){
            notify({
                title: 'Error al subir archivo',
                text: 'Debe completar todos los campos',
                type: 'error',
                duration: 5000,
                speed: 1000,
            });
            return;
        }
    }else{
        if(!selectedPeriod.value || !selectedSource.value || !excelData.value ){
            notify({
                title: 'Error al subir archivo',
                text: 'Debe completar todos los campos',
                type: 'error',
                duration: 5000,
                speed: 1000,
            });
            return;
        }
    }

    loading.value = true;
    sendButtonAnimated.value = false;
    await nextTick();
    animateLoading();

    const formData = new FormData();
    const jsonBlob = new Blob([JSON.stringify(excelData.value)], { type: 'application/json' });
    formData.append('archivo', jsonBlob, 'data.json');
    formData.append('source', selectedSource.value);
    if(newBank.value){
        formData.append('periodo', newBankYear.value);
    }else{
        formData.append('periodo', selectedPeriod.value.ejercicio);
    }
    try {
        notify({
                title: 'Subiendo archivo',
                text: 'La información se esta guardando en el servidor',
                type: 'warn',
                duration: 5000,
                speed: 1000,
            });
        const response = await axios.post('/upload_report', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response.status === 200) {
            notify({
                title: 'Banco actualizado',
                text: 'La información se ha subido correctamente',
                type: 'success',
                duration: 5000,
                speed: 1000,
            });
            endAnimation();
            selectedPeriod.value = null;
            selectedSource.value = null;
            newBankYear.value = null;
            excelData.value = null;
            bankFile.value = null;
            //readyToSend.value = false;
            //valid.value = false;
        } else {
            notify({
                title: 'Error al subir archivo',
                text: 'Favor de verificar el archivo antes de enviar',
                type: 'error',
                duration: 5000,
                speed: 1000,
            });
        }
    } catch (e) {
        console.log(e);
    }
    loading.value = false;
}

watch(selectedPeriod, async () => {
    if(selectedPeriod.value && selectedSource.value) {
        valid.value = true;
        await nextTick();
        animateUploadContainer();
    }
})

const handleTabClick = async (tab) => {
    if(tab === 'upload'){
        animateExitModifyContainer(tab);
    }else{
        animateExitUploadMainContainer(tab);
    }
}

const handleFindPeriod = async () => {
    if(selectedSource && selectedPeriod.value != "Periodo requerido *"){
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

const animateUploadContainer = () => {
    anime({
        targets: uploadContainer.value,
        opacity: [0, 1],
        duration: 500,
        easing: 'easeInOutQuad',
    });
}

const animateSendButton = () => {
    anime.timeline()
    .add({
      targets: sendButton.value,
      opacity: [0, 1],
      width: [0, '30px'],      // Anima a un círculo de 50px
      height: [0, '30px'],
      borderRadius: '10px', // Mantén la forma circular
      easing: 'easeOutExpo',
      duration: 500,
      overflow: 'hidden',
    })
    .add({
      targets: sendButton.value,
      rotate: '1turn',   // Gira 360 grados
      easing: 'easeInOutSine',
      duration: 250,
    })
    .add({
      targets: sendButton.value,
      width: '200px',     // Cambia a tamaño normal del botón
      height: '40px',
      borderRadius: '10px', // Elimina el borde redondeado
      easing: 'easeOutExpo',
      duration: 400,

      complete: () => {
        sendButtonAnimated.value = true;
      }
    });
}

const animateLoading = () => {
    anime.remove(sendButton.value);
    
    anime({
        targets: sendButton.value,
        opacity: [0, 1],
        width: ['200px', '30px'],
        height: ['40px', '30px'],
        borderRadius: '5px',
        easing: 'easeOutExpo',
        duration: 500,
    })
    //alterna la rotación de la imagen
    anime({
        targets: sendButton.value,
        rotate: '2turn',
        width: ['30px', '40px'],
        height: ['30px', '40px'],
        easing: 'easeInOutSine',
        borderRadius: '15px',
        duration: 750,
        direction: 'alternate',
        delay:250,
        loop: true,
    })
}

const endAnimation = () => {
    anime.remove(sendButton.value);

    anime({
        targets: sendButton.value,
        width: ['30px', '200px'],
        height: ['30px', '40px'],
        borderRadius: '5px',  
        easing: 'easeOutExpo',
        duration: 500,
        rotate: '0deg', 
        complete: () => {
            sendButtonAnimated.value = true;
        }
    });
    readyToSend.value = false;
}

const animateUploadMainContainer = () => {
    anime({
        targets: uploadMainContainer.value,
        opacity: [0, 1],
        translateY: [100, 0],
        duration: 500,
        easing: 'easeInOutQuad',
    });
}

const animateExitUploadMainContainer = (tab) => {
    anime({
        targets: uploadMainContainer.value,
        opacity: [1, 0],
        translateX: [0, 110],
        duration: 500,
        easing: 'easeInOutQuad',
        complete: async () => {
            activeTab.value = tab;
            await nextTick();
            animateModifyContainer();
        }
    });
}

const animateModifyContainer = () => {
    anime({
        targets: modifyContainer.value,
        opacity: [0, 1],
        translateY: [100, 0],
        duration: 500,
        easing: 'easeInOutQuad',
    });
}

const animateExitModifyContainer = (tab) => {
    anime({
        targets: modifyContainer.value,
        opacity: [1, 0], 
        translateX: [0, 100],
        duration: 500,
        easing: 'easeInOutQuad',
        complete: async () => {
            activeTab.value = tab;
            await nextTick();
            animateUploadMainContainer();
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
</style>