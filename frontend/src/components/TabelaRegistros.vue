<script>
function valor(item, chave) {
  return chave.split('.').reduce((obj, k) => obj?.[k], item)
}

export default {
  name: 'TabelaRegistros',
  props: {
    items: {
      type: Array,
      default: () => [],
    },
    carregando: {
      type: Boolean,
      default: false,
    },
    colunas: {
      type: Array,
      required: true,
    },
    mensagemVazia: {
      type: String,
      default: 'Nenhum registro encontrado',
    },
  },
  emits: ['view', 'edit', 'inactivate'],
  methods: {
    texto(coluna, item) {
      const raw = valor(item, coluna.key)
      if (coluna.formatter) {
        return coluna.formatter(raw, item)
      }
      return raw ?? '-'
    },
  },
}
</script>

<template>
  <div class="table-responsive">
    <table class="table table-centered table-nowrap mb-0 rounded">
      <thead class="thead-light">
        <tr>
          <th
            v-for="(coluna, index) in colunas"
            :key="coluna.key"
            class="border-0"
            :class="{ 'rounded-start': index === 0 }"
          >
            {{ coluna.label }}
          </th>
          <th class="border-0 rounded-end text-center">Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="carregando">
          <td :colspan="colunas.length + 1" class="text-center py-4">Carregando...</td>
        </tr>
        <tr v-else-if="!items.length">
          <td :colspan="colunas.length + 1" class="text-center py-4">{{ mensagemVazia }}</td>
        </tr>
        <tr v-for="item in items" :key="item.id">
          <td v-for="coluna in colunas" :key="coluna.key">
            <span v-if="coluna.badge" class="badge" :class="item.status === 'ativo' ? 'bg-success' : 'bg-secondary'">
              {{ item.status === 'ativo' ? 'Ativo' : 'Inativo' }}
            </span>
            <template v-else>{{ texto(coluna, item) }}</template>
          </td>
          <td class="text-center">
            <button class="btn btn-link text-dark p-1" @click="$emit('view', item)" title="Visualizar">
              <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </button>
            <button class="btn btn-link text-dark p-1" @click="$emit('edit', item)" title="Editar">
              <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            </button>
            <button class="btn btn-link text-danger p-1" @click="$emit('inactivate', item)" title="Inativar" :disabled="item.status === 'inativo'">
              <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
