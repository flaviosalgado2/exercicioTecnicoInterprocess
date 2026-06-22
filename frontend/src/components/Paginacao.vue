<script>
export default {
  name: 'Paginacao',
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
    paginasVisiveis() {
      const total = this.meta.last_page
      const atual = this.meta.current_page

      if (total <= 7) {
        return this.paginas
      }

      if (atual <= 4) {
        return [1, 2, 3, 4, 5, '...', total]
      }

      if (atual >= total - 3) {
        return [1, '...', total - 4, total - 3, total - 2, total - 1, total]
      }

      return [1, '...', atual - 1, atual, atual + 1, '...', total]
    },
  },
}
</script>

<template>
  <nav v-if="meta.last_page > 1" aria-label="Paginação">
    <ul class="pagination mb-0 d-none d-md-flex">
      <li class="page-item" :class="{ disabled: meta.current_page === 1 }">
        <button class="page-link" @click="$emit('change', meta.current_page - 1)" :disabled="meta.current_page === 1">
          Anterior
        </button>
      </li>
      <li
        v-for="(pagina, index) in paginasVisiveis"
        :key="`${pagina}-${index}`"
        class="page-item"
        :class="{ active: pagina === meta.current_page, disabled: pagina === '...' }"
      >
        <button
          class="page-link"
          @click="pagina !== '...' && $emit('change', pagina)"
          :disabled="pagina === '...'"
        >
          {{ pagina }}
        </button>
      </li>
      <li class="page-item" :class="{ disabled: meta.current_page === meta.last_page }">
        <button class="page-link" @click="$emit('change', meta.current_page + 1)" :disabled="meta.current_page === meta.last_page">
          Próxima
        </button>
      </li>
    </ul>

    <div class="d-md-none">
      <select
        class="form-select"
        :value="meta.current_page"
        @change="$emit('change', Number($event.target.value))"
      >
        <option v-for="pagina in paginas" :key="pagina" :value="pagina">
          Página {{ pagina }} de {{ meta.last_page }}
        </option>
      </select>
    </div>
  </nav>
</template>
