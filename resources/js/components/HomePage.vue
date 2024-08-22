<template >
    <div class="h-100 w-100 d-flex flex-column">
        <div class="mt-5">
            <p class="h3 mx-3 nunito">Reporte general por fuentes de financiamiento</p>
            <hr/>
            <SourcePicker :fuentes="fuentes" :handleSelect="handleSelect"/>
            <div class="controlsContainer">
              <div class="d-flex ">
                <div>
                  <label for="dpInicio" class="ml-1">Fecha de inicio</label>
                  <VueDatePicker v-model="beginingDate" id="dpInicio" clereable/>
                </div>
                <div class="mx-3">
                  <label for="dpFin" class="ml-1">Fecha de fin</label>
                  <VueDatePicker v-model="endDate" id="dpFin" clereable/>
                </div>
              </div>
              <div class="w-100 h-50 d-flex justify-content-end mt-1">
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
                <div class="tableWrapper">
                  
                </div>
              </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, defineProps } from 'vue';
import '../../sass/app.scss';
import SourcePicker from './homepageComponents/sourcePicker.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const props = defineProps({
  user: Object,
  fuentes: Array
});
const beginingDate = ref(new Date());
const endDate = ref(new Date());
const selectedSources = ref([]);
const skeleton = ref(true);
const registros = ref([]);

const handleSelect = (source) => {
  if (selectedSources.value.includes(source)) {
    selectedSources.value = selectedSources.value.filter(s => s !== source);
  } else {
    selectedSources.value.push(source);
  }
}

const handleGenReport = async () => {
  skeleton.value = false;
  try{
    const response = await axios.get('/report', {
      params: {
        beginingDate: beginingDate.value,
        endDate: endDate.value,
        sources: selectedSources.value.map(s => s.id)
      }
    });
    registros.value = response.data;
    console.log(registros.value);
  }catch(e){
    console.log(e);
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

.tableWrapper {
    flex: 1;
    overflow: auto;
    height: 45vh;
}

.table {
    width: 100%;
    height: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.table thead {
    position: sticky;
    top: 0;
    background-color: white; /* Ajusta esto según tu diseño */
    z-index: 1;
}

.tableBody {
    flex: 1;
    overflow: auto;
    width: 100%;
    height: 100%;
}

</style>