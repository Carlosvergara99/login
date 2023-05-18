

<template>
  <div v-if="isAuthenticate">
    <v-toolbar color="purple" dark>
        <v-toolbar-title> Bienvenido  {{ userName.name }}</v-toolbar-title>
  
        <v-divider class="mx-4" vertical></v-divider>
  
        <v-spacer></v-spacer>
  
        <v-toolbar-items class="hidden-sm-and-down">
            <v-btn text   @click="lagout(userName.id)"> logout </v-btn>
  
            
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
                        type="password"
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
                  @click="cancel"
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
              <v-card-title class="text-h5">¿Estas seguro de eliminar este usuario?</v-card-title>
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
          icon="edit"
          @click="editItem(item.raw)"
        >
          
        </v-icon>
        <v-icon
          size="small"
          @click="deleteItem(item.raw)"
        >
          delete
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
        dialogValidate: false, 
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
          id:''
        },
        defaultItem: {
          name: '',
          email: '',
          passwrod: '',
          passwrod_conf: '',
          id:''
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
          axios.get("/api/user/index")
                .then((response) => {
                  this.users = response.data.users;
  
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
          this.editedIndex = this.users.indexOf(item)
          this.editedItem = Object.assign({}, item)
          this.dialogDelete = true
        },
  
        deleteItemConfirm () {
          axios.post("/api/user/delete", this.editedItem)
                .then((response) => {
                  this.users.splice(this.editedIndex, 1)
  
                  this.$swal(
                    'Eliminado!',
                    'El usuario ha sido eliminado.',
                    'success'
                  );
                  this.$router.replace('/users');
  
                 }).catch((errors) => {
                   this.$swal(
                     'Error!',
                     errors.response.data.message,
                   'error'
                   );
                });
          this.closeDelete()
        },
  
        closeValidate () {
          this.dialog = false
          this.$nextTick(() => {
            this.editedIndex = -1
          })
        },
        cancel(){
          this.dialogValidate = true;
          this.close()
        },
        close () {
          this.dialog = false
          if (this.dialogValidate) {
            this.$nextTick(() => {
             this.editedItem = Object.assign({}, this.defaultItem)
             this.editedIndex = -1
            })
          }else{
            this.$nextTick(() => {
            // this.editedItem = Object.assign({}, this.defaultItem)
             this.editedIndex = -1
            })
          }
        },
  
        closeDelete () {
          this.dialogDelete = false
          this.$nextTick(() => {
            this.editedItem = Object.assign({}, this.defaultItem)
            this.editedIndex = -1
          })
        },
       validate (edit=false) {
          const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/
           const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
  
           if ( this.editedItem.name =='') {
            this.dialogValidate =false
            this.close()
  
              this.$swal(
               'Error!',
                'Por favor ingrese  nombre',
              'error'
             );
  
  
            } else if  ( this.editedItem.email =='') {
    
              this.dialogValidate =false
              this.close()
  
             this.$swal(
              'Error!',
              'Por favor ingrese  el emaiL',
              'error'
           );
          
  
           }else if  ( this.editedItem.password =='' && edit) {
            this.dialogValidate =false
            this.close()
  
             this.$swal(
              'Error!',
              'Por favor ingrese la contraseña',
              'error'
           );
           
           } else if  ( this.editedItem.confirmPassword =='' && edit) {
             this.dialogValidate =false
             this.close()
  
             this.$swal(
              'Error!',
              'Por favor ingrese la contraseña',
              'error'
            );
             
           }else if (!emailRegex.test(this.editedItem.email)) {
            this.dialogValidate =false
            this.close()
  
            this.$swal(
              'Error!',
              'Please enter a valid email address',
              'error'
           );
  
          } else if (!passwordRegex.test(this.editedItem.password)&&  this.editedItem.password != undefined) {
            this.dialogValidate =false
            this.close()
             this.$swal(
              'Error!',
              'La contraseña debe tener al menos 8 caracteres y contener al menos un número, una letra mayúscula y una letra minúscula',
              'error'
              );
           } else if (this.editedItem.password !== this.editedItem.confirmPassword) {
              this.dialogValidate =false
              this.close()
              this.$swal(
                'Error!',
                'la contraseña y la contraseña de confirmación deben ser las mismas',
                'error'
              );
           
           }else {
            this.dialogValidate =true
              
          }
      },
      lagout(id){
        const data = {
          id:id
        }
        axios.post("/api/logout",data)
        .then((response) => {
          this.$swal(
            'Good!',
            'Sesión cerrada correctamente!',
            'success'
          );
          this.$cookies.remove('user')
          this.$router.replace('/');
         }).catch((errors) => {
           this.$swal(
             'Error!',
             errors.response.data.message,
           'error'
           );
         });
      },
        save () {
  
  
          if (this.editedIndex > -1) {
           
            this.validate(true);
            if (this.dialogValidate ) {
              axios.post("/api/user/update", this.editedItem)
  
                .then((response) => {
                  this.dialogValidate =true
                  Object.assign(this.users[this.editedIndex], response.data.user)
                  this.close()
                  this.$swal(
                    'Good!',
                    'Registro actualizado correctamente!',
                    'success'
                  );
                 }).catch((errors) => {
                  this.dialogValidate =false
                  this.close()
                   this.$swal(
                     'Error!',
                     errors.response.data.message,
                   'error'
                   );
                 });
              }
  
          } else {
  
            this.validate(this.editedIndex);
  
            if (this.dialogValidate ) {
               axios.post("/api/user/create", this.editedItem)
                .then((response) => {
                  this.dialogValidate =true
                  this.users.push(response.data.user);      
                  this.close()
                  this.$swal(
                    'Good!',
                    'Registro creado correctamente!',
                    'success'
                  );
  
                 }).catch((errors) => {
                  this.dialogValidate =false
                  this.close()
                   this.$swal(
                     'Error!',
                     errors.response.data.message,
                   'error'
                   );
                 });
              }
          }
        },
      },
    }
  </script>