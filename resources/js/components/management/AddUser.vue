<template lang="">
    <div class="addUserForm">
        <p class="h3">Registro de usuarios para el acceso al sistema</p>
        <hr>
        <dynamic-form
            :form="form"
            @submitted="handleSubmit"
            @error="handleError"
            @change="handleChange"
        />
        <hr/>
        <button submit="true" ref="btnRef" class="btn btn-primary" style="width: 300px;" @click="handleSubmit">Registrar</button>
        <Notifications position="bottom left" />
    </div>
</template>
<script setup>
import { EmailField, PasswordField, TextField, SelectField, required, Validator, pattern, email } from '@asigloo/vue-dynamic-forms';
import { ref} from 'vue';
import { Notifications, notify } from '@kyvg/vue3-notification';
import axios from 'axios';
import anime from 'animejs';

const form = ref({
    id: 'register-form',
    fields:{
        name: TextField({
            label: 'Nombre',
            placeholder: 'Pedro',
            required: true,
            validations:[
                Validator({validator: required, text: 'El nombre es obligatorio'}),
                Validator({ validator: pattern( /^[a-zA-Z\s]{3,20}$/, ),text: 'El nombre debe contener al menos 3 caracteres y no más de 20, los numeros no son permitidos' })
            ]
        }),
        email: EmailField({
            label: 'Correo electronico',
            placeholder: 'pedro@gmail.com',
            required: true,
            validations:[
                Validator({validator: required, text: 'El correo electronico es obligatorio'}),
                Validator({validator: email, text: 'El correo electronico no es válido'})
            ]
        }),
        password: PasswordField({
            label: 'Contraseña',
            required: true,
            placeholder: '******',
            validations:[
                Validator({validator: required, text: 'La contraseña es obligatoria'} ),
                Validator({ validator: pattern( /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!\"#$%&'()*+,-./:;<=>?@[\\\]^_`{|}~]).{8,10}$/, ),text: 'La contraseña debe contener al menos 1 mayúscula, 1 minúscula, 1 número, 1 carácter especial y mínimo 8 caracteres max 10' })
            ]
        }),
        password_confirmation: PasswordField({
            label: 'Confirmar contraseña',
            required: true,
            placeholder: '******',
            validations:[
                Validator({validator: required, text: 'La confirmación de contraseña es obligatoria'}, ),
                Validator({ validator: pattern( /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!\"#$%&'()*+,-./:;<=>?@[\\\]^_`{|}~]).{8,10}$/, ),text: 'La contraseña debe contener al menos 1 mayúscula, 1 minúscula, 1 número, 1 carácter especial y mínimo 8 caracteres max 10' }) 
            ]
        }),
        role: SelectField({
            label: 'Rol',
            options: [
                { value: 'proyectos', label: 'Proyectos especiales' }, 
                { value: 'proyectos', label: 'Almacen' },
                { value: 'obras', label: 'Subdireccion de obras' }, 
            ],
            required: true,
            placeholder: "Selecciona un rol",
            validations:[
                Validator({validator: required, text: 'El rol es obligatorio'}, )
            ]
        }),
    },
});

const btnRef = ref(null);

const formData = ref({})

const handleSubmit = async () => {
    try{
        const formDt = JSON.stringify(formData);

        const response = await axios.post('/register', formDt, {
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if(response.status === 200){
            animateSendForm();

            notify({
                title:"Registro exitoso",
                text: "El usuario se ha registrado correctamente",
                type: "success",
                duration: 5000,
            })
        }

    }catch(e){
        notify({
            title:"Error",
            text: "Se encontro un error al enviar el formulario: "+e,
            type: "error",
            duration: 5000,
        })
    }
}

const handleError = (errors) => {
    notify({
        title:"Error, no se ha enviado el formulario",
        text: "No se ha validado correctamente el formulario, revise los campos",
        type: "error",
        duration: 5000,
    })
}

const handleChange = (data) => {
    formData.value = data;
}

const animateSendForm = ( )=> {
    anime.timeline()
    .add({
      targets: btnRef.value,
      width: ['300px', '30px'],      // Anima a un círculo de 50px width
      easing: 'easeOutExpo',
      duration: 500,
    })
    .add({
      targets: btnRef.value,
      easing: 'easeInOutSine',
      duration: 250,
      opacity: [1, 0],
    })
}
</script>
<style lang="css">
    .addUserForm{
        background-color: rgb(240, 240, 240);
        padding: 20px;
        margin: 20px;
        border-radius: 10px;
    }
</style>