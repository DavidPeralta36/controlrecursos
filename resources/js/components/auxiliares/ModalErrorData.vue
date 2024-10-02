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
import { U013, ALE, E001 } from '../../lib/Headers.js';

const props = defineProps({
    errorData: Object,
    selectedSource: Number
});

const colDefs = ref([]);

watch(props, (newValue) => {
    if(props.selectedSource === 1){
        colDefs.value = U013;
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