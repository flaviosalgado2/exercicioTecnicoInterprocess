import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import CadastroPacientes from '../views/CadastroPacientes.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/cadastro-pacientes',
      name: 'cadastro-pacientes',
      component: CadastroPacientes
    }
  ]
})

export default router
