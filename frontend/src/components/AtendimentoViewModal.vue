<script>
import { formatarDataHora } from '@/utils/data'

export default {
  name: 'AtendimentoViewModal',
  props: {
    atendimento: {
      type: Object,
      default: null,
    },
  },
  computed: {
    dados() {
      return this.atendimento || {}
    },
  },
  methods: {
    formatarDataHora,
    abrir() {
      const modal = new window.bootstrap.Modal(this.$refs.modalElement)
      modal.show()
    },
  },
}
</script>

<template>
  <div class="modal fade" ref="modalElement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalhes do Atendimento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <dl class="row" v-if="atendimento">
            <dt class="col-sm-4">Paciente</dt>
            <dd class="col-sm-8">{{ dados.paciente?.nome || '-' }}</dd>
            <dt class="col-sm-4">Data e Hora</dt>
            <dd class="col-sm-8">{{ formatarDataHora(dados.data_hora) }}</dd>
            <dt class="col-sm-4">Descrição</dt>
            <dd class="col-sm-8" style="white-space: pre-wrap;">{{ dados.descricao }}</dd>
            <dt class="col-sm-4">Status</dt>
            <dd class="col-sm-8">
              <span class="badge" :class="dados.status === 'ativo' ? 'bg-success' : 'bg-secondary'">
                {{ dados.status === 'ativo' ? 'Ativo' : 'Inativo' }}
              </span>
            </dd>
          </dl>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
</template>
