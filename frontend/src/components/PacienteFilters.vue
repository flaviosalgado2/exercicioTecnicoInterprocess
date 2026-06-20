<script>
import { formatarCpf, limparCpf } from '@/utils/cpf'

export default {
  name: 'PacienteFilters',
  emits: ['filter'],
  data() {
    return {
      filtros: {
        nome: '',
        cpf: '',
        status: '',
      },
    }
  },
  computed: {
    cpfFormatado: {
      get() {
        return formatarCpf(this.filtros.cpf)
      },
      set(valor) {
        this.filtros.cpf = limparCpf(valor)
      },
    },
  },
  methods: {
    buscar() {
      this.$emit('filter', { ...this.filtros })
    },
    limpar() {
      this.filtros = { nome: '', cpf: '', status: '' }
      this.$emit('filter', { ...this.filtros })
    },
  },
}
</script>

<template>
  <div class="row g-3 align-items-end">
    <div class="col-md-4">
      <label for="filtro-nome" class="form-label">Nome</label>
      <input id="filtro-nome" v-model="filtros.nome" type="text" class="form-control" placeholder="Buscar por nome" @keyup.enter="buscar" />
    </div>
    <div class="col-md-3">
      <label for="filtro-cpf" class="form-label">CPF</label>
      <input id="filtro-cpf" v-model="cpfFormatado" type="text" class="form-control" placeholder="000.000.000-00" @keyup.enter="buscar" />
    </div>
    <div class="col-md-3">
      <label for="filtro-status" class="form-label">Status</label>
      <select id="filtro-status" v-model="filtros.status" class="form-select">
        <option value="">Todos</option>
        <option value="ativo">Ativo</option>
        <option value="inativo">Inativo</option>
      </select>
    </div>
    <div class="col-md-2 d-flex gap-2">
      <button class="btn btn-primary w-100" @click="buscar">Filtrar</button>
      <button class="btn btn-outline-secondary" @click="limpar">Limpar</button>
    </div>
  </div>
</template>
