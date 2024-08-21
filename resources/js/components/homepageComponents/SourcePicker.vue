<template lang="">
    <div class="container fuentesContainer">
        <p class="nunito h4">Fuentes de financiamiento disponibles</p>
        <small class="nunito h5">Selecciona las fuentes de financiamiento que deseas incluir en el reporte</small>
        <hr/>
        <div class="d-flex" >
            <div :class="fuente.id === 1 ? 'form-check mr-2' : 'form-check mx-2'" v-for="fuente in fuentes.sort((a, b) => a.id - b.id)" :key="fuente.id">
                <input class="form-check-input" type="checkbox" value="" :id="fuente.id" @click="handleSelect(fuente)">
                <label class="form-check-label nunito-bold" :for="fuente.id">
                  {{fuente.nombre_fuente}}
                </label>
              </div>
        </div>
    </div>
</template>

<script setup>
    import { defineProps } from 'vue';
    import '../../../sass/app.scss';
    const props = defineProps({
      fuentes: Array,
      handleSelect: Function,
    });
</script>

<style lang="scss">
.nunito{
  font-family: 'Nunito', sans-serif;
  font-weight: 200;
}
.nunito-bold{
  font-family: 'Nunito', sans-serif;
  font-weight: 500;
}
</style>