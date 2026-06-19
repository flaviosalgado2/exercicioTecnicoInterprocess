<script>
import NavBarMobile from '@/components/NavBarMobile.vue'
import SideBar from '@/components/SideBar.vue'
import TopBar from '@/components/TopBar.vue'
import Footer from '@/components/Footer.vue'

export default {
  name: 'DefaultLayout',
  components: {
    NavBarMobile,
    SideBar,
    TopBar,
    Footer
  }
}
</script>

<template>
  <NavBarMobile />
  <SideBar />
  <main class="content d-flex flex-column">
    <TopBar />
    <div class="flex-grow-1">
      <slot></slot>
    </div>
    <Footer />
  </main>
</template>

<style scoped>
.content {
  min-height: 100vh;
}
</style>
