<script>
import PacienteFilters from '@/components/PacienteFilters.vue'
import PacienteTable from '@/components/PacienteTable.vue'
import PacienteModal from '@/components/PacienteModal.vue'
import PacienteViewModal from '@/components/PacienteViewModal.vue'
import Pagination from '@/components/Pagination.vue'
import { usePacientesStore } from '@/stores/pacientes'
import { mapState, mapActions } from 'pinia'

export default {
  name: 'CadastroPacientes',
  components: {
    PacienteFilters,
    PacienteTable,
    PacienteModal,
    PacienteViewModal,
    Pagination,
  },
  data() {
    return {
      filtros: {},
      pagina: 1,
      porPagina: 10,
      pacienteEdicao: null,
      pacienteVisualizacao: null,
      mensagem: '',
      tipoMensagem: '',
    }
  },
  computed: {
    ...mapState(usePacientesStore, ['pacientes', 'meta', 'carregando', 'erro']),
  },
  mounted() {
    this.carregar()
  },
  methods: {
    ...mapActions(usePacientesStore, ['listar', 'salvar', 'inativar']),

    async carregar() {
      await this.listar(this.filtros, this.pagina, this.porPagina)
    },

    aoFiltrar(novosFiltros) {
      this.filtros = novosFiltros
      this.pagina = 1
      this.carregar()
    },

    aoMudarPagina(novaPagina) {
      this.pagina = novaPagina
      this.carregar()
    },

    abrirCadastro() {
      this.pacienteEdicao = null
      this.$refs.modalPaciente.abrir()
    },

    editar(paciente) {
      this.pacienteEdicao = { ...paciente }
      this.$refs.modalPaciente.abrir()
    },

    visualizar(paciente) {
      this.pacienteVisualizacao = { ...paciente }
      this.$nextTick(() => {
        this.$refs.modalVisualizacao.abrir()
      })
    },

    async salvarPaciente(dados) {
      try {
        await this.salvar(dados)
        this.mostrarMensagem('Paciente salvo com sucesso', 'success')
        this.$refs.modalPaciente.fechar()
        this.carregar()
      } catch (erro) {
        const msg = erro.data?.errors?.cpf?.[0] || erro.data?.message || 'Erro ao salvar paciente'
        this.mostrarMensagem(msg, 'danger')
      }
    },

    async inativarPaciente(paciente) {
      if (!confirm(`Deseja inativar o paciente ${paciente.nome}?`)) return

      try {
        await this.inativar(paciente.id)
        this.mostrarMensagem('Paciente inativado com sucesso', 'success')
        this.carregar()
      } catch (erro) {
        this.mostrarMensagem('Erro ao inativar paciente', 'danger')
      }
    },

    mostrarMensagem(texto, tipo) {
      this.mensagem = texto
      this.tipoMensagem = tipo
      setTimeout(() => {
        this.mensagem = ''
      }, 5000)
    },
  },
}
</script>

<template>
  <div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
      <div class="mb-3 mb-lg-0">
        <h1 class="h4">Cadastro de Pacientes</h1>
        <p class="mb-0">Cadastro e gerenciamento de pacientes</p>
      </div>
      <div>
        <button class="btn btn-primary" @click="abrirCadastro">
          <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          Novo Paciente
        </button>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 mb-4">
      <div class="card border-0 shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h2 class="h5 mb-0">Pacientes</h2>
          <span class="text-muted small" v-if="meta.total !== undefined">{{ meta.total }} registros</span>
        </div>
        <div class="card-body">
          <div v-if="mensagem" :class="`alert alert-${tipoMensagem} alert-dismissible fade show`" role="alert">
            {{ mensagem }}
            <button type="button" class="btn-close" @click="mensagem = ''"></button>
          </div>

          <PacienteFilters @filter="aoFiltrar" />

          <div class="mt-4">
            <PacienteTable
              :pacientes="pacientes"
              :carregando="carregando"
              @view="visualizar"
              @edit="editar"
              @inactivate="inativarPaciente"
            />
          </div>

          <div class="d-flex justify-content-end mt-3">
            <Pagination :meta="meta" @change="aoMudarPagina" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <PacienteModal ref="modalPaciente" :paciente="pacienteEdicao" @save="salvarPaciente" />
  <PacienteViewModal ref="modalVisualizacao" :paciente="pacienteVisualizacao" />
</template>
