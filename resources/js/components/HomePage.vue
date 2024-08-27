<template >
    <div class="h-100 w-100 d-flex flex-column">
        <div class="mt-5">
            <p class="h3 mx-3 nunito font-weight-bold">Reporte general por fuentes de financiamiento</p>
            <hr/>
            <SourcePicker :fuentes="fuentes" :handleSelect="handleSelect"/>
            <ControlsGeneralReport 
              :dates="dates" 
              :rangeSearch="rangeSearch" 
              :selectedPeriod="selectedPeriod" 
              :periodos="periodos" 
              :handleDateChange="handleDateChange" 
              :handleGenReport="handleGenReport"
              :handleSelectPeriod="handleSelectPeriod"
              @update:rangeSearch="toggleSetRangeSearch"
              @update:selectedPeriod="handleSelectPeriod"/>
            <hr class="mt-2"/>
            <div :class="!loads.loaded ? 'imgContainer' : 'tableContainer'">
              <div v-if="loads.loading" class="text-center" ref="loadingDiv">
                <div ref="spinner" class="spinner-border" role="status">
                  <span class="sr-only">Loading...</span>
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
                  :pagination="pagination"
                  :paginationPageSize="paginationPageSize"
                  :paginationPageSizeSelector="paginationPageSizeSelector"
                  style="height: 500px"
                  class="ag-theme-quartz"
                  :frameworkComponents="{ customCellRenderer }"
                >
                </AgGridVue>
              </div>
            </div>
        </div>
        <Notifications position="bottom left" />
    </div>
</template>

<script setup>
import { ref, defineProps, nextTick } from 'vue';
import '../../sass/app.scss';
import SourcePicker from './homepage_components/SourcePicker.vue';
import ControlsGeneralReport from './homepage_components/ControlsGeneralReport.vue';
import { Notifications, notify } from '@kyvg/vue3-notification';
import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the Data Grid
import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the Data Grid
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component
import {
  animateSkeletonOut,
  animateLoadingOut,
  startAnimations,
} from '../utils/animations.js';


const props = defineProps({
  user: Object,
  fuentes: Array,
  periodos: Array
});
const dates = ref({
  startDate: new Date(),
  startDateSelected: false,
  endDate: new Date(),
  endDateSelected: false
})
const rangeSearch = ref(false);
const loads = ref({
  loading: false,
  skeleton: true,
  loaded: false
})
const registros = ref([]);
const selectedSource = ref(null);
const colDefs = ref([
  { field: 'fechas', headerName: 'Fecha', filter: true, sortable: true },
  { field: 'mes', headerName: 'Mes', filter: true, sortable: true },
  { field: 'forma_pago', headerName: 'Forma de pago', filter: true, sortable: true },
  { field: 'rfc', headerName: 'RFC', filter: true, sortable: true },
  { field: 'proveedor', headerName: 'Proveedor', filter: true, sortable: true  },
  { field: 'factura', headerName: 'Factura', filter: true, sortable: true  },
  { field: 'parcial', headerName: 'Parcial', valueFormatter: formatCurrency  },
  { 
    field: 'depositos', 
    headerName: 'Depositos', 
    valueFormatter: formatCurrency, 
    cellClass: (params) => params.value ? 'deposit-cell' : '',
    cellRenderer: 'customCellRenderer' 
  },
  { 
    field: 'retiros', 
    headerName: 'Retiros', 
    valueFormatter: formatCurrency, 
    cellClass: (params) => params.value ? 'withdrawal-cell' : '',
    cellRenderer: 'customCellRenderer' 
  },
  { 
    field: 'saldo', 
    headerName: 'Saldo', 
    valueFormatter: formatCurrency, 
    cellClass: (params) => params.value ? 'balance-cell' : '',
    cellRenderer: 'customCellRenderer' 
  },
  { field: 'r', headerName: 'Rubro', filter: true, sortable: true }, 
  { field: 'partida', headerName: 'Partida', filter: true, sortable: true  },
  { field: 'fecha_factura', headerName: 'Fecha de factura', filter: true, sortable: true  },
  { field: 'folio_fiscal', headerName: 'Folio fiscal', filter: true, sortable: true  },
  { field: 'tipo_adjudicacion', headerName: 'Tipo de adjudicación', filter: true, sortable: true  },
  { field: 'num_adj_contrato', headerName: 'Numero de adjudicación o contrato', filter: true, sortable: true  },
  { field: 'orden_servicio_compra', headerName: 'Orden de servicio o compra', filter: true, sortable: true  },
  { field: 'num_suficiencia_presupuestal', headerName: 'Numero de suficiencia presupuestal', filter: true, sortable: true  },
  { field: 'clc', headerName: 'CLC', filter: true, sortable: true  },
  { field: 'poliza', headerName: 'Poliza', filter: true, sortable: true  },
  { field: 'numero_cuenta_proovedor', headerName: 'Numero de cuenta de proveedor', filter: true, sortable: true  },
  { field: 'referencia_bancaria', headerName: 'Referencia bancaria', filter: true, sortable: true  },
  { field: 'nombre_clue', headerName: 'CLUE', filter: true, sortable: true  },
  { field: 'nombrepartida', headerName: 'Aplica en' },
  { field: 'mes_servicio', headerName: 'Mes de servicio' },
  { field: 'metodo_pago', headerName: 'Metodo de pago', filter: true, sortable: true  },
  { field: 'ejercicio', headerName: 'Ejercicio' },
]);
const pagination = ref(true);
const paginationPageSize = ref(500);
const paginationPageSizeSelector = ref([200, 500, 1000]);
const selectedPeriod = ref({ejercicio: "2024"});
const loadingText = ref(null); 
const spinner = ref(null); 
const skeletonDiv = ref(null);
const loadingDiv = ref(null);
const tableDiv = ref(null);

