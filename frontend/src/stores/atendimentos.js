import { defineStore } from 'pinia'
import * as atendimentoService from '@/services/atendimentoService'

export const useAtendimentosStore = defineStore('atendimentos', {
  state: () => ({
    atendimentos: [],
    meta: {},
    carregando: false,
    erro: null,
  }),

  actions: {
    async listar(filtros = {}, pagina = 1, porPagina = 10) {
      this.carregando = true
      this.erro = null

      try {
        const resposta = await atendimentoService.listar({
          ...filtros,
          page: pagina,
          per_page: porPagina,
        })

        this.atendimentos = resposta.data
        this.meta = {
          current_page: resposta.current_page,
          last_page: resposta.last_page,
          per_page: resposta.per_page,
          total: resposta.total,
          from: resposta.from,
          to: resposta.to,
        }
      } catch (erro) {
        this.erro = erro.data?.message || 'Erro ao carregar atendimentos'
        throw erro
      } finally {
        this.carregando = false
      }
    },

    async buscar(id) {
      return atendimentoService.buscar(id)
    },

    async salvar(dados) {
      if (dados.id) {
        return atendimentoService.atualizar(dados.id, dados)
      }

      return atendimentoService.criar(dados)
    },

    async inativar(id) {
      return atendimentoService.inativar(id)
    },

    async listarPacientesAtivos(nome = '') {
      return atendimentoService.listarPacientesAtivos(nome)
    },
  },
})
