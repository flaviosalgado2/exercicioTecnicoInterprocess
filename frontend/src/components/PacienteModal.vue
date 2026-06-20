<script>
import { IMaskDirective } from 'vue-imask'
import { cpfMaskOptions, formatarCpf, limparCpf, validarCpf } from '@/utils/cpf'

export default {
  name: 'PacienteModal',
  directives: {
    imask: IMaskDirective,
  },
  props: {
    paciente: {
      type: Object,
      default: null,
    },
  },
  emits: ['save'],
  data() {
    return {
      form: this.novoForm(),
      erroCpf: '',
      cpfMascarado: '',
      cpfMask: cpfMaskOptions,
    }
  },
  watch: {
    paciente: {
      immediate: true,
      handler(novo) {
        if (novo) {
          this.form = { ...novo }
        } else {
          this.form = this.novoForm()
        }
        this.cpfMascarado = formatarCpf(this.form.cpf)
        this.erroCpf = ''
      },
    },
  },
  computed: {
    titulo() {
      return this.paciente ? 'Editar Paciente' : 'Novo Paciente'
    },
  },
  methods: {
    novoForm() {
      return {
        nome: '',
        data_nascimento: '',
        cpf: '',
        sexo: '',
        cep: '',
        cidade: '',
        endereco: '',
        complemento: '',
        status: 'ativo',
      }
    },
    fechar() {
      this.$refs.modalElement && window.bootstrap.Modal.getInstance(this.$refs.modalElement)?.hide()
    },
    abrir() {
      const modal = new window.bootstrap.Modal(this.$refs.modalElement)
      modal.show()
    },
    aoAceitarCpf(event) {
      this.form.cpf = limparCpf(event.detail._value)
    },
    validar() {
      this.erroCpf = ''

      if (!this.form.cpf) {
        this.erroCpf = 'O CPF é obrigatório'
        return false
      }

      if (!validarCpf(this.form.cpf)) {
        this.erroCpf = 'CPF inválido'
        return false
      }

      return true
    },
    salvar() {
      if (!this.validar()) return

      this.$emit('save', { ...this.form })
    },
  },
}
</script>

<template>
  <div class="modal fade" ref="modalElement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ titulo }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="salvar">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input id="nome" v-model="form.nome" type="text" class="form-control" maxlength="255" required />
              </div>
              <div class="col-md-6 mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input
                  id="cpf"
                  v-model="cpfMascarado"
                  v-imask="cpfMask"
                  @accept="aoAceitarCpf"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': erroCpf }"
                  placeholder="000.000.000-00"
                  maxlength="14"
                  required
                />
                <div v-if="erroCpf" class="invalid-feedback">{{ erroCpf }}</div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input id="data_nascimento" v-model="form.data_nascimento" type="date" class="form-control" required />
              </div>
              <div class="col-md-6 mb-3">
                <label for="sexo" class="form-label">Sexo</label>
                <select id="sexo" v-model="form.sexo" class="form-select" required>
                  <option value="">Selecione</option>
                  <option value="M">Masculino</option>
                  <option value="F">Feminino</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="cep" class="form-label">CEP</label>
                <input id="cep" v-model="form.cep" type="text" class="form-control" maxlength="8" required />
              </div>
              <div class="col-md-8 mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <input id="cidade" v-model="form.cidade" type="text" class="form-control" maxlength="255" required />
              </div>
            </div>
            <div class="row">
              <div class="col-md-8 mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input id="endereco" v-model="form.endereco" type="text" class="form-control" maxlength="255" required />
              </div>
              <div class="col-md-4 mb-3">
                <label for="complemento" class="form-label">Complemento</label>
                <input id="complemento" v-model="form.complemento" type="text" class="form-control" maxlength="255" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" v-model="form.status" class="form-select" required>
                  <option value="ativo">Ativo</option>
                  <option value="inativo">Inativo</option>
                </select>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" @click="salvar">Salvar</button>
        </div>
      </div>
    </div>
  </div>
</template>
