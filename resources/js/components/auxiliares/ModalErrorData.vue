<template>
    <div ref="modal" class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabe" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Errores encontrados en los registros cargados</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Verifique los registros que están causando error en el sistema</h5>
                    <p>Verifique y agregue las regitros clave para poder continuar</p>
                    <hr/>
                    <div v-for="(group, key) in errorData" :key="key">
                        <p class="fs-5">Registro clave causando error: <span style="font-weight: bold;">{{ key.toString().toUpperCase() }}</span></p>
                        <AgGridVue
                            :rowData="group"
                            :columnDefs="colDefs"
                            :pagination="agProps.pagination"
                            :paginationPageSize="agProps.paginationPageSize"
                            :paginationPageSizeSelector="agProps.paginationPageSizeSelector"
                            style="height: 500px"
                            class="ag-theme-quartz mb-5"
                        />
                        <button v-if="key.toString().toLowerCase() !== 'partida'" class="btn btn-primary" @click="handleSaveNewRecords(group, key)">Agregar registro clave la tabla {{ key.toString().toUpperCase() }}</button>
                        <p v-else style="color: red; font-size: 1.5rem;">La partida se debe de agregar manualemente desde su catalogo</p>
                    </div>                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal" @click="handelCancelData">Cancelar</button> 
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, defineExpose, defineProps, computed, defineEmits, watch } from 'vue';
import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the Data Grid
import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the Data Grid
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component
import { U013, ALE, E001, S200, SaNAS } from '../../lib/Headers.js';
import { notify } from '@kyvg/vue3-notification';
import axios from 'axios';

const props = defineProps({
    errorData: Object,
    selectedSource: Number
});

const colDefs = ref([]);

watch(props, (newValue) => {
    if(props.selectedSource === 1){
        colDefs.value = U013;
    }

    if(props.selectedSource === 2){
        colDefs.value = S200;
    }

    if(props.selectedSource === 3){
        colDefs.value = SaNAS;
    }
    
    if(props.selectedSource === 4){
        colDefs.value = ALE;
    }

    if(props.selectedSource === 5){
        colDefs.value = E001;
    }

});

const agProps = ref({
  pagination: true,
  paginationPageSize: 500,
  paginationPageSizeSelector: [500, 1000, 5000],
});

const getClass = (header) => {
    var className = '';

    switch (header) {
        case 'PARCIAL':
            className = 'partial-cell';
            break;
        case 'DEPOSITOS':
            className = 'deposit-cell';
            break;
        case 'RETIROS':
            className = 'withdrawal-cell';
            break;
        case 'SALDO':
            className = 'balance-cell';
            break;
        default:
            className = '';
    }
    return className;
}
const modal = ref(null);
let modalInstance = null;

const openModal = () => {
    modalInstance.show();
};


onMounted(() => {
    modalInstance = new bootstrap.Modal(modal.value);
});

defineExpose({
    openModal
});

const handleSaveNewRecords = async (group, key) => {
    
    if(key.toString().toLowerCase() === 'partida') {
        const newPartidas = group.map(item => {

            return {
                partida: item.partida,
                aportacion_federal: 0,
                aportacion_estatal: 0,
                descripcion: item.nombrepartida,
                req_auth_a_imb: 0,
                req_auth_af: 0,
                remuneracionPersonal: 0,
                adminInsumosMed: 0,
                gastosOperacion: 0,
                idcapitulo: null
            }
        });

        try{
            const response = await axios.post('/save_new_partidas', {
                newPartidas: JSON.stringify(newPartidas)
            });
            if(response.status === 200){
                notify({
                    title: 'Partidas guardadas exitosamente',
                    text: 'La partida se ha guardado correctamente',
                    type: 'success',
                    duration: 5000,
                })

                console.log(response);
            }else{
                notify({
                    title: 'Error al guardar partidas',
                    text: 'Error: ' + response.message,
                    type: 'error',
                    duration: 5000,
                })
            }
        }catch(e){
            notify({
                title: 'Error al guardar partidas',
                text: 'Error: ' + e.message,
                type: 'error',
                duration: 5000,
            })
        }
        //console.log(newPartidas);
    }

    if(key.toString().toLowerCase() === 'rfc') {
        const newProveedores= group.map(item => {

        return {
            rfc: item.rfc,
            proveedor: item.proveedor,
            numero_cuenta_proovedor: item.numero_cuenta_proovedor,
        }
        });

        try{
            const response = await axios.post('/save_new_provedores', {
                newProveedores: JSON.stringify(newProveedores)
            });
            if(response.status === 200){
                notify({
                    title: 'Proveedores guardados exitosamente', 
                    text: 'Los proveedores se han guardado correctamente',
                    type: 'success',
                    duration: 5000,
                })

                console.log(response);
            }else{
                notify({
                    title: 'Error al guardar los proveedores',
                    text: 'Error: ' + response.message,
                    type: 'error',
                    duration: 5000,
                })
            }
        }catch(e){
            notify({
                title: 'Error al guardar los proveedores',
                text: 'Error: ' + e.message,
                type: 'error',
                duration: 5000,
            })
        }
    }

    if(key.toString().toLowerCase() === 'clue') {
        const newClues = group.map(item => {

        return {
            clue: item.clue,
            nombre_clue: item.nombre_clue,
        }
        });

        try{
            const response = await axios.post('/save_new_clue', {
                newClues: JSON.stringify(newClues)
            });
            if(response.status === 200){
                notify({
                    title: 'CLUES guardadas exitosamente',
                    text: 'Las clues se han guardado correctamente',
                    type: 'success',
                    duration: 5000,
                })

                console.log(response);
            }else{
                notify({
                    title: 'Error al guardar clues',
                    text: 'Error: ' + response.message,
                    type: 'error',
                    duration: 5000,
                })
            }
        }catch(e){
            notify({
                title: 'Error al guardar clues',
                text: 'Error: ' + e.message,
                type: 'error',
                duration: 5000,
            })
        }
    }
}

</script>

<style scoped>
.custom-cell {
    display: flex;
    align-items: center;
  }

  .deposit-cell {
    background-color: #e6f7e6; /* Fondo verde claro */
    color: #2e7d32; /* Texto verde */
    font-weight: bold;
  }
  
  .withdrawal-cell {
    background-color: #fdecea; /* Fondo rojo claro */
    color: #c62828; /* Texto rojo */
    font-weight: bold;
  }
  
  .balance-cell {
    background-color: #e3f2fd; /* Fondo azul claro */
    color: #1565c0; /* Texto azul */  font-weight: bold;
  }
</style>