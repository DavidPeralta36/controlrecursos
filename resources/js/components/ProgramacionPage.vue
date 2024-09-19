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
        <main class="mb-5">
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
                    <div class="d-flex align-items-center">
                        <h5 class="h5 mt-2">$</h5>
                        <input type="number" class="form-control mx-2" placeholder="Monto a programar" aria-label="Monto a programar" v-model="monto">
                        <p class="h5 mt-2">MXN</p>
                    </div>
                    <button class="btn rojo mt-2" @click="handleProgramar">Programar recursos</button>
                </section>
                <hr/>
                <section class="mt-2">
                    <div v-if="partidasProgramadas.length > 0" v-for="partida in partidasProgramadas" :key="partida.id">
                        <p class="h5">{{ partida.descripcion }}</p>
                        <p>Partida: {{ partida.partida }}</p>
                        <p>Monto programado: {{ partida.monto_programado.toLocaleString('es-MX', {
                            style: 'currency',
                            currency: 'MXN',
                            minimumFractionDigits: 2, // Asegurar dos decimales
                            maximumFractionDigits: 2,
                        }) }}</p>
                        <div class="d-flex ">
                            <button class="btn btn-danger mx-2" @click="removeRecordFromArray(partida)">Eliminar</button>
                        </div>
                    </div>
                </section>
            </div>

            <div v-if="activeTab === 'modify'" >
                <p class="h5 ">Seleccione el ejercicio</p>
                <SourcePicker :fuentes="fuentes" :handleSelect="handleSelect"/>
                <section class="mt-2">
                    <p class="h5 ">Ingrese el ejercicio</p>
                    <input type="text" class="form-control" placeholder="Ejercicio" aria-label="Ejercicio" v-model="ejercicio" required>
                </section>
                <button class="btn rojo mt-2" @click="handleGetPartidasProgramadas">Obtener partidas programadas</button>
                <hr/>
                <AgGridVue
                    :rowData="partidasProgramadasDb"
                    :columnDefs="colDefs"
                    style="height: 340px; width: 100%;"
                    class="ag-theme-quartz mb-5"
                    :frameworkComponents="{ ActionRenderer }"
                    :animateRows="true"
                    :rowHeight="48"
                    @cellValueChanged="onCellValueChanged"
                    @record-deleted="handleRecordDeleted"
                />

                <button v-if="editedRecords.length > 0" class="btn rojo mt-2" @click="handleEdit">Guardar registros editados</button>
            </div>
            
            <hr/>
            <p v-if="activeTab === 'modify'">Total programado: {{ computedTotalProgramadoDb }} MXN</p>
            <p v-if="activeTab === 'new'">Total programado: {{ computedTotalProgramado }} MXN</p>
            <button v-if="activeTab === 'new'" class="btn rojo mt-2" @click="handleSave">Guardar programacion del ejercicio</button>
            <button v-if="activeTab === 'modify'" class="btn rojo mt-2" @click="generateProgramacionRubros">Generar programación por rubro</button>
            <Notifications position="bottom left" />
        </main>
    </div>
</template>

<script setup>
import SourcePicker from './homepage_components/SourcePicker.vue';
import { ref, defineProps, watch, onBeforeMount, defineComponent } from 'vue';
import { Notifications, notify } from '@kyvg/vue3-notification';
import axios from 'axios';
import VueSelect from 'vue-select';
import "vue-select/dist/vue-select.css"
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-quartz.css";
import { AgGridVue } from "ag-grid-vue3";
import ActionRenderer from '../components/auxiliares/ActionRenderer.vue';
import { computed } from 'vue';

const props = defineProps({
    user: Object,
    fuentes: Array,
    periodos: Array,
    partidas: Array
});

onBeforeMount( async () => {
    partidasFormateadas.value = props.partidas.map(partida => ({
        ...partida,
        label: `${partida.partida} - ${partida.descripcion}`
    }));

    try{
        const response = await axios.get('/get_rubros');
        rubros.value = response.data;
        console.log(rubros.value);
    }catch(e){
        console.log(e);
    }
})

const colDefs = ref([
    {
        field: 'descripcion',
        headerName: 'Partida',
        filter: true,
        sortable: true,
        flex: 1,
        cellClass: (params) => params.value ? 'invalidClass' : 'validClass',
    },
    {
        field: 'monto_programado',
        headerName: 'Monto programado (MXN)',
        filter: true,
        sortable: true,
        editable: true,
        flex: 1,
        cellClass: (params) => params.value ? 'partial-cell' : 'invalidClass',
        valueFormatter: formatCurrency,
    },
    {
        field: 'nombre_capitulo',
        headerName: 'Capitulo',
        filter: true,
        sortable: true,
        flex: 1,
        cellClass: 'invalidClass',
    },
    {
        field: 'ejercicio',
        headerName: 'Ejercicio',
        filter: true,
        sortable: true,
        flex: 1, 
        cellClass: 'invalidClass',
    },
    { field: 'actions', headerName: 'Acciones', cellRenderer: ActionRenderer, flex: 1 },
]);

function formatCurrency(params) {
    if (!params.value) return '';

    const number = parseFloat(params.value.replace(/[^0-9.-]+/g, ''));
    if (isNaN(number)) return '';

    const formattedCurrency = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(number);
    return `${formattedCurrency} MXN`;
}

const ejercicio = ref(null);
const partidasFormateadas = ref([]);
const partidaSeleccionada = ref(null);
const selectedSource = ref(null);   
const partidasProgramadas = ref([]);
const monto = ref(null);
const activeTab = ref('new');
const partidasProgramadasDb = ref([]);
const editedRecords = ref([]);
const rubros = ref([]);

