<template>
    <div class="d-flex align-items-center">
        <Popper placement="top-start" hover openDelay="500">
            <template #content>
            <div class="tlpContent">
                Cambia entre el tipo de busqueda para el reporte
            </div>
            </template>
            <Toggle v-model="props.rangeSearch" class="mt-1" @click="props.toggleSetRangeSearch"/>
        </Popper>
        <p class="mb-0 ms-2 mx-2 mt-1">Tipo de busqueda: <strong>{{ props.rangeSearch ? 'Fechas' : 'Periodo' }}</strong></p>
    </div>
    <div class="controlsContainer">
        <div class="d-flex" v-if="rangeSearch">
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
            <button class="btn btn-primary " @click="props.handleGenReport">Generar reporte</button>
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
import { ref, defineProps, defineEmits, watch } from 'vue';

const flow = ref(['year', 'month', 'day']);
const localSelectedPeriod = ref( '2024');

const props = defineProps({
  dates: Object,
  rangeSearch: Boolean,
  selectedPeriod: String,
  periodos: Array,
  handleDateChange: Function,
  handleGenReport: Function,
  toggleSetRangeSearch: Function,
  handleSelectPeriod: Function
})

const emit = defineEmits(['update:selectedPeriod']);

// Watch for changes in the local selected period and emit them
watch(localSelectedPeriod, (newValue) => {
  emit('update:selectedPeriod', newValue);
});

// Watch for prop changes and update local state accordingly
watch(() => props.selectedPeriod, (newValue) => {
  localSelectedPeriod.value = newValue;
});

</script>
<style >
.controlsContainer {
    display: flex;
    justify-content: space-between;
    align-items: end;
    width: 100%;
    margin-top: 1vh;
  }
</style>