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
              @update:selectedPeriod="handleSelectPeriod"/>
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
                  :frameworkComponents="{ customCellRenderer }"
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
import { ref, defineProps, nextTick } from 'vue';
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
const colDefs = ref([
  { field: 'fechas', headerName: 'Fecha', filter: true, sortable: true },
  { field: 'mes', headerName: 'Mes', filter: true, sortable: true },
  { field: 'forma_pago', headerName: 'Forma de pago', filter: true, sortable: true },
  { field: 'rfc', headerName: 'RFC', filter: true, sortable: true },
  { field: 'proveedor', headerName: 'Proveedor', filter: true, sortable: true  },
  { field: 'factura', headerName: 'Factura', filter: true, sortable: true  },
  { 
    field: 'parcial', 
    headerName: 'Parcial', 
    valueFormatter: formatCurrency , 
    cellClass: (params) => params.value ? 'partial-cell' : '',
    cellRenderer: 'customCellRenderer' 
  },
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
const agProps = ref({
  pagination: true,
  paginationPageSize: 500,
  paginationPageSizeSelector: [200, 500, 1000],
});
const registros = ref([]);
const selectedSource = ref(null);
const selectedPeriod = ref({ejercicio: "Periodo requerido * "});
const loadingText = ref(null); 
const spinner = ref(null); 
const skeletonDiv = ref(null);
const loadingDiv = ref(null);
const tableDiv = ref(null);
const filteredIngresos = ref(0);
const filteredEgresos = ref(0);
const filteredBalance = ref(0);

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

  const registrosProcesados = response.data.map((item, index, array) => {
    if (index === 0) {
      // El primer registro no cambia, es el estado inicial
      return { ...item };
    } else {
      // Calcular el saldo para los registros subsecuentes
      const saldoAnterior = parseFloat(array[index - 1].saldo);
      const ingresosActuales = parseFloat(item.depositos) || 0;
      const egresosActuales = parseFloat(item.retiros) || 0;

      console.log(saldoAnterior, ingresosActuales, egresosActuales);

      const nuevoSaldo = saldoAnterior + ingresosActuales - egresosActuales;

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
    console.log(selectedPeriod.value, selectedPeriod.value.ejercicio);
    if (selectedPeriod.value && selectedPeriod.value.ejercicio !== 'Periodo requerido *') {
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
    if (!params.value) return '';
    
    const number = parseFloat(params.value.replace(/[^0-9.-]+/g, '')); 
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

    //console.log(`Saldo actual: ${saldoTotal}, Depósitos: ${depositos}, Retiros: ${retiros}`);


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