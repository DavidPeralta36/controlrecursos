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
                  <VueDatePicker id="dpInicio" v-model="beginingDate" clereable/>
                </div>
                <div class="mx-3">
                  <label for="dpFin" class="ml-1">Fecha de fin</label>
                  <VueDatePicker id="dpFin" v-model="endDate" clereable/>
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
              <div class="w-100 mt-4">
                <p class="nunito h5">Banco generado para el periodo {{ beginingDate.toLocaleDateString() }} al {{ endDate.toLocaleDateString() }} con las fuentes de financiamiento seleccionadas:</p>
                <div class="tableWrapper">
                  <table class="table w-100">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">First</th>
                        <th scope="col">Last</th>
                        <th scope="col">Handle</th>
                      </tr>
                    </thead>
                    <tbody class="tableBody nunito">
                      <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                      </tr>
                      <tr>
                        <th scope="row">2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                      </tr>
                      <tr>
                        <th scope="row">3</th>
                        <td colspan="2">Larry the Bird</td>
                        <td>@twitter</td>
                      </tr>
                      <!-- Más filas aquí -->
                      <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                      </tr>
                      <tr>
                        <th scope="row">2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                      </tr>
                      <tr>
                        <th scope="row">3</th>
                        <td colspan="2">Larry the Bird</td>
                        <td>@twitter</td>
                      </tr>
                      <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                      </tr>
                      <tr>
                        <th scope="row">2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                      </tr>
                      <tr>
                        <th scope="row">3</th>
                        <td colspan="2">Larry the Bird</td>
                        <td>@twitter</td>
                      </tr>
                      <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                      </tr>
                      <tr>
                        <th scope="row">2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                      </tr>
                      <tr>
                        <th scope="row">3</th>
                        <td colspan="2">Larry the Bird</td>
                        <td>@twitter</td>
                      </tr>
                    </tbody>
                  </table>
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
import { VueDatePicker } from '@mathieustan/vue-datepicker';
import '@mathieustan/vue-datepicker/dist/vue-datepicker.min.css';

const props = defineProps({
  user: Object,
  fuentes: Array
});
const beginingDate = ref(new Date());
const endDate = ref(new Date());
const selectedSources = ref([]);
const skeleton = ref(false);

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

const handleGenReport = async () => {
  console.log("Generando reporte");

  try{
    const response = await axios.get('/report', {
      params: {
        beginingDate: "beginingDate.value.toISOString()",
        endDate: "endDate.value.toISOString()",
        sources: selectedSources.value.map(s => s.id)
      }
    });
    console.log(response);
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