const computedTotalProgramado = computed(() => {
    return partidasProgramadas.value.reduce((total, partida) => total + parseFloat(partida.monto_programado), 0).toLocaleString('es-MX', {
        style: 'currency',
        currency: 'MXN',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
});

const computedTotalProgramadoDb = ref(calculateTotalProgramadoDb());

watch(editedRecords, () => {
    computedTotalProgramadoDb.value = calculateTotalProgramadoDb();
});

const handleRecordDeleted = async () => {
    alert("Se ha eliminado la partida programada");
  await getPartidasProgramadas();
};

function calculateTotalProgramadoDb() {
    return partidasProgramadasDb.value.reduce((total, partida) => total + parseFloat(partida.monto_programado), 0).toLocaleString('es-MX', {
        style: 'currency',
        currency: 'MXN',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

const handleSelect = async (source) => {
  selectedSource.value = source.id;
}

const handleTabClick = async (tab) => {
    activeTab.value = tab;
}

const handleProgramar = async () => {
    if(monto.value <= 0){
        notify({
            title: 'Error al programar recursos',
            text: 'El monto a programar debe ser mayor a cero',
            type: 'error',
            duration: 5000,
            speed: 1000,
        });
        return;
    }

    if(selectedSource.value && ejercicio.value && partidaSeleccionada.value){
        partidasProgramadas.value.push({
            idfuente: selectedSource.value,
            ejercicio: ejercicio.value,
            idpartida: partidaSeleccionada.value.id,
            descripcion: partidaSeleccionada.value.descripcion,
            idcapitulo: partidaSeleccionada.value.idcapitulo,
            monto_programado: monto.value
        });
    }else{
        notify({
            title: 'Error al programar recursos',
            text: 'Debe completar todos los campos',
            type: 'error',
            duration: 5000,
            speed: 1000,
        });
    }
}

const removeRecordFromArray = (record) => {
    partidasProgramadas.value = partidasProgramadas.value.filter(partida => partida.id !== record.id);
}

const handleSave = async () => {
    if(partidasProgramadas.value.length <= 0){
        notify({
            title: 'Error al guardar programacion',
            text: 'Debe completar la programacion',
            type: 'error',
            duration: 5000,
            speed: 1000,
        });
        return;
    }

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

const handleGetPartidasProgramadas = async () => {
    await getPartidasProgramadas();
}

const getPartidasProgramadas = async () => {
    
    if(selectedSource.value && ejercicio.value){
        try {
            const response = await axios.get('/get_partidas_programadas', {
                params: {
                    ejercicio: ejercicio.value,
                    source: selectedSource.value
                }
            });
            partidasProgramadasDb.value = response.data;
            computedTotalProgramadoDb.value = calculateTotalProgramadoDb();
            console.log(response.data);
        } catch (e) {
            notify({
                title: 'Error al obtener partidas programadas',
                text: 'Error: ' + e.response.data.message,
                type: 'error',
                duration: 5000,
            })
        }
    }else{
        notify({
            title: 'Error al obtener partidas programadas',
            text: 'Debe completar todos los campos',
            type: 'error',
            duration: 5000
        })
          return;  
    }
}

const onCellValueChanged = async (params) => {
  const updatedData = params.data;
  editedRecords.value.push(updatedData);
  computedTotalProgramadoDb.value = calculateTotalProgramadoDb();
};

const handleEdit = async () => {
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
        const response = await axios.post('/edit_partida_programada', formData, {
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
        text: 'Error: ' + e.response.data.message,
        type: 'error',
        duration: 5000,
        });
    }
};

const generateProgramacionRubros = async () => {
    //agrupa las partidas_programadas por idcapitulo
    const partidasProgramadasByCapitulo = partidasProgramadasDb.value.reduce((acc, partida) => {
        const cap = partida.idcapitulo;
        if (acc[cap]) {
            acc[cap].push(partida);
        } else {
            acc[cap] = [partida];
        }
        return acc;
    }, {});

    const rubrosProgramados = rubros.value.map(rubro => {
        const rubroProgramado = partidasProgramadasByCapitulo[rubro.id];
        if (rubroProgramado) {
            return {
                ...rubro,
                ejercicio: ejercicio.value,
                idfuente: selectedSource.value,
                monto_programado: rubroProgramado.reduce((total, partida) => total + parseFloat(partida.monto_programado), 0),
            };
        } else {
            return {
                ...rubro,
                ejercicio: ejercicio.value,
                idfuente: selectedSource.value,
                monto_programado: 0,
            };
        }
    });


    try{
        const response = await axios.post('/generate_programacion_rubros', {
            rubros: rubrosProgramados,
        });
        
        if(response.status === 200){
            notify({
                title: 'Programación generada exitosamente',
                text: 'La programación se ha generado correctamente',
                type: 'success',
                duration: 5000,
            })
        }else{
            notify({
                title: 'Error al generar programación',
                text: 'Error: ' + response.data.message,
                type: 'error',
                duration: 5000,
            })
        }
    }catch(e){
        notify({
            title: 'Error al generar programación',
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

.partial-cell{
    background-color: #fff9c2;
    color: #ffbc05;
    font-weight: bold;
}

.invalidClass{
    color: #eeeeee;
    background-color: #8d8d8d;
    font-weight: bold;
}

.rojo{
    background-color: #9F2241;
    color: #eeeeee;
}

a{
    color: #9F2241;
}

</style>