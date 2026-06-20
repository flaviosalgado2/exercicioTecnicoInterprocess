<script>
import { formatarCpf } from '@/utils/cpf'
import { formatarData } from '@/utils/data'

export default {
  name: 'PacienteViewModal',
  props: {
    paciente: {
      type: Object,
      default: null,
    },
  },
  computed: {
    dados() {
      return this.paciente || {}
    },
  },
  methods: {
    formatarCpf,
    formatarData,
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
          <h5 class="modal-title">Detalhes do Paciente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <dl class="row" v-if="paciente">
            <dt class="col-sm-4">Nome</dt>
            <dd class="col-sm-8">{{ dados.nome }}</dd>
            <dt class="col-sm-4">CPF</dt>
            <dd class="col-sm-8">{{ formatarCpf(dados.cpf) }}</dd>
            <dt class="col-sm-4">Nascimento</dt>
            <dd class="col-sm-8">{{ formatarData(dados.data_nascimento) }}</dd>
            <dt class="col-sm-4">Sexo</dt>
            <dd class="col-sm-8">{{ dados.sexo === 'M' ? 'Masculino' : 'Feminino' }}</dd>
            <dt class="col-sm-4">CEP</dt>
            <dd class="col-sm-8">{{ dados.cep }}</dd>
            <dt class="col-sm-4">Cidade</dt>
            <dd class="col-sm-8">{{ dados.cidade }}</dd>
            <dt class="col-sm-4">Endereço</dt>
            <dd class="col-sm-8">{{ dados.endereco }}</dd>
            <dt class="col-sm-4">Complemento</dt>
            <dd class="col-sm-8">{{ dados.complemento || '-' }}</dd>
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
