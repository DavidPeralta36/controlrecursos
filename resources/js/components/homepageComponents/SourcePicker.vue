<template lang="">
    <div class="container fuentesContainer">
        <p>Fuentes de financiamiento disponibles</p>
        
        <div class="d-flex" >
            <div :class="fuente.id === 1 ? 'form-check mr-2' : 'form-check mx-2'" v-for="fuente in fuentes.sort((a, b) => a.id - b.id)" :key="fuente.id">
                <input class="form-check-input" type="checkbox" value="" :id="fuente.id" @click="handleSelect(fuente)">
                <label class="form-check-label" :for="fuente.id">
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
