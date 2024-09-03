<template lang="">
    <div class="addUserForm">
        <p class="h3">Registro de usuarios para el acceso al sistema</p>
        <hr>
        <dynamic-form
            :form="form"
            @submitted="props.handleSubmit"
            @error="props-handleError"
            @change="props.handleChange"
        />
        <hr/>
        <button submit="true" :form="form?.id" class="btn btn-secondary">Registrar usuario</button>
    </div>
</template>
<script setup>
import { EmailField, PasswordField, TextField, SelectField, required, Validator, pattern, email } from '@asigloo/vue-dynamic-forms';
import { defineProps, ref } from 'vue';

const props = defineProps({
    handleSubmit: Function,
    handleError: Function,
    handleChange: Function,
});

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
</script>
<style lang="css">
    .addUserForm{
        background-color: rgb(240, 240, 240);
        padding: 20px;
        margin: 20px;
        border-radius: 10px;
    }
</style>