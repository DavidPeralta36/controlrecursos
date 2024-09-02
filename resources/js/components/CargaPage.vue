<template lang="html">
    <div class="container">
        <div class="mt-3">
            <p class="h2 nunito-bold" style="font-style: normal">Carga de banco a la base de datos</p>
            <hr/>
            <div style="min-height: 16vh">
                <SourcePicker :fuentes="fuentes" :handleSelect="handleSelect"/>
            </div>
            <div class="d-flex mt-3 w-100 justify-content-between">
                <div class="d-flex align-items-start ">
                    <Toggle v-model="newBank" />
                    <p class="nunito ml-2">{{ newBank ? 'Nuevo banco' : 'Banco existente' }}</p>
                </div>
                <div v-if="!newBank">
                    <p class="nunito">Selecciona el ejercicio</p>
                    <VueSelect v-model="selectedPeriod" :options="periodos" label="ejercicio" style="min-width: 11vw;" />
                </div>
                <div v-else>
                    <p class="nunito">Ingresa el ejercicio</p>
                    <input v-model="newBankYear" type="text" class="form-control" placeholder="Ejercicio" aria-label="Ejercicio" style="min-width: 11vw;" @input="handleYearChange" />
                </div>
            </div>
            <hr/>
            <div ref="uploadContainer" style="opacity: 0; " v-if="valid">
                <p class="nunito h4">Origen de datos</p>
                <div class="input-group mb-3" >
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupFileAddon01">Cargar</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="bankFile" aria-describedby="ariaBankFile" @change="handleFileChange">
                        <label class="custom-file-label" for="bankFile">{{ bankFile ? bankFile.name : 'Seleccionar archivo' }}</label>
                    </div>
                </div>
            </div>
            <button v-if="readyToSend" ref="sendButton" class="btn btn-primary" @click="habdleUploadFile">{{ sendButtonAnimated ? 'Subir origen de datos' : ''}}</button>
        </div>
        <Notifications position="bottom left" />
    </div>
</template>

<script setup>
import { ref, defineProps, nextTick, watch } from 'vue';
import SourcePicker from './homepage_components/SourcePicker.vue';
import Toggle from '@vueform/toggle/src/Toggle';
import '@vueform/toggle/themes/default.css'
import VueSelect from 'vue-select';
import "vue-select/dist/vue-select.css"
import anime from 'animejs';
import { Notifications, notify } from '@kyvg/vue3-notification';
import axios from 'axios';
import * as XLSX from 'xlsx';

const props = defineProps({
    user: Object,
    fuentes: Array,
    periodos: Array
});

const selectedSource = ref(null);
const uploadContainer = ref(null);
const newBank = ref(true);
const selectedPeriod = ref(null);
const newBankYear = ref(null);
const valid = ref(false);
const bankFile = ref(null);
const excelData = ref(null);
const readyToSend = ref(false);
const sendButton = ref(null);
const sendButtonAnimated = ref(false);

const headerColumnsSearch = ['FECHA', 'MES'];

const handleSelect = async (source) => {
  selectedSource.value = source.id;
  if((selectedSource.value && selectedPeriod.value) || (selectedSource.value && newBankYear.value)) {
    valid.value = true;

    await nextTick();

    animateUploadContainer();
  }
}

const handleYearChange = async (e) => {
    if (e.target.value.length === 4) {
        newBankYear.value = e.target.value;
        if(newBankYear.value && selectedSource.value) {
            valid.value = true;
            await nextTick();
            animateUploadContainer();
        }
    }
}

const handleFileChange = async (e) => {
  const file = e.target.files[0];
  bankFile.value = file;

  if (bankFile.value) {
    if(selectedSource && selectedSource.value == 1){
        loadU013();
    }

    if(selectedSource && selectedSource.value == 4){
        loadASLE();
    }

    if(selectedSource && selectedSource.value == 5){
        loadE001();
    }
  }
}

