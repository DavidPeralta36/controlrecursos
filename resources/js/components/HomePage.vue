<template >
    <div class="h-100 w-100 ">
        <div class="mt-5">
            <p class="h4 mx-3 nunito">Reporte general por fuentes de financiamiento</p>
            <hr/>
            <SourcePicker :fuentes="fuentes" :handleSelect="handleSelect"/>
            <div class="w-100 d-flex justify-content-end mt-1">
                <button class="btn btn-primary ">Generar reporte</button>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, defineProps } from 'vue';
import '../../sass/app.scss';
import SourcePicker from './homepageComponents/sourcePicker.vue';
const props = defineProps({
  user: Object,
  fuentes: Array
});

const selectedSources = ref([]);

const handleSelect = (source) => {
  if (selectedSources.value.includes(source)) {
    selectedSources.value = selectedSources.value.filter(s => s !== source);
  } else {
    selectedSources.value.push(source);
  }
}



</script>