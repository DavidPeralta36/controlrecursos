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

            <div v-if="activeTab === 'modify'" >
                <p class="h5 ">Seleccione el ejercicio</p>
                <SourcePicker :fuentes="fuentes" :handleSelect="handleSelect"/>
                <section class="mt-2">
                    <p class="h5 ">Ingrese el ejercicio</p>
                    <input type="text" class="form-control" placeholder="Ejercicio" aria-label="Ejercicio" v-model="ejercicio" required>
                </section>
                <button class="btn btn-primary mt-2" @click="handleGetPartidasProgramadas">Obtener partidas programadas</button>
                <hr/>
                <AgGridVue
                    :rowData="partidasProgramadasDb"
                    :columnDefs="colDefs"
                    style="height: 500px"
                    class="ag-theme-quartz mb-5"
                    :frameworkComponents="{ actionRenderer }"
                />
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
import { ref, defineProps, watch, onBeforeMount, defineComponent } from 'vue';
import { Notifications, notify } from '@kyvg/vue3-notification';
import axios from 'axios';
import VueSelect from 'vue-select';
import "vue-select/dist/vue-select.css"
import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the Data Grid
import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the Data Grid
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component

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

const colDefs = ref([
    {
        field: 'descripcion',
        headerName: 'Partida',
        filter: true,
        sortable: true,
        flex: 1,
    },
    {
        field: 'monto_programado',
        headerName: 'Monto programado',
        filter: true,
        sortable: true,
        editable: true,
        flex: 1,
    },
    {
        field: 'nombre_capitulo',
        headerName: 'Capitulo',
        filter: true,
        sortable: true,
        flex: 1,
    },
    {
        field: 'ejercicio',
        headerName: 'Ejercicio',
        filter: true,
        sortable: true,
                flex: 1, 
    },
    { field: 'actions', headerName: 'Acciones', cellRenderer: actionRenderer, flex: 1 },
]);

const ejercicio = ref(null);
const partidasFormateadas = ref([]);
const partidaSeleccionada = ref(null);
const selectedSource = ref(null);   
const partidasProgramadas = ref([]);
const monto = ref(null);
const activeTab = ref('new');
const partidasProgramadasDb = ref([]);

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
const actionRenderer = defineComponent({
  template: `
    <div>
      <button @click="editUser" class="btn btn-warning btn-sm mx-2">Editar</button>
      <button @click="deleteUser" class="btn btn-danger btn-sm">Eliminar</button>
    </div>
  `,
  props: ['params'],
  setup(props) {
    const editUser = () => {
      console.log("Editar:", props.params.data);
    };
    
    const deleteUser = () => {
      console.log("Eliminar:", props.params.data);
    };

    return {
      editUser,
      deleteUser,
    };
  }
});

const handleGetPartidasProgramadas = async () => {
    await getPartidasProgramadas();
}

const getPartidasProgramadas = async () => {
    try {
        const response = await axios.get('/get_partidas_programadas', {
            params: {
                ejercicio: ejercicio.value,
                source: selectedSource.value
            }
        });
        partidasProgramadasDb.value = response.data;
        console.log(response.data);
    } catch (e) {
        notify({
            title: 'Error al obtener partidas programadas',
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