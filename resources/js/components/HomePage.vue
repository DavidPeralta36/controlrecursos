<template >
    <div class="h-100 w-100 ">
        <div class="mt-5">
            <p class="h4 mx-3 nunito">Reporte general por fuentes de financiamiento</p>
            <hr/>
            <SourcePicker :fuentes="fuentes" :handleSelect="handleSelect"/>
            <div class="controlsContainer">
              <div class="d-flex ">
                <div>
                  <label for="dpInicio">Fecha de inicio</label>
                  <VueDatePicker id="dpInicio" v-model="beginingDate" clereable/>
                </div>
                <div class="mx-3">
                  <label for="dpFin">Fecha de fin</label>
                  <VueDatePicker id="dpFin" v-model="endDate"  @input="handleEndDate" clereable/>
                </div>
              </div>
              <div class="w-100 h-50 d-flex justify-content-end mt-1">
                <button class="btn btn-primary ">Generar reporte</button>
              </div>
            </div>
            <hr class="mt-2"/>
        </div>
    </div>
</template>
<script setup>
import { ref, defineProps } from 'vue';
import '../../sass/app.scss';
import SourcePicker from './homepageComponents/sourcePicker.vue';
import { VueDatePicker } from '@mathieustan/vue-datepicker';
import '@mathieustan/vue-datepicker/dist/vue-datepicker.min.css';

const props = defineProps({
  user: Object,
  fuentes: Array
});
const beginingDate = ref(new Date());
const endDate = ref(new Date());

const selectedSources = ref([]);

const handleSelect = (source) => {
  if (selectedSources.value.includes(source)) {
    selectedSources.value = selectedSources.value.filter(s => s !== source);
  } else {
    selectedSources.value.push(source);
  }
}

const handleEndDate = () => {
  if(endDate.value < beginingDate.value){
    alert("La fecha de fin no puede ser menor a la de inicio");
  }
}

</script>

<style lang="scss">
  .controlsContainer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    width: 100%;
    margin-top: 1vh;
  }
</style>