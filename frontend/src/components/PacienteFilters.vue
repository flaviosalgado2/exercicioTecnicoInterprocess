<script>
import { IMaskDirective } from 'vue-imask'
import { cpfMaskOptions, formatarCpf, limparCpf } from '@/utils/cpf'

export default {
  name: 'PacienteFilters',
  directives: {
    imask: IMaskDirective,
  },
  props: {
    modelValue: {
      type: Object,
      default: () => ({ nome: '', cpf: '', status: '' }),
    },
  },
  emits: ['filter'],
  data() {
    return {
      valores: { ...this.modelValue },
      cpfMascarado: formatarCpf(this.modelValue.cpf || ''),
      cpfMask: cpfMaskOptions,
    }
  },
  watch: {
    modelValue(novos) {
      this.valores = { ...novos }
      this.cpfMascarado = formatarCpf(novos.cpf || '')
    },
  },
  methods: {
    filtrosAtuais() {
      return {
        nome: this.valores.nome,
        cpf: limparCpf(this.cpfMascarado),
        status: this.valores.status,
      }
    },
    buscar() {
      this.$emit('filter', this.filtrosAtuais())
    },
    limpar() {
      this.valores = { nome: '', cpf: '', status: '' }
      this.cpfMascarado = ''
      this.buscar()
    },
  },
}
</script>

<template>
  <div class="row g-3 align-items-end">
    <div class="col-md-4">
      <label for="filtro-nome" class="form-label">Nome</label>
      <input id="filtro-nome" v-model="valores.nome" type="text" class="form-control" placeholder="Buscar por nome" maxlength="255" @keyup.enter="buscar" />
    </div>
    <div class="col-md-3">
      <label for="filtro-cpf" class="form-label">CPF</label>
      <input
        id="filtro-cpf"
        v-model="cpfMascarado"
        v-imask="cpfMask"
        type="text"
        class="form-control"
        placeholder="000.000.000-00"
        maxlength="14"
        @keyup.enter="buscar"
      />
    </div>
    <div class="col-md-3">
      <label for="filtro-status" class="form-label">Status</label>
      <select id="filtro-status" v-model="valores.status" class="form-select">
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
