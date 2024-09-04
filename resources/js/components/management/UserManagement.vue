<template lang="html">
    <div class="manageUserForm">
      <div>
        <p class="h5">Usuarios registrados en la base de datos</p>
        <AgGridVue
          :rowData="users"
          :columnDefs="colDefs"
          style="height: 340px; width: 100%;"
          class="ag-theme-quartz mb-5"
          :frameworkComponents="{ actionRenderer }"
          :animateRows="true"
          :rowHeight="48"
        />
      </div>

      <Modal :id="'editModal'" :title="'Editar usuario'" :user="user" :getUsers="getUsers" />
      <Modal :id="'deleteModal'" title="Eliminar usuario" :user="user" :getUsers="getUsers" />
      
      <Notifications position="bottom left" />
    </div>
  </template>
  
  <script setup>
  import { onMounted, ref, defineComponent } from 'vue';
  import { Notifications, notify } from '@kyvg/vue3-notification';
  import axios from 'axios';
  import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the Data Grid
  import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the Data Grid
  import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component
  import Modal from '../auxiliares/Modal.vue';

  const user = ref({});
  
  // Renderer personalizado para la columna de acciones
  const actionRenderer = defineComponent({
    template: `
      <div >
        <button @click="editUser" class="btn btn-warning  btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"><v-icon animation="ring" hover name="ri-edit-2-fill"></v-icon> Editar</button>
        <button @click="deleteUser" class="btn btn-danger btn-sm  mx-2" data-bs-toggle="modal" data-bs-target="#deleteModal"><v-icon animation="ring" hover name="md-delete-round"></v-icon> Eliminar</button>
      </div>
    `,
    props: ['params'],
    methods: {
      editUser() {
        user.value = this.params.data;
      },
      deleteUser() {
        user.value = this.params.data;
      }
    }
  });

  const getUsers = async () => {
    try {
      const response = await axios.get('/getusers');
      users.value = response.data;
    } catch (e) {
      notify({
        title: "Error",
        text: "Se encontro un error al obtener los usuarios registrados: " + e,
        type: "error",
        duration: 5000,
        speed: 1000,
      });
    }
  };
  
  const users = ref([]);
  onMounted(() => {
    getUsers();
  });
  
  const colDefs = ref([
    { field: 'name', headerName: 'Nombre', filter: true, sortable: true, flex: 1 },
    { field: 'email', headerName: 'Correo electronico', filter: true, sortable: true, flex: 1 },
    { field: 'password', headerName: 'Contraseña', filter: true, sortable: true, flex: 1 },
    { field: 'created_at', headerName: 'Fecha de creacion', filter: true, sortable: true, flex: 1 },
    { field: 'updated_at', headerName: 'Fecha de actualizacion', filter: true, sortable: true, flex: 1 },
    { field: 'role', headerName: 'Rol', filter: true, sortable: true, flex: 1 },
    { field: 'actions', headerName: 'Acciones', cellRenderer: actionRenderer, flex: 1 },
  ]);
  
  const agProps = ref({
    pagination: true,
    paginationPageSize: 5,
    paginationPageSizeSelector: [5, 10, 20],
  });
  
  </script>
  <style lang="css">
  .manageUserForm {
    background-color: rgb(240, 240, 240);
    padding: 20px;
    margin: 20px;
    border-radius: 10px;
  }
  </style>
  