<template >
    <div class="h-100 w-100 d-flex flex-column">
        <div class="mt-5">
            <p class="h3 mx-3 nunito font-weight-bold">Reporte general por fuentes de financiamiento</p>
            <hr/>
            <SourcePicker :fuentes="fuentes" :handleSelect="handleSelect"/>
            <div class="d-flex align-items-center">
              <Popper placement="top-start" hover>
                <template #content>
                  <div class="tlpContent">
                    Cambia entre el tipo de busqueda para el reporte
                  </div>
                </template>
                <Toggle v-model="rangeSearch" class="mt-1"/>
              </Popper>
              <p class="mb-0 ms-2 mx-2 mt-1">Tipo de busqueda: <strong>{{ rangeSearch ? 'Fechas' : 'Periodo' }}</strong></p>
            </div>
            <div class="controlsContainer">
              <div class="d-flex" v-if="rangeSearch">
                <div>
                  <label for="dpInicio" class="ml-1">Fecha de inicio</label>
                  <VueDatePicker :model-value="dates.startDate" @update:model-value="(value) => handleDateChange(value, 'startDate')" id="dpInicio" clereable auto-apply :flow="flow" :enable-time-picker="false" :state="dates.startDateSelected"/>
                </div>
                <div class="mx-3">
                  <label for="dpFin" class="ml-1">Fecha de fin</label>
                  <VueDatePicker :model-value="dates.endDate" @update:model-value="(value) => handleDateChange(value, 'endDate')" id="dpFin" clereable auto-apply :flow="flow" :enable-time-picker="false" :state="dates.endDateSelected"/>
                </div>
              </div>
              <div class="w-100 h-100"  v-else>
                <p>Seleccione el periodo requerido:</p>
                <VueSelect v-model="selectedPeriod" :options="periodos" label="ejercicio" class="mt-1"/>
              </div>
              <div class="w-100 h-100 d-flex justify-content-end mt-1">
                <button class="btn btn-primary " @click="handleGenReport">Generar reporte</button>
              </div>
            </div>
            <hr class="mt-2"/>
            <div :class="skeleton ? 'imgContainer' : 'tableContainer'">
              <div v-if="skeleton">
                <img src="../../../public/assets/vacio.png" alt="vacio" class="img"/>
                <p class="font-weight-bold nunito font-italic h5 text-center">Nada por aquí todavía...</p>
              </div> 
              <div v-if="!skeleton" class="w-100 mt-4">
                <p class="nunito h5">Banco generado para el periodo con las fuentes de financiamiento seleccionadas:</p>
                
              </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, defineProps } from 'vue';
import '../../sass/app.scss';
import SourcePicker from './homepage_components/SourcePicker.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import '@vueform/toggle/themes/default.css'
import Toggle from '@vueform/toggle'
import VueSelect from "vue-select";
import "vue-select/dist/vue-select.css"

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
const skeleton = ref(true);
const registros = ref([]);
const flow = ref(['year', 'month', 'day']);
const selectedSource = ref(null);

const selectedPeriod = ref("2024");

const handleSelect = (source) => {
  selectedSource.value = source.id;
  console.log(source.id)
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

const handleGenReport = async () => {
  skeleton.value = false;
  if(rangeSearch.value){
    try{
      const response = await axios.get('/report', {
        params: {
          beginingDate: dates.value.startDate,
          endDate: dates.value.endDate,
          source: selectedSource.value
        }
      });
      registros.value = response.data;
      console.log(registros.value);
    }catch(e){
      console.log(e);
    }
  }
  else{
    try{
      console.log(selectedPeriod.value);
      const response = await axios.get('/report_by_period', {
        params: {
          source: selectedSource.value,
          period: selectedPeriod.value.ejercicio
        }
      });
      registros.value = response.data;
      console.log(registros.value);
    }catch(e){
      console.log(e);
    }
  }
}

</script>

<style lang="scss">
  .controlsContainer {
    display: flex;
    justify-content: space-between;
    align-items: end;
    width: 100%;
    margin-top: 1vh;
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

.tlpContent{
  background-color: rgb(13, 14, 41);
  color: rgb(189, 189, 189);
  font-weight: 500;
  font-size: 0.9vw;
  font-family: 'Nunito', sans-serif;
  min-width: 10vw;
  max-width: 13vw;
  height: 8vh;
  padding: 1rem;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  
}

.tlpContent:hover{
  cursor: pointer;
}
</style>