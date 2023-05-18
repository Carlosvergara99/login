
import './bootstrap';
import 'material-design-icons-iconfont/dist/material-design-icons.css' // Ensure your project is capable of handling css files

import { createApp } from 'vue';
import router from "./components/routes";

import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as labsComponents from 'vuetify/labs/components'

import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { aliases, md } from 'vuetify/iconsets/md'
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import VueCookies from 'vue-cookies';
import Auth  from './components/mixins/auth.js';


const vuetify = createVuetify({
    icons: {
        defaultSet: 'md',
        aliases,
        sets: {
          md,
        },
    },
    components: {
      ...components,
      ...labsComponents,
    },
  directives,
  
})

const app = createApp({});

app.use(vuetify);
app.use(router);
app.use(VueSweetalert2);
app.use(VueCookies);
app.mixin(Auth);
app.mount('#app');
