<script>
import AtendimentoFilters from '@/components/AtendimentoFilters.vue'
import AtendimentoModal from '@/components/AtendimentoModal.vue'
import AtendimentoViewModal from '@/components/AtendimentoViewModal.vue'
import TabelaRegistros from '@/components/TabelaRegistros.vue'
import Paginacao from '@/components/Paginacao.vue'
import { useAtendimentosStore } from '@/stores/atendimentos'
import { formatarDataHora } from '@/utils/data'
import { mapState, mapActions } from 'pinia'

export default {
  name: 'RegistroAtendimentos',
  components: {
    AtendimentoFilters,
    AtendimentoModal,
    AtendimentoViewModal,
    TabelaRegistros,
    Paginacao,
  },
  data() {
    return {
      filtros: this.lerFiltros(this.$route.query),
      pagina: Number(this.$route.query.page) || 1,
      porPagina: Number(this.$route.query.per_page) || 10,
      atendimentoEdicao: null,
      atendimentoVisualizacao: null,
      mensagem: '',
      tipoMensagem: '',
      colunas: [
        { key: 'paciente.nome', label: 'Paciente', formatter: (_, item) => item.paciente?.nome || '-' },
        { key: 'data_hora', label: 'Data e Hora', formatter: formatarDataHora },
        { key: 'descricao', label: 'Descrição', formatter: this.descricaoResumida },
        { key: 'status', label: 'Status', badge: true },
      ],
    }
  },
  computed: {
    ...mapState(useAtendimentosStore, ['atendimentos', 'meta', 'carregando']),
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
    ...mapActions(useAtendimentosStore, ['listar', 'salvar', 'inativar']),

    descricaoResumida(descricao) {
      if (!descricao) return '-'
      const limpo = descricao.replace(/\n/g, ' ')
      return limpo.length > 60 ? limpo.substring(0, 60) + '...' : limpo
    },

    lerFiltros(query) {
      return {
        data_inicio: query.data_inicio || '',
        data_fim: query.data_fim || '',
        paciente_nome: query.paciente_nome || '',
        status: query.status || '',
      }
    },

    syncUrl() {
      const query = {}
      if (this.filtros.data_inicio) query.data_inicio = this.filtros.data_inicio
      if (this.filtros.data_fim) query.data_fim = this.filtros.data_fim
      if (this.filtros.paciente_nome) query.paciente_nome = this.filtros.paciente_nome
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
      this.atendimentoEdicao = null
      this.$refs.modalAtendimento.abrir()
    },

    editar(atendimento) {
      this.atendimentoEdicao = { ...atendimento }
      this.$refs.modalAtendimento.abrir()
    },

    visualizar(atendimento) {
      this.atendimentoVisualizacao = { ...atendimento }
      this.$refs.modalVisualizacao.abrir()
    },

    async salvarAtendimento(dados) {
      try {
        await this.salvar(dados)
        this.mostrarMensagem('Atendimento salvo com sucesso', 'success')
        this.$refs.modalAtendimento.fechar()
        this.carregar()
      } catch (erro) {
        const msg = erro.data?.message || 'Erro ao salvar atendimento'
        this.mostrarMensagem(msg, 'danger')
      }
    },

    async inativarAtendimento(atendimento) {
      const result = await window.Swal.fire({
        title: 'Inativar atendimento?',
        text: `Deseja inativar o atendimento de ${atendimento.paciente?.nome || 'paciente'}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, inativar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
      })

      if (!result.isConfirmed) return

      try {
        await this.inativar(atendimento.id)
        await window.Swal.fire({
          title: 'Sucesso!',
          text: 'Atendimento inativado com sucesso',
          icon: 'success',
          confirmButtonText: 'OK',
        })
        this.carregar()
      } catch {
        this.mostrarMensagem('Erro ao inativar atendimento', 'danger')
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
        <h1 class="h4">Registro de Atendimentos</h1>
        <p class="mb-0">Registro e gerenciamento de atendimentos</p>
      </div>
      <div>
        <button class="btn btn-primary" @click="abrirCadastro">
          <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          Novo Atendimento
        </button>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 mb-4">
      <div class="card border-0 shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h2 class="h5 mb-0">Atendimentos</h2>
          <span class="text-muted small" v-if="meta.total !== undefined">{{ meta.total }} registros</span>
        </div>
        <div class="card-body">
          <div v-if="mensagem" :class="`alert alert-${tipoMensagem} alert-dismissible fade show`" role="alert">
            {{ mensagem }}
            <button type="button" class="btn-close" @click="mensagem = ''"></button>
          </div>

          <AtendimentoFilters :filtros="filtros" @filter="aoFiltrar" />

          <div class="mt-4">
            <TabelaRegistros
              :items="atendimentos"
              :colunas="colunas"
              :carregando="carregando"
              mensagem-vazia="Nenhum atendimento encontrado"
              @view="visualizar"
              @edit="editar"
              @inactivate="inativarAtendimento"
            />
          </div>

          <div class="d-flex justify-content-end mt-3">
            <Paginacao :meta="meta" @change="aoMudarPagina" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <AtendimentoModal ref="modalAtendimento" :atendimento="atendimentoEdicao" @save="salvarAtendimento" />
  <AtendimentoViewModal ref="modalVisualizacao" :atendimento="atendimentoVisualizacao" />
</template>
