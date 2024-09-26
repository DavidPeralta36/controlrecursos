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
              :registros="registros"
              :handleDateChange="handleDateChange" 
              :handleGenReport="handleGenReport"
              :handleSelectPeriod="handleSelectPeriod"
              :handleFilterReport="handleFilterReport"
              :disabled="disabled"
              @update:rangeSearch="toggleSetRangeSearch"
              @update:selectedPeriod="handleSelectPeriod"
              @update:selectedMonth="handleSelectMonth"
              @update:selectedYear="handleSelectYear"/>
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
                  :rowData="report"
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
            <h4 class="nunito-bold">Servicios Estatales de salud</h4>
            <h5 class="nunito">Informes generados</h5>
            <div v-if="loads.loaded" class="informe">
              <div class="container p-2">
                <h4 class="nunito-bold">Reporte basico</h4>
                <h5 class="nunito">Informe del ejercicio de los Recursos Federales para la prestacion gratuita de servicios de salud</h5>
                <hr/>
                <p>Total de registros: <strong>{{ registros.length }}</strong></p>
                <p>Total de retiros: <strong>{{ formatearCantidad(totales.retiros) }}</strong></p>
                <p>Total de depósitos: <strong>{{ formatearCantidad(totales.depositos) }}</strong></p>
                <hr/>
                <p>Total de saldo: <strong>{{ formatearCantidad(totales.saldo) }}</strong></p>
              </div>
            </div>

            <div class="informe">
              <div class="container p-2">
                <h4 class="nunito-bold">Informe mensual del ejercicio</h4>
                <h5 class="nunito">Informe del ejercicio de los Recursos Federales para la prestacion gratuita de servicios de salud</h5>
                <hr/>
                <div class="container text-center w-100">
                  <div class="row bg-dark text-light rounded-2">
                    <div class="col col-md-4">
                      <p>Capitulo</p>
                    </div>
                    <div class="col">
                      <p>Programado</p>
                    </div>
                    <div class="col">
                      <p>Ejercido mes</p>
                    </div>
                    <div class="col">
                      <p>Ejercido acumulado</p>
                    </div>
                    <div class="col">
                      <p>Reintegros</p>
                    </div>
                    <div class="col">
                      <p>Por ejercer</p>
                    </div>
                  </div>
                  <div v-for="rubro in generalReport" :key="rubro.idcapitulo" class="row flex justify-content-start align-items-center" style="height: 100px;">
                    <div class="d-flex">
                      <div class="col col-md-4 d-flex justify-content-start text-start">{{ rubro.nombre_capitulo }}</div>
                      <div class="col">{{ formatCurrency(rubro.monto_programado) }}</div>
                      <div class="col">{{ formatCurrency(rubro.ejercido_mes) }}</div>
                      <div class="col">{{ formatCurrency(rubro.ejercido_acumulado) }}</div>
                      <div class="col">{{ formatCurrency(rubro.reintegros) }}</div>
                      <div class="col">{{ formatCurrency(rubro.por_ejercer) }}</div>
                    </div>
                    <hr>
                  </div>
                  <hr>
                  <div class="d-flex" v-if="generalReport">
                    <div class="col col-md-4 d-flex justify-content-start text-start font-weight-bold">Total</div>
                    <div class="col programado">{{ formatCurrency(generalReport.reduce((total, item) => total + item.monto_programado, 0)) }}</div>
                    <div class="col mes">{{ formatCurrency(generalReport.reduce((total, item) => total + item.ejercido_mes, 0)) }}</div>
                    <div class="col acumulado">{{ formatCurrency(generalReport.reduce((total, item) => total + item.ejercido_acumulado, 0)) }}</div>
                    <div class="col reintegros">{{ formatCurrency(generalReport.reduce((total, item) => total + item.reintegros, 0)) }}</div>
                    <div class="col " :class="getClass(generalReport.reduce((total, item) => total + item.por_ejercer, 0))">{{ formatCurrency(generalReport.reduce((total, item) => total + item.por_ejercer, 0)) }}</div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <Notifications position="bottom left" />
    </div>
</template>

