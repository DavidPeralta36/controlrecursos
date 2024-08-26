<template>
    <div class="container fuentesContainer">
        <p class="nunito h4 text-light">Fuentes de financiamiento disponibles</p>
        <small class="nunito h5 text-light">Selecciona las fuentes de financiamiento que deseas incluir en el reporte</small>
        <hr class="border-light"/>
        <div class="d-flex" >
            <div :class="fuente.id === 1 ? 'form-check mr-2 ' : 'form-check mx-2'" v-for="fuente in fuentes.sort((a, b) => a.id - b.id)" :key="fuente.id">
              <input class="form-check-input" type="radio" name="fuentes" :id="fuente.id" @click="handleSelect(fuente)">
              <label class="form-check-label nunito-bold text-light" :for="fuente.id">
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

<style lang="scss" scoped>
.nunito{
  font-family: 'Nunito', sans-serif;
  font-weight: 200;
}
.nunito-bold{
  font-family: 'Nunito', sans-serif;
  font-weight: 500;
}

.fuentesContainer{
  background-color: #9F2241;
  border-radius: 10px;
  padding-top: 2vh;
  padding-bottom: 2vh;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}
</style>