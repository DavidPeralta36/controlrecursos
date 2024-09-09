<template>
    <div class="d-flex align-items-center">
        <Popper placement="top-start" hover openDelay="500" content="Cambia entre el tipo de busqueda para el reporte" arrow>
            <Toggle v-model="localRangeSearch" class="mt-2" />
        </Popper>
        <p class="mb-0 ms-2 mx-2 mt-1">Tipo de busqueda: <strong>{{ props.rangeSearch ? 'Fechas' : 'Periodo' }}</strong></p>
    </div>
    <div class="controlsContainer">
        <div class="d-flex" v-if="localRangeSearch">
            <div>
                <label for="dpInicio" class="ml-1">Fecha de inicio</label>
                <VueDatePicker :model-value="props.dates.startDate" @update:model-value="(value) => props.handleDateChange(value, 'startDate')" id="dpInicio" clereable auto-apply :flow="flow" :enable-time-picker="false" :state="props.dates.startDateSelected"/>
                </div>
                <div class="mx-3">
                <label for="dpFin" class="ml-1">Fecha de fin</label>
                <VueDatePicker :model-value="props.dates.endDate" @update:model-value="(value) => props.handleDateChange(value, 'endDate')" id="dpFin" clereable auto-apply :flow="flow" :enable-time-picker="false" :state="props.dates.endDateSelected"/>
            </div>
        </div>
        <div class="w-100 h-100"  v-else>
            <p>Seleccione el periodo requerido:</p> 
            <VueSelect v-model="localSelectedPeriod" :options="periodos" label="ejercicio" class="mt-1"/>
        </div>
        <div class="w-100 h-100 d-flex justify-content-end mt-1">
            <button class="btn btn-primary align-self-center" @click="props.handleGenReport">
              <v-icon name="fa-chart-bar" animation="ring" hover/>
              Consulta informacion del periodo
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
import { ref, defineProps, defineEmits, watch, toRef } from 'vue';

const flow = ref(['year', 'month', 'day']);
const localSelectedPeriod = ref('Periodo requerido *');
const localRangeSearch = ref(false);

const props = defineProps({
  dates: Object,
  rangeSearch: Boolean,
  selectedPeriod: Object,
  periodos: Array,
  handleDateChange: Function,
  handleGenReport: Function,
  toggleSetRangeSearch: Function,
  handleSelectPeriod: Function
})

const emit = defineEmits(['update:selectedPeriod', 'update:rangeSearch']);

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

</script>
<style scoped>
.controlsContainer {
    display: flex;
    justify-content: space-between;
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