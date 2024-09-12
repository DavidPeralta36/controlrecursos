<template>
    <div ref="modal" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Datos preliminares a cargar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Verifique los registros que está por guardar en el sistema</h5>
                    <p>Los registros se pueden modificar más tarde</p>
                    <hr/>
                    <AgGridVue
                        :rowData="filteredData"
                        :columnDefs="colDefs"
                        :pagination="agProps.pagination"
                        :paginationPageSize="agProps.paginationPageSize"
                        :paginationPageSizeSelector="agProps.paginationPageSizeSelector"
                        style="height: 500px"
                        class="ag-theme-quartz mb-5"
                        :frameworkComponents="{ customCellRenderer }"
                    >
                    </AgGridVue>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal" @click="handelCancelData">Cancelar</button> 
                    <button type="button" class="btn btn-primary">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, defineExpose, defineProps, computed, defineEmits } from 'vue';
import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the Data Grid
import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the Data Grid
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component

const props = defineProps({
    preliminarData: Array,
    selectedSource: Number
});

const emit = defineEmits(['cancelData']);

const colDefs = ref([]);

const agProps = ref({
  pagination: true,
  paginationPageSize: 500,
  paginationPageSizeSelector: [500, 1000, 5000],
});

const filteredData = computed(() => {
    if (props.preliminarData.length === 0) return [];

    const headers = props.preliminarData[0].filter(header => header !== null);

    colDefs.value = headers.map((header, index) => ({
        field: `col${index}`,
        headerName: header,
        filter: true,
        sortable: true,
        valueFormatter: (header === 'DEPOSITOS' || header === 'RETIROS' || header === 'SALDO' || header === 'PARCIAL') ? formatCurrency : null,
        cellRenderer: (header === 'DEPOSITOS' || header === 'RETIROS' || header === 'SALDO') ? 'customCellRenderer' : null,
        cellClass: (params) => params.value ? getClass(header) : ''
    }));

        
    if(props.selectedSource === 1){
        return props.preliminarData.slice(1).map(row => {
            return row.reduce((acc, value, index) => {
                
                if (index !== 0) {
                    acc[`col${index - 1}`] = value; 
                }
                return acc;
            }, {});
        });
    }
    
    if(props.selectedSource === 4){
        colDefs.value.push({ field: 'col26', headerName: 'MES DE SERVICIO', filter: true, sortable: true });
        
        return props.preliminarData.slice(1).map(row => {
            return row.reduce((acc, value, index) => {
                
                if (index !== 0) {
                    acc[`col${index - 1}`] = value; 
                }
                return acc;
            }, {});
        });
    }

    if(props.selectedSource === 5){
        return props.preliminarData.slice(1).map(row => {
            return row.reduce((acc, value, index) => {
                acc[`col${index}`] = value;
                return acc;
            }, {});
        });
    }
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

function formatCurrency(params) {
    if (params.value === null || params.value === undefined) return '';

    let number;

    if (typeof params.value === 'number') {
        number = params.value;
    } else {
        number = parseFloat(params.value.replace(/[^0-9.-]+/g, ''));
    }

    if (isNaN(number)) return ''; 

    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(number); 
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

onMounted(() => {
    modalInstance = new bootstrap.Modal(modal.value);
});

defineExpose({
    openModal
});

const handelCancelData = () => {
    emit('cancelData');
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