const loadE001 = () => {
    const reader = new FileReader();

    reader.onload = async (event) => {
      const data = new Uint8Array(event.target.result);
      const woorkbook = XLSX.read(data, { type: 'array' });

      const sheetName = woorkbook.SheetNames[3];
    
      if(sheetName){
        const woorksheet = woorkbook.Sheets[sheetName];

        const sheetJson = XLSX.utils.sheet_to_json(woorksheet, { header: 1, blankrows: false });

        const headerRowIndex = sheetJson.findIndex(row => 
            headerColumnsSearch.some(col => row.includes(col))
        );

        if(headerRowIndex !== -1){
            const dataRows = sheetJson.slice(headerRowIndex);
          
            const maxColumns = dataRows.reduce((max, row) => Math.max(max, row.length), 0);

            const formattedDataRows = dataRows.map(row => {
                const formattedRow = row.map(cell => {
                    if (typeof cell === 'number' && cell === row[0]) {
                        if (cell > 59 && cell < 2958465) { 
                            return XLSX.SSF.format("yyyy-mm-dd", cell);
                        }
                    }

                    if (typeof cell === 'string' && cell === row[11]) {
                        if(cell.length === 0 || cell === '' || cell === ' '){
                            return null;
                        }
                    }

                    return cell;
                });

                while (formattedRow.length < maxColumns) {
                formattedRow.push(null);
                }

                return formattedRow;
            });

            excelData.value = formattedDataRows;
        }

        notify({
            title: 'Archivo cargado y listo para enviar',
            text: 'Favor de verificar el archivo antes de enviar',
            type: 'info',
            duration: 5000,
            speed: 1000,
            })
        }

        readyToSend.value = true;
        await nextTick();
        animateSendButton();
    }

    reader.onerror = () => {
      notify({
        title: 'Error al leer el archivo',
        text: 'Ocurrió un problema al leer el archivo, por favor intente nuevamente.',
        type: 'error',
        duration: 5000,
        speed: 1000,
      });
    };

    reader.readAsArrayBuffer(bankFile.value);
}

const loadASLE = () => {
    const reader = new FileReader();

    reader.onload = async (event) => {
      const data = new Uint8Array(event.target.result);
      const woorkbook = XLSX.read(data, { type: 'array' });

      const sheetName = woorkbook.SheetNames[3];
    
      if(sheetName){
        const woorksheet = woorkbook.Sheets[sheetName];

        const sheetJson = XLSX.utils.sheet_to_json(woorksheet, { header: 1, blankrows: false });

        const headerRowIndex = sheetJson.findIndex(row => 
            headerColumnsSearch.some(col => row.includes(col))
        );

        if(headerRowIndex !== -1){
            const dataRows = sheetJson.slice(headerRowIndex);
          
            const maxColumns = dataRows.reduce((max, row) => Math.max(max, row.length), 0);

            const formattedDataRows = dataRows.map(row => {
                const formattedRow = row.map(cell => {
                    if (typeof cell === 'number' && cell === row[1]) {
                        if (cell > 59 && cell < 2958465) { 
                            return XLSX.SSF.format("yyyy-mm-dd", cell);
                        }
                    }

                    if (typeof cell === 'string' && cell === row[12]) {
                        if(cell.length === 0 || cell === '' || cell === ' '){
                            return null;
                        }
                    }

                    return cell;
                });

                while (formattedRow.length < maxColumns) {
                formattedRow.push(null);
                }

                return formattedRow;
            });

            excelData.value = formattedDataRows;
        }

        notify({
            title: 'Archivo cargado y listo para enviar',
            text: 'Favor de verificar el archivo antes de enviar',
            type: 'info',
            duration: 5000,
            speed: 1000,
            })
        }

        readyToSend.value = true;
        await nextTick();
        animateSendButton();
    }

    reader.onerror = () => {
      notify({
        title: 'Error al leer el archivo',
        text: 'Ocurrió un problema al leer el archivo, por favor intente nuevamente.',
        type: 'error',
        duration: 5000,
        speed: 1000,
      });
    };

    reader.readAsArrayBuffer(bankFile.value);
}

const loadU013= () => {
    const reader = new FileReader();

    reader.onload = async (event) => {
      const data = new Uint8Array(event.target.result);
      const woorkbook = XLSX.read(data, { type: 'array' });

      const sheetName = woorkbook.SheetNames[3];
    
      if(sheetName){
        const woorksheet = woorkbook.Sheets[sheetName];

        const sheetJson = XLSX.utils.sheet_to_json(woorksheet, { header: 1, blankrows: false });

        const headerRowIndex = sheetJson.findIndex(row => 
            headerColumnsSearch.some(col => row.includes(col))
        );

        if(headerRowIndex !== -1){
            const dataRows = sheetJson.slice(headerRowIndex);
          
            const maxColumns = dataRows.reduce((max, row) => Math.max(max, row.length), 0);

            const formattedDataRows = dataRows.map(row => {
                const formattedRow = row.map(cell => {
                    if (typeof cell === 'number' && cell === row[1]) {
                        if (cell > 59 && cell < 2958465) { 
                            return XLSX.SSF.format("yyyy-mm-dd", cell);
                        }
                    }

                    if (typeof cell === 'string' && cell === row[5]) {
                        if(cell.length === 0 || cell === '' || cell === ' '){
                            return null;
                        }
                    }

                    if (typeof cell === 'string' && cell === row[13]) {
                        if(cell.length === 0 || cell === '' || cell === ' '){
                            return null;
                        }
                    }

                    if (typeof cell === 'string' && cell === row[24]) {
                        if(cell.length === 0 || cell === '' || cell === ' '){
                            return null;
                        }
                    }

                    return cell;
                });

                while (formattedRow.length < maxColumns) {
                formattedRow.push(null);
                }

                return formattedRow;
            });

            excelData.value = formattedDataRows;
        }

        notify({
            title: 'Archivo cargado y listo para enviar',
            text: 'Favor de verificar el archivo antes de enviar',
            type: 'info',
            duration: 5000,
            speed: 1000,
            })
        }

        readyToSend.value = true;
        await nextTick();
        animateSendButton();
    }

    reader.onerror = () => {
      notify({
        title: 'Error al leer el archivo',
        text: 'Ocurrió un problema al leer el archivo, por favor intente nuevamente.',
        type: 'error',
        duration: 5000,
        speed: 1000,
      });
    };

    reader.readAsArrayBuffer(bankFile.value);
}