<script setup>
import { ref, defineProps, nextTick, watch, onMounted } from 'vue';
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
//#region Variables
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
const selectedYear = ref(null);
const report = ref(null);
const generalReport = ref(null);
const programadoRubros = ref(null);
const capitulos = ref(null);
const rubros = ref(null);
const disabled = ref(false);
//#endregion

onMounted(async () => {
  getCapitulos();
  getRubros();
})

const getCapitulos = async () => {
  try{
    const response = await axios.get('/get_capitulos');
    if(response.status === 200){
      capitulos.value = response.data;
    }else{
      notify({
        title: 'Error al obtener capitulos',
        text: 'Error: ' + e.response.data.message,
        type: 'error',
        duration: 5000,
      })
    }
  }catch(e){
    notify({
      title: 'Error al obtener capitulos',
      text: 'Error: ' + e.response.data.message,
      type: 'error',
      duration: 5000,
    })
  }
}

const getRubros = async () => {
  try{
    const response = await axios.get('/get_rubros');
    if(response.status === 200){
      rubros.value = response.data;
    }else{
      notify({
        title: 'Error al obtener rubros',
        text: 'Error: ' + e.response.data.message,
        type: 'error',
        duration: 5000,
      })
    }
  }catch(e){
    notify({
      title: 'Error al obtener rubros',
      text: 'Error: ' + e.response.data.message,
      type: 'error',
      duration: 5000,
    })
  }
}

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

const handleSelectYear = (year) => {
  selectedYear.value = year.label;
}

const handleSelectMonth = (month) => {
  selectedMonth.value = month.label;
}

