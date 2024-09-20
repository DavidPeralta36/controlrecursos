<template >
    <div class="h-100 w-100 d-flex flex-column container con">
        <div>
            <p class="h3 nunito font-weight-bold">Reporte general por fuentes de financiamiento</p>
            <hr/>
            <div style="min-height: 16vh">
              <SourcePicker :fuentes="fuentes" :handleSelect="handleSelect"/>
            </div>
            <ControlsGeneralReport 
              :dates="dates" 
              :rangeSearch="rangeSearch" 
              :selectedPeriod="selectedPeriod" 
              :periodos="periodos" 
              :handleDateChange="handleDateChange" 
              :handleGenReport="handleGenReport"
              :handleSelectPeriod="handleSelectPeriod"
              @update:rangeSearch="toggleSetRangeSearch"
              @update:selectedPeriod="handleSelectPeriod"
              @update:selectedMonth="handleSelectMonth"/>
            <hr class="mt-2"/>
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
                  @firstDataRendered="onFirstDataRendered"
                  @filterChanged="onFilterChanged"
                >
                </AgGridVue>
              </div>
            </div>
            <hr class="mt-2"/>
            <div v-if="loads.loaded" class="informe">
              <div class="container p-2">
                <h4 class="nunito-bold">Servicios Estatales de salud</h4>
                <h5 class="nunito">Informe del ejercicio de los Recursos Federales para la prestacion gratuita de servicios de salud</h5>
                <hr/>
                <p>Total de registros: <strong>{{ registros.length }}</strong></p>
                <p>Total de retiros: <strong>{{ formatearCantidad(totales.retiros) }}</strong></p>
                <p>Total de depósitos: <strong>{{ formatearCantidad(totales.depositos) }}</strong></p>
                <hr/>
                <p>Total de saldo: <strong>{{ formatearCantidad(totales.saldo) }}</strong></p>
              </div>
            </div>
        </div>
        <Notifications position="bottom left" />
    </div>
</template>

<script setup>
import { ref, defineProps, nextTick, watch } from 'vue';
import '../../sass/app.scss';
import SourcePicker from './homepage_components/SourcePicker.vue';
import ControlsGeneralReport from './homepage_components/ControlsGeneralReport.vue';
import { Notifications, notify } from '@kyvg/vue3-notification';
import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the Data Grid
import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the Data Grid
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component
import axios from 'axios';
import {
  animateSkeletonOut,
  animateLoadingOut,
  startAnimations,
} from '../utils/animations.js';
import { U013, ALE, E001 } from '../lib/Headers.js';


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
const totales = ref({
  registros: 0,
  depositos: 0,
  retiros: 0,
  saldo: 0
});
const colDefs = ref();
const agProps = ref({
  pagination: true,
  paginationPageSize: 500,
  paginationPageSizeSelector: [200, 500, 1000],
});
const registros = ref([]);
const selectedSource = ref(null);
const selectedPeriod = ref({ejercicio: "Periodo requerido *"});
const loadingText = ref(null); 
const spinner = ref(null); 
const skeletonDiv = ref(null);
const loadingDiv = ref(null);
const tableDiv = ref(null);
const selectedMonth = ref(null);

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

