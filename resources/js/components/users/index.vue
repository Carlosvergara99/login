

<template>
<div v-if="isAuthenticate">
  <v-toolbar color="purple" dark>
      <v-toolbar-title> Bienvenido  {{ userName.name }}</v-toolbar-title>

      <v-divider class="mx-4" vertical></v-divider>

      <v-spacer></v-spacer>

      <v-toolbar-items class="hidden-sm-and-down">
          <v-btn text to="/"> lagaout </v-btn>

          
      </v-toolbar-items>
  </v-toolbar>
  <v-data-table
    :headers="headers"
    :items="users"
    :sort-by="[{ key: 'id', order: 'asc' }]"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar
        flat
      >
        <v-toolbar-title>Usuarios</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          <template v-slot:activator="{ props }">
            <v-btn
              color="primary"
              dark
              class="mb-2"
              v-bind="props"
            >
              New user
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="text-h5">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col
                    cols="12"
                    sm="6"
                    md="12"
                  >
                    <v-text-field
                      v-model="editedItem.name"
                      label="Nombre"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="12"
                  >
                    <v-text-field
                      v-model="editedItem.email"
                      label="Email"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="12"
                  >
                    <v-text-field
                      v-model="editedItem.password"
                      type="Contraseña"
                      label="Password"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="12"
                  >
                    <v-text-field
                      v-model="editedItem.confirmPassword"
                      type="password"
                      label="Confirmar contraseña"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="12"
                  >
                    
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="blue-darken-1"
                variant="text"
                @click="close"
              >
                Cancel
              </v-btn>
              <v-btn
                color="blue-darken-1"
                variant="text"
                @click="save"
              >
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon
        size="small"
        class="me-2"
        @click="editItem(item.raw)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        size="small"
        @click="deleteItem(item.raw)"
      >
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn
        color="primary"
        @click="initialize"
      >
        Reset
      </v-btn>
    </template>
  </v-data-table>
</div>

</template>

<script>
  export default {
    data: () => ({
      dialog: false,
      dialogDelete: false,
      headers: [
        { title: '#', key: 'id' },
        { title: 'Nombre', key: 'name' },
        { title: 'Email', key: 'email' },
        { title: 'Actions', key: 'actions', sortable: false },
      ],
      users: [],
      editedIndex: -1,
      editedItem: {
        name: '',
        email: '',
        passwrod: '',
        passwrod_conf: '',
      },
      defaultItem: {
        name: '',
        email: '',
        passwrod: '',
        passwrod_conf: '',
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },
    },

    created () {
      this.initialize()
    },

    methods: {
      initialize () {
// Ejemplo de uso
        axios.get("/api/user/index")
              .then((response) => {
                this.users = response.data.data;

               }).catch((errors) => {
                 this.$swal(
                   'Error!',
                   errors.response.data.message,
                 'error'
                 );
               });
        
       
      },

      editItem (item) {
        this.editedIndex = this.users.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        this.editedIndex = this.desserts.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true
      },

      deleteItemConfirm () {
        this.desserts.splice(this.editedIndex, 1)
        this.closeDelete()
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

    reset () {
      this.$refs.editedItem.reset()
    },
     validate () {
        const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/
         const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

         if ( this.editedItem.name =='') {
  
            this.$swal(
             'Error!',
              'Por favor ingrese  nombre',
            'error'
           );
           return false;
          } else if  ( this.editedItem.email =='') {
  
           this.$swal(
            'Error!',
            'Por favor ingrese  el emaiñ',
            'error'
         );
         return false;

         }else if  ( this.editedItem.password =='') {
  
           this.$swal(
            'Error!',
            'Por favor ingrese la contraseña',
            'error'
         );
          return false;
         } else if  ( this.editedItem.confirmPassword =='') {
  
           this.$swal(
            'Error!',
            'Por favor ingrese la contraseña',
            'error'
          );
            return false;
         }else if (!emailRegex.test(this.editedItem.email)) {
          this.$swal(
            'Error!',
            'Please enter a valid email address',
            'error'
         );
            return false;
        } else if (!passwordRegex.test(this.editedItem.password)) {
           this.$swal(
            'Error!',
            'La contraseña debe tener al menos 8 caracteres y contener al menos un número, una letra mayúscula y una letra minúscula',
            'error'
            );
            return false;
         } else if (this.editedItem.password !== this.editedItem.confirmPassword) {
            this.$swal(
              'Error!',
              'la contraseña y la contraseña de confirmación deben ser las mismas',
              'error'
            );
          return false;
         }else {
            return true;
        }
    },
      save () {
        if (this.editedIndex > -1) {

           if (this.validate()) {
             axios.post("/api/user/create", this.editedItem)
              .then((response) => {
                Object.assign(this.desserts[this.editedIndex], this.editedItem)
                this.$swal(
                  'Good!',
                  'Registro creado correctamente!',
                  'success'
                );

               }).catch((errors) => {
                 this.$swal(
                   'Error!',
                   errors.response.data.message,
                 'error'
                 );
               });
            }
        } else {
          this.desserts.push(this.editedItem)
        }
        this.close()
        this.reset()
      },
    },
  }
</script>