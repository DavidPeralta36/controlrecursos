<template>

    <div class="controlsContainer">
        <div class="w-100 h-100">
            <p>Seleccione el periodo requerido:</p> 
            <VueSelect v-model="localSelectedPeriod" :options="periodos" label="ejercicio" class="mt-1"/>
        </div>
        <div class="d-flex justify-content-between w-100" >
          <div>
            <label for="mes" class="ml-1">Selecciona el año en el periodo</label>
            <VueSelect label="label" :options="years" v-model="selectedYear" class="mt-1" id="mes"/>
          </div>
          <div>
            <label for="mes" class="ml-1">Selecciona el mes del reporte</label>
            <VueSelect v-model="localSelectedMOnth" :options="months" label="label" class="mt-1" id="mes"/>
          </div>
        </div>
        <div class="w-100 h-100 d-flex justify-content-end mt-1">
          <button class="btn btn-success  align-self-center" @click="props.handleFilterReport">
            <v-icon name="fa-chart-bar" animation="ring" hover/>
            Consultar reporte
          </button>
      </div>
    </div>
</template>
<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import '@vueform/toggle/themes/default.css'
import Toggle from '@vueform/toggle'
import VueSelect from "vue-select";
import "vue-select/dist/vue-select.css"
import { ref, defineProps, defineEmits, watch, computed } from 'vue';

const flow = ref(['year', 'month', 'day']);
const localSelectedPeriod = ref('Periodo requerido *');
const localRangeSearch = ref(false);
const localSelectedMOnth = ref(null);
const years = ref([]);
const selectedYear = ref(null);

const months = ref([
    { value: 1, label: 'Enero' },
    { value: 2, label: 'Febrero' },
    { value: 3, label: 'Marzo' },
    { value: 4, label: 'Abril' },
    { value: 5, label: 'Mayo' },
    { value: 6, label: 'Junio' },
    { value: 7, label: 'Julio' },
    { value: 8, label: 'Agosto' },
    { value: 9, label: 'Septiembre' },
    { value: 10, label: 'Octubre' },
    { value: 11, label: 'Noviembre' },
    { value: 12, label: 'Diciembre' },
]);
const props = defineProps({
  dates: Object,
  rangeSearch: Boolean,
  selectedPeriod: Object,
  periodos: Array,
  handleDateChange: Function,
  handleGenReport: Function,
  toggleSetRangeSearch: Function,
  handleSelectPeriod: Function,
  registros: Array,
  handleFilterReport: Function,
})

const registrosLocales = computed(() => {
    if (props.registros.length === 0) return [];
    return props.registros;
});

watch(registrosLocales, (newValue) => {
    const uniqueYears = [...new Set(newValue.map(item => new Date(item.fechas).getFullYear()))];
    years.value = uniqueYears.map(year => ({ value: year, label: year.toString() }));
});


const emit = defineEmits(['update:selectedPeriod', 'update:rangeSearch', 'update:selectedMonth', 'update:selectedYear']);

function syncPropsWithEmit(propName, localRef, emit) {
  // Watch for local changes and emit them
  watch(localRef, (newValue) => {
    emit(`update:${propName}`, newValue);
  });

  // Watch for prop changes and update local state accordingly
  watch(() => props[propName], (newValue) => {
    localRef.value = newValue;
  });
}

// Eliminar duplicidad y sobrecarga del watch
syncPropsWithEmit('selectedPeriod', localSelectedPeriod, emit);
syncPropsWithEmit('rangeSearch', localRangeSearch, emit);
syncPropsWithEmit('selectedMonth', localSelectedMOnth, emit);
syncPropsWithEmit('selectedYear', selectedYear, emit);

</script>
<style scoped>
.controlsContainer {
    display: flex;
    justify-content: space-between;
    flex-direction: column;
    align-items: end;
    width: 100%;
    margin-top: 1vh;
  }

:root {
  --popper-theme-background-color: #333333;
  --popper-theme-background-color-hover: #333333;
  --popper-theme-text-color: #ffffff;
  --popper-theme-border-width: 0px;
  --popper-theme-border-style: solid;
  --popper-theme-border-radius: 6px;
  --popper-theme-padding: 32px;
  --popper-theme-box-shadow: 0 6px 30px -6px rgba(0, 0, 0, 0.25);
}

.btn-primary {
  background-color: #691C32;
  border-color: #691C32;
}
</style>