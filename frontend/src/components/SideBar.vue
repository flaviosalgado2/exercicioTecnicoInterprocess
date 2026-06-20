<script>
import logo from '@/assets/img/brand/Logos_Interprocess_page-0002-removebg-preview.png';
export default {
  name: 'SideBar',
  data() {
    return {
      logo
    }
  }
}
</script>

<template>
  <nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
    <div class="sidebar-inner px-2 pt-3">
      <div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
        <div class="d-flex align-items-center">
          <div class="d-block">
            <h2 class="h5 mb-3">Menu</h2>
          </div>
        </div>
        <div class="collapse-close d-md-none">
          <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
            <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
          </a>
        </div>
      </div>
      <ul class="nav flex-column pt-3 pt-md-0">
        <li class="nav-item">
          <span class="nav-link logo-link d-flex align-items-center">
            <span class="sidebar-icon">
              <img :src="logo" alt="Interprocess">
            </span>
          </span>
        </li>
        <li class="nav-item" :class="{ active: $route.path === '/' }">
          <router-link to="/" custom v-slot="{ href, navigate, isActive }">
            <a :href="href" @click="navigate" class="nav-link d-flex align-items-center" :class="{ active: isActive }">
              <span class="sidebar-icon icon-holder">
                <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
              </span>
              <span class="sidebar-text">Início</span>
            </a>
          </router-link>
        </li>
        <li class="nav-item" :class="{ active: $route.path === '/cadastro-pacientes' }">
          <router-link to="/cadastro-pacientes" custom v-slot="{ href, navigate, isActive }">
            <a :href="href" @click="navigate" class="nav-link d-flex align-items-center" :class="{ active: isActive }">
              <span class="sidebar-icon icon-holder">
                <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
              </span>
              <span class="sidebar-text">Cadastro de Pacientes</span>
            </a>
          </router-link>
        </li>
      </ul>
    </div>
  </nav>
</template>

<style scoped>
.logo-link {
  cursor: default;
}

.logo-link:hover {
  color: #ffffff !important;
  background-color: transparent !important;
}

.icon-holder {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 24px;
  margin-right: 0.5rem;
}

.icon-holder .icon,
.icon-holder img {
  display: block;
}
</style>
