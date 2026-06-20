<script>
export default {
  name: 'Pagination',
  props: {
    meta: {
      type: Object,
      default: () => ({}),
    },
  },
  emits: ['change'],
  computed: {
    paginas() {
      const pages = []

      for (let i = 1; i <= this.meta.last_page; i++) {
        pages.push(i)
      }

      return pages
    },
  },
}
</script>

<template>
  <nav v-if="meta.last_page > 1" aria-label="Paginação">
    <ul class="pagination mb-0">
      <li class="page-item" :class="{ disabled: meta.current_page === 1 }">
        <button class="page-link" @click="$emit('change', meta.current_page - 1)" :disabled="meta.current_page === 1">
          Anterior
        </button>
      </li>
      <li
        v-for="pagina in paginas"
        :key="pagina"
        class="page-item"
        :class="{ active: pagina === meta.current_page }"
      >
        <button class="page-link" @click="$emit('change', pagina)">
          {{ pagina }}
        </button>
      </li>
      <li class="page-item" :class="{ disabled: meta.current_page === meta.last_page }">
        <button class="page-link" @click="$emit('change', meta.current_page + 1)" :disabled="meta.current_page === meta.last_page">
          Próxima
        </button>
      </li>
    </ul>
  </nav>
</template>
