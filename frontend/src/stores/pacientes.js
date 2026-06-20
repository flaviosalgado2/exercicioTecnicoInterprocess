import { defineStore } from 'pinia'
import * as pacienteService from '@/services/pacienteService'

export const usePacientesStore = defineStore('pacientes', {
  state: () => ({
    pacientes: [],
    meta: {},
    carregando: false,
    erro: null,
  }),

  actions: {
    async listar(filtros = {}, pagina = 1, porPagina = 10) {
      this.carregando = true
      this.erro = null

      try {
        const resposta = await pacienteService.listar({
          ...filtros,
          page: pagina,
          per_page: porPagina,
        })

        this.pacientes = resposta.data
        this.meta = {
          current_page: resposta.current_page,
          last_page: resposta.last_page,
          per_page: resposta.per_page,
          total: resposta.total,
          from: resposta.from,
          to: resposta.to,
        }
      } catch (erro) {
        this.erro = erro.data?.message || 'Erro ao carregar pacientes'
        throw erro
      } finally {
        this.carregando = false
      }
    },

    async buscar(id) {
      return pacienteService.buscar(id)
    },

    async salvar(dados) {
      if (dados.id) {
        return pacienteService.atualizar(dados.id, dados)
      }

      return pacienteService.criar(dados)
    },

    async inativar(id) {
      return pacienteService.inativar(id)
    },
  },
})
