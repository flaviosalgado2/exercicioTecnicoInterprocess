import { createApp } from 'vue'
import { createPinia } from 'pinia'

import 'bootstrap/dist/css/bootstrap.min.css'
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js'
import Swal from 'sweetalert2'

window.bootstrap = bootstrap
window.Swal = Swal

import '@/assets/css/volt.css'
import '@/assets/js/volt.js'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
