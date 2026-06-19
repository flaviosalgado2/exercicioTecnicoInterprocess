<script>
import { RouterView } from 'vue-router'
import DefaultLayout from './layout/DefaultLayout.vue'

export default {
  name: 'App',
  components: {
    RouterView,
    DefaultLayout
  }
}
</script>

<template>
  <DefaultLayout>
    <RouterView />
  </DefaultLayout>
</template>
