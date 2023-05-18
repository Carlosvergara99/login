<template>
  <v-app>
    <v-main>
      <v-container fluid fill-height v-if="!isAuthenticate" >
        <v-row justify="center">
          <v-col cols="4">
            <v-sheet class="pa-2 ma-2">
              <v-flex xs12 sm8 md4>
                  <v-card class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{isRegister ? stateObj.register.name : stateObj.login.name}}</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>
                     <form ref="form" @submit.prevent="isRegister ? register() : login()">

                          <v-text-field v-if="isRegister"
                              v-model="name"
                              name="Nombre"
                              label="Nombre"
                              type="text"
                              placeholder="Nombre"
                              required
                           ></v-text-field>

                            <v-text-field
                              v-model="email"
                              name="Email"
                              label="Email"
                              type="email"
                              placeholder="Email"
                              required
                           ></v-text-field>
                           
                            <v-text-field
                              v-model="password"
                              name="Contraseña"
                              label="Password"
                              type="password"
                              placeholder="Contraseña"
                              required
                           ></v-text-field>

                           <v-text-field v-if="isRegister"
                              v-model="confirmPassword"
                              name="confirmPassword"
                              label="confirmPassword"
                              type="password"
                              placeholder="password"
                              required
                           ></v-text-field>
                         
                           <v-btn type="submit" class="mt-4" color="primary" value="log in">{{isRegister ? stateObj.register.name : stateObj.login.name}}</v-btn>
                           <div class="grey--text mt-4" v-on:click="isRegister = !isRegister;">
                              {{toggleMessage}}  
                           </div>
                      </form>
                     </v-card-text>
                  </v-card>
                
               </v-flex>
            </v-sheet>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>

export default {
  data() {
    return {
      email: "",
      password: "",
      confirmPassword: "",
      isRegister: false,
      name: "",
      stateObj: {
        register: {
          name: 'Registrar',
          message: '¿Ya tienes una cuenta? acceso.'
        },
        login: {
          name: 'Login',
          message: 'Registrar una cuenta.'
        }
      }
    };
  },
 
  methods: {

    
    login() {

      const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/
      if (!emailRegex.test(this.email)) {
        this.$swal(
            'Error!',
            'Por favor, introduce una dirección de correo electrónico válida',
            'error'
         );
      }else if( this.password ==''){
        this.$swal(
            'Error!',
            'Por favor ingrese la contraseña',
            'error'
         );
      }else{
        const data ={
          email: this.email,
          password: this.password
        }
        axios.post("/api/login", data)
        .then((response) => {
         this.$cookies.set('user', response.data.user);
          this.$cookies.set('token', response.data.token);

         this.$router.replace('/users');

        })
        .catch((errors) => {
          this.$swal(
            'Error!',
             errors.response.data.message,
            'error'
         );
        });
      }
    },
    register() {


      const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/
      const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

      if ( this.name =='') {
  
        this.$swal(
            'Error!',
            'Por favor ingrese  nombre',
            'error'
         );
      } else if  ( this.email =='') {
  
         this.$swal(
            'Error!',
            'Por favor ingrese  el emaiñ',
            'error'
         );
       } else if  ( this.password =='') {
  
         this.$swal(
            'Error!',
            'Por favor ingrese la contraseña',
            'error'
         );
      } else if  ( this.confirmPassword =='') {
  
         this.$swal(
            'Error!',
            'Por favor ingrese la contraseña',
            'error'
         );
      }else if (!emailRegex.test(this.email)) {
          this.$swal(
            'Error!',
            'Please enter a valid email address',
            'error'
         );
      } else if (!passwordRegex.test(this.password)) {
        this.$swal(
            'Error!',
            'La contraseña debe tener al menos 8 caracteres y contener al menos un número, una letra mayúscula y una letra minúscula',
            'error'
         );

      } else if (this.password !== this.confirmPassword) {
        this.$swal(
            'Error!',
            'la contraseña y la contraseña de confirmación deben ser las mismas',
            'error'
         );
      }else{
        
        const data ={
          name: this.name,
          email: this.email,
          password: this.password,
          confirmPassword: this.confirmPassword
        }
        axios.post("/api/register", data)
        .then((response) => {
         this.dialog = false
         this.$router.replace('/users');

        })
        .catch((errors) => {
          this.$swal(
            'Error!',
             errors.response.data.message,
            'error'
         );
        });
      }
    }
  },
 
  computed: {
    toggleMessage: function () {
      this.email= "",
      this.password= "",
      this.confirmPassword="",
      this.name=''
      return this.isRegister ? this.stateObj.register.message : this.stateObj.login.message
    }
  }
};
</script>