const handleSelect = (source) => {
  selectedSource.value = source.id;
}

const handleDateChange = (modelData, field) => {
  if (field === 'startDate') {
    dates.value.startDate = modelData;
    dates.value.startDateSelected = true;
  } else if (field === 'endDate') {
    dates.value.endDate = modelData;
    dates.value.endDateSelected = true;
  }
};

const getReport = async () => {
  const response = await axios.get('/report', {
    params: {
      beginingDate: dates.value.startDate,
      endDate: dates.value.endDate,
      source: selectedSource.value
    }
  });
  registros.value = response.data;
  await nextTick();
  await animateLoadingOut(loadingDiv, tableDiv, loads);
}

const getReportByPeriod = async () => {
  const response = await axios.get('/report_by_period', {
    params: {
      source: selectedSource.value,
      period: selectedPeriod.value.ejercicio
    }
  });
  registros.value = response.data;
  await nextTick();
  await animateLoadingOut(loadingDiv, tableDiv, loads);
}

const handleGenReport = async () => {
  if (rangeSearch.value) {
    if (dates.value.startDateSelected && dates.value.endDateSelected && selectedSource.value) {
      try {
        await animateSkeletonOut(skeletonDiv, loadingDiv, loads);
        startAnimations(loadingText, spinner);
        await getReport();
      } catch (e) {
        console.log(e);
      }
    } else {
      notify({
        title: 'Formulario incompleto',
        text: 'Debe completar los campos de fecha de inicio, fin y fuente de financiamiento para generar el reporte',
        type: 'error',
        duration: 5000,
        speed: 1000,
      });
    }
  } else {
    if (selectedSource.value && selectedPeriod.value) {
      try {
        await animateSkeletonOut(skeletonDiv, loadingDiv, loads);
        startAnimations(loadingText, spinner);
        await getReportByPeriod();
      } catch (e) {
        console.log(e);
      }
    } else {
      notify({
        title: 'Formulario incompleto',
        text: 'Debe completar el campo fuente de financiamiento y el periodo para generar el reporte',
        type: 'error',
        duration: 5000,
        speed: 1000,
      });
    }
  }
};

const toggleSetRangeSearch = () => {
  rangeSearch.value =  !rangeSearch.value;
}

const handleSelectPeriod = (period) => {
  selectedPeriod.value = period;
}

function formatCurrency(params) {
    if (!params.value) return ''; // Manejar valores nulos o indefinidos
    
    const number = parseFloat(params.value.replace(/[^0-9.-]+/g, '')); // Convertir a número
    if (isNaN(number)) return ''; // Manejar valores que no se pueden convertir a número

    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(number); 
}

function customCellRenderer(params) {
  const value = params.valueFormatted ? params.valueFormatted : params.value;
  
  // Si no hay valor, retornar una cadena vacía o solo el valor sin estilos
  if (!value) {
    return '';
  }

  // Retornar el valor con estilos sólo si hay contenido
  return `
    <span class="custom-cell">
      ${value}
    </span>
  `;
}


</script>

<style lang="scss">
.nunito{
  font-family: 'Nunito', sans-serif;
  font-weight: 200;
  color: #691C32;
}
.imgContainer{
  width: 100%;
  height: 50vh;
  display: flex;
  justify-content: center;
  align-items: center;
}
.img{
  width: 300px;
  height: 300px;
}


.tableContainer {
  width: 100%;
  height: 50vh;
}


.deposit-cell {
  background-color: #e6f7e6; /* Fondo verde claro */
  color: #2e7d32; /* Texto verde */
  font-weight: bold;
  border-radius: 10px;
}

/* Estilo para las celdas de retiros */
.withdrawal-cell {
  background-color: #fdecea; /* Fondo rojo claro */
  color: #c62828; /* Texto rojo */
  font-weight: bold;
}

/* Estilo para las celdas de saldo */
.balance-cell {
  background-color: #e3f2fd; /* Fondo azul claro */
  color: #1565c0; /* Texto azul */  font-weight: bold;
}

/* Estilo común para todas las celdas personalizadas */
.custom-cell {
  display: flex;
  align-items: center;
}
</style>