<script>
import { IMaskDirective } from 'vue-imask'
import { cpfMaskOptions, limparCpf, validarCpf } from '@/utils/cpf'
import { verificarCpfDuplicado } from '@/services/pacienteService'

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
      erros: {},
      verificandoCpf: false,
      cancelando: false,
      cpfMask: cpfMaskOptions,
    }
  },
  watch: {
    paciente: {
      immediate: true,
      handler(novo) {
        if (novo) {
          this.form = { ...novo, cpf: limparCpf(novo.cpf) }
        } else {
          this.form = this.novoForm()
        }
        this.limparErros()
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
    limparErros() {
      this.erros = {}
    },
    limparErro(campo) {
      if (this.erros[campo]) {
        delete this.erros[campo]
      }
    },
    fechar() {
      this.cancelando = false
      this.$refs.modalElement && window.bootstrap.Modal.getInstance(this.$refs.modalElement)?.hide()
    },
    abrir() {
      const modal = new window.bootstrap.Modal(this.$refs.modalElement)
      modal.show()
    },
    aoAceitarCpf(event) {
      this.form.cpf = event.detail._value
    },
    validarObrigatorios() {
      const camposObrigatorios = {
        nome: 'O Nome é obrigatório',
        data_nascimento: 'A Data de Nascimento é obrigatória',
        cpf: 'O CPF é obrigatório',
        sexo: 'O Sexo é obrigatório',
        status: 'O Status é obrigatório',
      }

      for (const [campo, mensagem] of Object.entries(camposObrigatorios)) {
        if (!this.form[campo]) {
          this.erros[campo] = mensagem
        }
      }
    },
    async validar() {
      if (this.cancelando) {
        this.cancelando = false
        return true
      }

      this.limparErros()
      this.validarObrigatorios()

      if (this.erros.cpf) {
        return false
      }

      if (!validarCpf(this.form.cpf)) {
        this.erros.cpf = 'CPF inválido'
        return false
      }

      const cpfLimpo = limparCpf(this.form.cpf)
      const cpfAtual = this.paciente ? limparCpf(this.paciente.cpf) : ''

      if (cpfLimpo === cpfAtual) {
        return Object.keys(this.erros).length === 0
      }

      this.verificandoCpf = true

      try {
        const resposta = await verificarCpfDuplicado(cpfLimpo)

        if (resposta.existe) {
          this.erros.cpf = 'CPF já cadastrado'
          return false
        }
      } catch {
        this.erros.cpf = 'Erro ao verificar CPF'
        return false
      } finally {
        this.verificandoCpf = false
      }

      return Object.keys(this.erros).length === 0
    },
    async salvar() {
      if (this.verificandoCpf) return

      const valido = await this.validar()
      if (!valido) return

      const dados = { ...this.form, cpf: limparCpf(this.form.cpf) }
      this.$emit('save', dados)
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
                <input
                  id="nome"
                  v-model="form.nome"
                  @input="limparErro('nome')"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': erros.nome }"
                  maxlength="255"
                />
                <div v-if="erros.nome" class="invalid-feedback">{{ erros.nome }}</div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input
                  id="cpf"
                  v-model="form.cpf"
                  v-imask="cpfMask"
                  @accept="aoAceitarCpf"
                  @blur="validar"
                  @input="limparErro('cpf')"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': erros.cpf }"
                  placeholder="000.000.000-00"
                  maxlength="14"
                />
                <div v-if="erros.cpf" class="invalid-feedback">{{ erros.cpf }}</div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input
                  id="data_nascimento"
                  v-model="form.data_nascimento"
                  @input="limparErro('data_nascimento')"
                  type="date"
                  class="form-control"
                  :class="{ 'is-invalid': erros.data_nascimento }"
                />
                <div v-if="erros.data_nascimento" class="invalid-feedback">{{ erros.data_nascimento }}</div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="sexo" class="form-label">Sexo</label>
                <select
                  id="sexo"
                  v-model="form.sexo"
                  @change="limparErro('sexo')"
                  class="form-select"
                  :class="{ 'is-invalid': erros.sexo }"
                >
                  <option value="">Selecione</option>
                  <option value="M">Masculino</option>
                  <option value="F">Feminino</option>
                </select>
                <div v-if="erros.sexo" class="invalid-feedback">{{ erros.sexo }}</div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="cep" class="form-label">CEP</label>
                <input id="cep" v-model="form.cep" type="text" class="form-control" maxlength="8" />
              </div>
              <div class="col-md-8 mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <input id="cidade" v-model="form.cidade" type="text" class="form-control" maxlength="255" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-8 mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input id="endereco" v-model="form.endereco" type="text" class="form-control" maxlength="255" />
              </div>
              <div class="col-md-4 mb-3">
                <label for="complemento" class="form-label">Complemento</label>
                <input id="complemento" v-model="form.complemento" type="text" class="form-control" maxlength="255" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Status</label>
                <select
                  id="status"
                  v-model="form.status"
                  @change="limparErro('status')"
                  class="form-select"
                  :class="{ 'is-invalid': erros.status }"
                >
                  <option value="ativo">Ativo</option>
                  <option value="inativo">Inativo</option>
                </select>
                <div v-if="erros.status" class="invalid-feedback">{{ erros.status }}</div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @mousedown="cancelando = true">Cancelar</button>
          <button type="button" class="btn btn-primary" @click="salvar">Salvar</button>
        </div>
      </div>
    </div>
  </div>
</template>