const habdleUploadFile = async () => {
    sendButtonAnimated.value = false;
    await nextTick();
    animateLoading();

    const formData = new FormData();
    const jsonBlob = new Blob([JSON.stringify(excelData.value)], { type: 'application/json' });
    formData.append('archivo', jsonBlob, 'data.json');
    formData.append('source', selectedSource.value);
    formData.append('periodo', newBankYear.value);
    try {
        notify({
                title: 'Subiendo archivo',
                text: 'La información se esta guardando en el servidor',
                type: 'warn',
                duration: 5000,
                speed: 1000,
            });
        const response = await axios.post('/upload_report', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response.status === 200) {
            notify({
                title: 'Banco actualizado',
                text: 'La información se ha subido correctamente',
                type: 'success',
                duration: 5000,
                speed: 1000,
            });
            endAnimation();
            selectedPeriod.value = null;
            selectedSource.value = null;
            newBankYear.value = null;
        } else {
            notify({
                title: 'Error al subir archivo',
                text: 'Favor de verificar el archivo antes de enviar',
                type: 'error',
                duration: 5000,
                speed: 1000,
            });
        }
    } catch (e) {
        console.log(e);
    }
}
//watch selectedPeriod
watch(selectedPeriod, async () => {
    if(selectedPeriod.value && selectedSource.value) {
        valid.value = true;
        await nextTick();
        animateUploadContainer();
    }
})

const animateUploadContainer = () => {
    anime({
        targets: uploadContainer.value,
        opacity: [0, 1],
        duration: 500,
        easing: 'easeInOutQuad',
    });
}

const animateSendButton = () => {
    anime.timeline()
    .add({
      targets: sendButton.value,
      opacity: [0, 1],
      width: [0, '30px'],      // Anima a un círculo de 50px
      height: [0, '30px'],
      borderRadius: '10px', // Mantén la forma circular
      easing: 'easeOutExpo',
      duration: 500,
      overflow: 'hidden',
    })
    .add({
      targets: sendButton.value,
      rotate: '1turn',   // Gira 360 grados
      easing: 'easeInOutSine',
      duration: 250,
    })
    .add({
      targets: sendButton.value,
      width: '200px',     // Cambia a tamaño normal del botón
      height: '40px',
      borderRadius: '10px', // Elimina el borde redondeado
      easing: 'easeOutExpo',
      duration: 400,

      complete: () => {
        sendButtonAnimated.value = true;
      }
    });
}

const animateLoading = () => {
    anime.remove(sendButton.value);
    
    anime({
        targets: sendButton.value,
        opacity: [0, 1],
        width: ['200px', '30px'],
        height: ['40px', '30px'],
        borderRadius: '5px',
        easing: 'easeOutExpo',
        duration: 500,
    })
    //alterna la rotación de la imagen
    anime({
        targets: sendButton.value,
        rotate: '2turn',
        width: ['30px', '40px'],
        height: ['30px', '40px'],
        easing: 'easeInOutSine',
        borderRadius: '15px',
        duration: 750,
        direction: 'alternate',
        delay:250,
        loop: true,
    })
}

const endAnimation = () => {
    anime.remove(sendButton.value);

    anime({
        targets: sendButton.value,
        width: ['30px', '200px'],
        height: ['30px', '40px'],
        borderRadius: '5px',  // Puede que prefieras un borderRadius diferente al original
        easing: 'easeOutExpo',
        duration: 500,
        rotate: '0deg', // Asegura que el botón esté horizontal
        complete: () => {
            sendButtonAnimated.value = true;
        }
    });
}
</script>


<style >
.fuentes{
    height: 10vh;
    width: 100%;
    background-color: rgb(240, 240, 240);
    border-radius: 10px;
}
</style>