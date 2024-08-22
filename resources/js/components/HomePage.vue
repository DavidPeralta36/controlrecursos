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
              <div v-if="!skeleton" class="w-100 mt-4">
                <p class="nunito h5">Banco generado para el periodo con las fuentes de financiamiento seleccionadas:</p>
                <div class="tableWrapper">
                  <table class="table w-100">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Forma de pago</th>
                        <th scope="col">RFC</th>
                        <th scope="col">Proveedor</th>
                        <th scope="col">Factura</th>
                        <th scope="col">Parcialidad</th>
                        <th scope="col">Depositos</th>
                        <th scope="col">Retiros</th>
                        <th scope="col">Saldo</th>
                        <th scope="col">R</th>
                        <th scope="col">Partida</th>
                        <th scope="col">Fecha factura</th>
                        <th scope="col">Folio fiscal</th>
                        <th scope="col">Tipo de adjudicación</th>
                        <th scope="col">Num adjudicación o contrato</th>
                        <th scope="col">Num suficiencia presupuestal</th>
                        <th scope="col">Orden de servidor o compra</th>
                        <th scope="col">CLC</th>
                        <th scope="col">Poliza</th>
                        <th scope="col">Numero cuenta proveedor</th>
                        <th scope="col">Referencia bancaria</th>
                        <th scope="col">CLUE</th>
                        <th scope="col">Aplica en</th>
                        <th scope="col">Nombre partida</th>
                        <th scope="col">Mes de servicio</th>
                        <th scope="col">Metodo de pago</th>
                      </tr>
                    </thead>
                    <tbody class="tableBody nunito">
                      <tr v-for="r in registros" :key="r.id">
                        <th scope="row">{{ r.id }}</th>
                        <td>{{r.fechas}}</td>
                        <td>{{r.mes}}</td>
                        <td>{{r.mes}}</td>
                        <td>{{ r.proveedor }}</td>
                        <td>{{ r.factura }}</td>
                        <td>{{ r.parcial }}</td>
                        <td>{{ r.depositos }}</td>
                        <td>{{ r.retiros }}</td>
                        <td>{{ r.saldo }}</td>
                        <td>{{ r.r }}</td>
                        <td>{{ r.partida }}</td>
                        <td>{{ r.fecha_factura }}</td>
                        <td>{{ r.folio_fiscal }}</td>
                        <td>{{ r.tipo_adjudicacion }}</td>
                        <td>{{ r.num_adj_contrato }}</td>
                        <td>{{ r.num_suficiencia_presupuestal }}</td>
                        <td>{{ r.orden_servocio_compra }}</td>
                        <td>{{ r.clc }}</td>
                        <td>{{ r.poliza }}</td>
                        <td>{{ r.numero_cuenta_proveedor }}</td>
                        <td>{{ r.referencia_bancaria }}</td>
                        <td>{{ r.clue }}</td>
                        <td>{{ r.nombre_clue }}</td>
                        <td>{{ r.nombrepartida }}</td>
                        <td>{{ r.mes_servicio }}</td>
                        <td>{{ r.metodo_pago }}</td>
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