const handleSelect = (source) => {
  selectedSource.value = source.id;

  if(selectedSource.value && selectedPeriod.value !== "Periodo requerido *"){
    handleGenReport();
  }
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

const getReportByPeriod = async () => {
  const response = await axios.get('/report_by_period', {
    params: {
      source: selectedSource.value,
      period: selectedPeriod.value.ejercicio,
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
  
}

const handleGenReport = async () => {
  if (selectedSource.value && selectedPeriod.value.ejercicio !== 'Periodo requerido *') {
      try {
        //await animateSkeletonOut(skeletonDiv, loadingDiv, loads);
        //startAnimations(loadingText, spinner);
        await getReportByPeriod();
      } catch (e) {
        console.log(e);
      }
    }
};

const toggleSetRangeSearch = () => {
  rangeSearch.value =  !rangeSearch.value;
}

const handleSelectPeriod = (period) => {
  selectedPeriod.value = period;

  if(selectedSource.value && selectedPeriod.value !== "Periodo requerido *"){
    handleGenReport();
  }
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

function getYearFromDate(dateString) {
  if (dateString.includes('-')) {
    // Formato yyyy-MM-dd
    return parseInt(dateString.split('-')[0]);
  } else if (dateString.includes('/')) {
    // Formato dd/MM/yyyy
    return parseInt(dateString.split('/')[2]);
  }
  // Si no coincide con ninguno de los formatos, devolver null o algún valor predeterminado
  return null;
}

const handleFilterReport = async () => {
  if(selectedSource.value && selectedPeriod.value !== "Periodo requerido *" && selectedMonth.value && selectedYear.value){
    disabled.value = true;
    getProgramacionRubros();

    await animateSkeletonOut(skeletonDiv, loadingDiv, loads);
    startAnimations(loadingText, spinner);

    const monthOrder = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
  
    const startMonth = 'ENERO';

    const startIndex = monthOrder.indexOf(startMonth);
    const endIndex = monthOrder.indexOf(selectedMonth.value.toUpperCase());

    const filteredRegistrosMes = registros.value.filter(item => {
      const itemYear = item.fechas.includes(selectedYear.value);
      const itemMonthIndex = monthOrder.indexOf(item.mes);

      const valido = itemYear && itemMonthIndex >= startIndex && itemMonthIndex === endIndex

      if(valido){
        return item;
      }else{
        return ;
      }
    });

    const filteredRegistros = registros.value.filter(item => {
      const itemYear = item.fechas.includes(selectedYear.value) || item.fechas.includes(selectedPeriod.value.ejercicio);
      const itemMonthIndex = monthOrder.indexOf(item.mes);

      const valido = itemYear && itemMonthIndex >= startIndex && itemMonthIndex <= endIndex

      if(valido || parseInt(selectedYear.value) > getYearFromDate(item.fechas)){
        return item;
      }else{
        return ;
      }
    });

    report.value = registros.value.filter(item => item.mes === selectedMonth.value.toUpperCase() && item.fechas.includes(selectedYear.value));

    await animateLoadingOut(loadingDiv, tableDiv, loads);

    await nextTick();

 

    const groupedRegistros = filteredRegistrosMes.reduce((acc, item) => {
      const cap = item.r
      if (acc[cap]) {
        acc[cap].push(item);
      } else {
        acc[cap] = [item];
      }
      return acc;
    }, {});

    const groupedFilteredRegistros = filteredRegistros.reduce((acc, item) => {
      const cap = item.r
      if (acc[cap]) {
        acc[cap].push(item);
      } else {
        acc[cap] = [item];
      }
      return acc;
    }, {});

    generalReport.value = capitulos.value.map(capitulo => {

      const rubro = rubros.value.find(rubro => rubro.id === capitulo.id);
      const numberRubro = rubro.descripcion.split("R")[1];
      const registrosRubros = groupedRegistros[numberRubro];
      const registrosRubroAcumulado = groupedFilteredRegistros[numberRubro];

      if (registrosRubros && registrosRubroAcumulado) {
        const rubroProgramado = programadoRubros.value.find(rubro => rubro.idrubro === capitulo.id);
        const monto_programado = parseFloat(rubroProgramado.monto_programado);
        const ejercido_mes = registrosRubros.reduce((total, item) => {
          const retiro = parseFloat(item.retiros);
          return !isNaN(retiro) ? total + retiro : total;
        }, 0);

        const ejercido_acumulado = registrosRubroAcumulado.reduce((totalAcumulado, itemAcumlado) => {
          const retiroAcumulado = parseFloat(itemAcumlado.retiros);
          return !isNaN(retiroAcumulado) ? totalAcumulado + retiroAcumulado : totalAcumulado;
        }, 0);

        const reintegros = 0;

        const obj = {
          idcapitulo: capitulo.id,
          nombre_capitulo: capitulo.descripcion,
          monto_programado: monto_programado,
          ejercido_mes,
          ejercido_acumulado: ejercido_acumulado,
          reintegros,
          por_ejercer: parseFloat(monto_programado) - ejercido_acumulado // Use colon instead of assignment
        }

        //console.log(obj);
        return obj;

      }else{
        const rubroProgramado = programadoRubros.value.find(rubro => rubro.idrubro === capitulo.id);
        const monto_programado = parseFloat(rubroProgramado.monto_programado);
        return {
          idcapitulo: capitulo.id,
          nombre_capitulo: capitulo.descripcion,
          monto_programado: monto_programado,
          ejercido_mes: 0,
          ejercido_acumulado: 0,
          reintegros: 0,
          por_ejercer: monto_programado,
        }
      }
    });

    disabled.value = false;
  }else{
    notify({
      title: 'Error al buscar el periodo',
      text: 'Para generar el reporte debe completar todos los campos',
      type: 'error',
      duration: 5000,
      speed: 1000,
    });
  }
}

const getProgramacionRubros = async () => {
  try{
    const response = await axios.get('/get_programacion_rubros', {
      params: {
        ejercicio: selectedPeriod.value.ejercicio,
        source: selectedSource.value
      }
    });
    programadoRubros.value = response.data;
  }catch(e){
    notify({
      title: 'Error al obtener programación por rubro',
      text: 'Error: ' + e.response.data.message,
      type: 'error',
      duration: 5000,
    })
  }
}

function formatCurrency(param) {

    if (isNaN(param)) return ''; 

    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(param); 
}

const getClass = (param) => {
  if (param > 0) {
    return 'positivo';
  } else if (param < 0) {
    return 'negativo';
  }
}
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

.positivo{
  color: #2e7d32;
  font-weight: bold;
}
.negativo{
  color: #e3342f;
  font-weight: bold;
}
.programado{
  color: #1f49be;
  font-weight: bold;
}
.mes{
  color: #590d85;
  font-weight: bold;
}
.acumulado{
  color: #ffee00;
  font-weight: bold;
}
.reintegros{
  color: #016e99;
  font-weight: bold;
}

</style>