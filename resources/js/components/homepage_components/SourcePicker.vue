<template>
  <div class="container fuentesContainer" ref="fuentesContainerRef">
    <!-- Condición para mostrar el contenido solo cuando la animación ha terminado -->
    <div v-if="finished" ref="contentRef">
      <p class="nunito h4 text-light">Fuentes de financiamiento disponibles</p>
      <small class="nunito h5 text-light">Selecciona las fuentes de financiamiento que deseas incluir en el reporte</small>
      <hr class="border-light" />
      <div class="d-flex">
        <!-- Asignar ref correctamente para cada checkbox -->
        <div :class="fuente.id === 1 ? 'form-check mr-2 ' : 'form-check mx-2'" 
             v-if="contentAnimated" 
             v-for="(fuente, index) in fuentes.sort((a, b) => a.id - b.id)" 
             :key="fuente.id" 
             :ref="el => fuentesRef[index] = el">
          <input class="form-check-input" type="radio" name="fuentes" :id="fuente.id" @click="handleSelect(fuente)">
          <label class="form-check-label nunito-bold text-light" :for="fuente.id">
            {{ fuente.nombre_fuente }}
          </label>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, ref, onMounted, nextTick } from 'vue';
import '../../../sass/app.scss';
import anime from 'animejs';

// Definir las propiedades que recibe el componente
const props = defineProps({
  fuentes: Array,
  handleSelect: Function,
});

// Referencia al contenedor
const fuentesContainerRef = ref(null);
const contentRef = ref(null);
const fuentesRef = ref([]); // Inicializar como un array
// Estado para determinar si la animación ha terminado
const finished = ref(false);
const contentAnimated = ref(false);

onMounted(() => {
  // Crear la animación con una línea de tiempo
  anime
    .timeline()
    .add({
      targets: fuentesContainerRef.value,
      width: [0, "5px"], 
      height: [0, "5px"],
      easing: "easeOutExpo",
      duration: 500,
      backgroundColor: "#d3d3d3",
      delay: 100,
    })
    .add({
      targets: fuentesContainerRef.value,
      height: "16vh", 
      easing: "easeOutExpo",
      duration: 500,
      delay: 100,
    })
    .add({
      targets: fuentesContainerRef.value,
      width: "100%", 
      easing: "easeOutExpo",
      duration: 750,
      backgroundColor: "#9F2241",
      borderRadius: "10px",
      paddingTop: "2vh",
      paddingBottom: "2vh",
      boxShadow: "0 0 10px rgba(0, 0, 0, 0.2)",
      complete: async () => {
        finished.value = true;
        await nextTick();
        animateContent();
      },
    });
});

const animateContent = () => {
  // Animar la aparición de contentRef
  anime({
    targets: contentRef.value,
    opacity: [0, 1],
    translateY: [-100, 0],
    duration: 500,
    easing: "easeOutExpo",
    complete: async () => {
      contentAnimated.value = true;
      await nextTick();
      animateFuentes();
    }
  });
}

const animateFuentes = () => {
  fuentesRef.value.forEach((fuenteCheck, index) => {
    if (fuenteCheck) {
      anime({
        targets: fuenteCheck,
        opacity: [0, 1],
        translateY: [50, 0],
        easing: 'easeInOutQuad',
        duration: 200,
        delay: index * 100, // Animación secuencial con retraso
      });
    }
  });
}

</script>


<style lang="scss" scoped>
.nunito {
  font-family: 'Nunito', sans-serif;
  font-weight: 200;
}
.nunito-bold {
  font-family: 'Nunito', sans-serif;
  font-weight: 500;
}

.fuentesContainer {
  overflow: hidden; // Asegura que el contenido no se desborde durante la animación
}
</style>
