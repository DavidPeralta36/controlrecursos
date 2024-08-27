// Importa anime.js
import anime from 'animejs';
import { nextTick } from 'vue';

let dotsAnimation = null;
let spinnerAnimation = null;

// Función para animar la salida del skeleton
export const animateSkeletonOut = (skeletonDiv, loadingDiv, loads) => {
  return new Promise((resolve) => {
    anime({
      targets: skeletonDiv.value,
      opacity: [1, 0],
      translateY: [0, -50],
      duration: 500,
      easing: 'easeInOutQuad',
      complete: async () => {
        loads.value.skeleton = false;
        loads.value.loading = true;
        resolve();
        await nextTick();
        animateLoadingIn(loadingDiv);
      },
    });
  });
};

// Función para animar la entrada del loading
export const animateLoadingIn = (loadingDiv) => {
  return new Promise((resolve) => {
    anime({
      targets: loadingDiv.value,
      opacity: [0, 1],
      translateX: [100, 0],
      duration: 500,
      easing: 'easeInOutQuad',
      complete: () => {
        resolve();
      },
    });
  });
};

// Función para iniciar las animaciones de carga
export const startAnimations = (loadingText, spinner) => {
  dotsAnimation = anime({
    targets: loadingText.value,
    update: (anim) => {
      const progress = Math.floor(anim.progress / 25);
      const textStages = ['Generando reporte', 'Generando reporte.', 'Generando reporte..', 'Generando reporte...'];
      loadingText.value.innerHTML = textStages[progress];
    },
    color: ['#28a745', '#ffc107', '#007bff', '#007bff', '#dc3545'],
    easing: 'linear',
    duration: 2000,
    loop: true,
  });

  spinnerAnimation = anime({
    targets: spinner.value,
    color: ['#007bff', '#28a745', '#dc3545', '#ffc107', '#007bff'],
    easing: 'linear',
    duration: 2000,
    loop: true,
  });
};

// Función para detener las animaciones de carga
export const stopAnimations = () => {
  if (dotsAnimation) dotsAnimation.pause(); // Detiene la animación de puntos
  if (spinnerAnimation) spinnerAnimation.pause(); // Detiene la animación del spinner
};

// Función para animar la salida del loading
export const animateLoadingOut = (loadingDiv, tableDiv, loads) => {
  return new Promise((resolve) => {
    anime({
      targets: loadingDiv.value,
      opacity: [1, 0],
      translateX: [0, -100],
      duration: 500,
      easing: 'easeInOutQuad',
      complete: async () => {
        stopAnimations();
        loads.value.loading = false;
        loads.value.loaded = true;
        await nextTick();
        animateTableIn(tableDiv);
        resolve();
      },
    });
  });
};

// Función para animar la entrada de la tabla
export const animateTableIn = (tableDiv) => {
  return new Promise((resolve) => {
    anime({
      targets: tableDiv.value,
      opacity: [0, 1],
      translateY: [100, 0],
      duration: 500,
      easing: 'easeOutBounce',
      complete: () => {
        resolve();
      },
    });
  });
};