const handleSelectMonth = (month) => {
  selectedMonth.value = month.label;
  console.log(selectedMonth.value);
}

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
  const formattedStartDate = dates.value.startDate.toISOString().split('T')[0];
  const formattedEndDate = dates.value.endDate.toISOString().split('T')[0];

  const response = await axios.get('/report', {
    params: {
      beginingDate: formattedStartDate,
      endDate: formattedEndDate,
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

  var saldoIterado = 0
  const registrosProcesados = response.data.map((item, index, array) => {
    if (index === 0) {
      saldoIterado = parseFloat(item.saldo);
      return { ...item };
    } else {
      const saldoAnterior = saldoIterado;
      const ingresosActuales = parseFloat(item.depositos) || 0;
      const egresosActuales = parseFloat(item.retiros) || 0;
      
      const nuevoSaldo = saldoAnterior + ingresosActuales - egresosActuales;
      saldoIterado = parseFloat(nuevoSaldo);

      return {
          ...item, // Copia todas las propiedades del objeto original
          saldo: nuevoSaldo.toFixed(2).toString() // Modifica solo el saldo
      };
    }
  });

  registros.value = registrosProcesados;
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

        totales.value.registros = registros.value.length;
        //totales.value.depositos = registros.value.reduce((total, item) => total + parseFloat(item.depositos || 0), 0).toFixed(2);
        //totales.value.retiros = registros.value.reduce((total, item) => total + parseFloat(item.retiros || 0), 0).toFixed(2);
        //totales.value.saldo = totales.value.depositos - totales.value.retiros;
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
    if (selectedSource.value && selectedPeriod.value.ejercicio !== 'Periodo requerido *') {
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

const formatearCantidad = (value) => {
  const number = parseFloat(value); // Convertir a número flotante
  if (isNaN(number)) return ""; // Verificar si no es un número
  return new Intl.NumberFormat("es-MX", {
    style: "currency",
    currency: "MXN",
    minimumFractionDigits: 2, // Asegurar dos decimales
    maximumFractionDigits: 2,
  }).format(number);
};

const onFirstDataRendered = (params) => {
  calculateTotals(params); 
  //calculateFilteredTotals(params);
}

const onFilterChanged = async (params) => {
  calculateFilteredTotals(params);
}

const isValidNumber = (value) => {
  return !isNaN(parseFloat(value)) && isFinite(value);
};

const calculateTotals = async (params) => {
  let saldoTotal = 0;
  let ingresosSum = 0;
  let egresosSum = 0;
  let isFirstNode = true; 

  const firstNode = params.api.getDisplayedRowAtIndex(0);
  if (firstNode) {
    saldoTotal = isValidNumber(firstNode.data.saldo) ? parseFloat(firstNode.data.saldo) : 0;
    ingresosSum = isValidNumber(firstNode.data.depositos) ? parseFloat(firstNode.data.depositos) : 0;
    //egresosSum = isValidNumber(firstNode.data.retiros) ? parseFloat(firstNode.data.retiros) : 0;
  }

  params.api.forEachNode((node) => {
    if (isFirstNode) {
      isFirstNode = false;
      return; 
    }

    const depositos = isValidNumber(node.data.depositos) ? parseFloat(node.data.depositos) : 0;
    const retiros = isValidNumber(node.data.retiros) ? parseFloat(node.data.retiros) : 0;


    ingresosSum += depositos;
    egresosSum += retiros;

    saldoTotal = (saldoTotal + depositos) - retiros;
  });

  await nextTick();
  totales.value.depositos = ingresosSum;
  totales.value.retiros = egresosSum;
  totales.value.saldo = saldoTotal;
};

const calculateFilteredTotals = async (params) => {
  let filteredIngresosSum = 0;
  let filteredEgresosSum = 0;
  let filteredSaldoTotal = 0;
  let filteredFirstNode = true;

  const firstNode = params.api.getDisplayedRowAtIndex(0);
  if (firstNode) {
    filteredSaldoTotal = isValidNumber(firstNode.data.saldo) ? parseFloat(firstNode.data.saldo) : 0;
    filteredIngresosSum = isValidNumber(firstNode.data.depositos) ? parseFloat(firstNode.data.depositos) : 0;
    //filteredEgresosSum = isValidNumber(firstNode.data.retiros) ? parseFloat(firstNode.data.retiros) : 0;
  }

  params.api.forEachNodeAfterFilter((node) => {
    if(filteredFirstNode) {
      filteredFirstNode = false;
      return;
    }
    const depositos = isValidNumber(node.data.depositos) ? parseFloat(node.data.depositos) : 0;
    const retiros = isValidNumber(node.data.retiros) ? parseFloat(node.data.retiros) : 0;

    filteredIngresosSum += depositos;
    filteredEgresosSum += retiros;

    filteredSaldoTotal = (filteredSaldoTotal + depositos) - retiros; 
    
  });

  await nextTick();
  totales.value.depositos = filteredIngresosSum;
  totales.value.retiros = filteredEgresosSum;
  totales.value.saldo = filteredSaldoTotal;
};
</script>

<style lang="scss">
.con{
  margin-top: 10vh;
}
.nunito{
  font-family: 'Nunito', sans-serif;
  font-weight: 200;
  color: #691C32;
}
.nunito-bold{
  font-family: 'Nunito', sans-serif;
  font-weight: 500;
  color: #691C32;
  font-style: italic;
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
  height: 60vh;
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

.partial-cell{
  background-color: #fdf28f;
  color: #a17600;
  font-weight: bold;
}

.custom-cell {
  display: flex;
  align-items: center;
}

.informe {
  width: 100%;
  min-height: 200px;
  background-color: #f1f1f1;
  border-radius: 15px;
  margin-bottom: 5vh;
}
</style>