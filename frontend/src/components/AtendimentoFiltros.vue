<script>
export default {
  name: 'AtendimentoFiltros',
  props: {
    filtros: {
      type: Object,
      default: () => ({ data_inicio: '', data_fim: '', paciente_id: '', status: '' }),
    },
  },
  emits: ['filter'],
  data() {
    return {
      valores: { data_inicio: '', data_fim: '', paciente_nome: '', status: '', ...this.filtros },
    }
  },
  watch: {
    filtros(novos) {
      this.valores = { ...novos }
    },
  },
  methods: {
    filtrosAtuais() {
      return {
        data_inicio: this.valores.data_inicio,
        data_fim: this.valores.data_fim,
        paciente_nome: this.valores.paciente_nome,
        status: this.valores.status,
      }
    },
    buscar() {
      this.$emit('filter', this.filtrosAtuais())
    },
    limpar() {
      this.valores = { data_inicio: '', data_fim: '', paciente_nome: '', status: '' }
      this.buscar()
    },
    buscar() {
      this.$emit('filter', this.filtrosAtuais())
    },
    limpar() {
      this.valores = { data_inicio: '', data_fim: '', paciente_id: '', status: '' }
      this.buscar()
    },
  },
}
</script>

<template>
  <div class="row g-3 align-items-end">
    <div class="col-md-3">
      <label for="filtro-data-inicio" class="form-label">Data inicial</label>
      <input id="filtro-data-inicio" v-model="valores.data_inicio" type="date" class="form-control" />
    </div>
    <div class="col-md-3">
      <label for="filtro-data-fim" class="form-label">Data final</label>
      <input id="filtro-data-fim" v-model="valores.data_fim" type="date" class="form-control" />
    </div>
    <div class="col-md-2">
      <label for="filtro-status" class="form-label">Status</label>
      <select id="filtro-status" v-model="valores.status" class="form-select">
        <option value="">Todos</option>
        <option value="ativo">Ativo</option>
        <option value="inativo">Inativo</option>
      </select>
    </div>
    <div class="col-md-4">
      <label for="filtro-paciente" class="form-label">Paciente</label>
      <input
        id="filtro-paciente"
        v-model="valores.paciente_nome"
        type="text"
        class="form-control"
        placeholder="Buscar por nome do paciente"
        maxlength="255"
        @keyup.enter="buscar"
      />
    </div>
    <div class="col-md-12 d-flex gap-2 justify-content-end">
      <button class="btn btn-primary" @click="buscar">Filtrar</button>
      <button class="btn btn-outline-secondary" @click="limpar">Limpar</button>
    </div>
  </div>
</template>
