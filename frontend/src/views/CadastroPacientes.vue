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
      filtros: this.lerFiltros(this.$route.query),
      pagina: Number(this.$route.query.page) || 1,
      porPagina: Number(this.$route.query.per_page) || 10,
      pacienteEdicao: null,
      pacienteVisualizacao: null,
      mensagem: '',
      tipoMensagem: '',
    }
  },
  computed: {
    ...mapState(usePacientesStore, ['pacientes', 'meta', 'carregando']),
  },
  watch: {
    '$route.query'(query) {
      this.filtros = this.lerFiltros(query)
      this.pagina = Number(query.page) || 1
      this.porPagina = Number(query.per_page) || 10
      this.carregar()
    },
  },
  mounted() {
    this.carregar()
  },
  methods: {
    ...mapActions(usePacientesStore, ['listar', 'salvar', 'inativar']),

    lerFiltros(query) {
      return {
        nome: query.nome || '',
        cpf: query.cpf || '',
        status: query.status || '',
      }
    },

    syncUrl() {
      const query = {}
      if (this.filtros.nome) query.nome = this.filtros.nome
      if (this.filtros.cpf) query.cpf = this.filtros.cpf
      if (this.filtros.status) query.status = this.filtros.status
      if (this.pagina !== 1) query.page = String(this.pagina)
      if (this.porPagina !== 10) query.per_page = String(this.porPagina)
      this.$router.replace({ query })
    },

    carregar() {
      this.listar(this.filtros, this.pagina, this.porPagina)
    },

    aoFiltrar(novosFiltros) {
      this.filtros = novosFiltros
      this.pagina = 1
      this.syncUrl()
    },

    aoMudarPagina(novaPagina) {
      this.pagina = novaPagina
      this.syncUrl()
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
      this.$refs.modalVisualizacao.abrir()
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
      const result = await window.Swal.fire({
        title: 'Inativar paciente?',
        text: `Deseja inativar o paciente ${paciente.nome}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, inativar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
      })

      if (!result.isConfirmed) return

      try {
        await this.inativar(paciente.id)
        await window.Swal.fire({
          title: 'Sucesso!',
          text: 'Paciente inativado com sucesso',
          icon: 'success',
          confirmButtonText: 'OK',
        })
        this.carregar()
      } catch {
        this.mostrarMensagem('Erro ao inativar paciente', 'danger')
      }
    },

    mostrarMensagem(texto, tipo) {
      this.mensagem = texto
      this.tipoMensagem = tipo
      setTimeout(() => (this.mensagem = ''), 5000)
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

          <PacienteFilters :filtros="filtros" @filter="aoFiltrar" />

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
