<script>
import TomSelect from 'tom-select'
import 'tom-select/dist/css/tom-select.bootstrap5.css'
import { dataHoraNaoEhFutura } from '@/utils/data'
import { listarPacientesAtivos } from '@/services/atendimentoService'

export default {
  name: 'AtendimentoModal',
  props: {
    atendimento: {
      type: Object,
      default: null,
    },
  },
  emits: ['save'],
  data() {
    return {
      form: this.novoForm(),
      erros: {},
      cancelando: false,
      pacientes: [],
      carregandoPacientes: false,
      tomSelectInstance: null,
    }
  },
  watch: {
    atendimento: {
      immediate: true,
      handler(novo) {
        if (novo) {
          this.form = {
            id: novo.id,
            paciente_id: novo.paciente_id,
            data_hora: this.formatarParaInput(novo.data_hora),
            descricao: novo.descricao,
            status: novo.status,
          }
        } else {
          this.form = this.novoForm()
        }
        this.limparErros()
        this.$nextTick(() => this.restaurarSelecaoPaciente())
      },
    },
  },
  computed: {
    titulo() {
      return this.atendimento ? 'Editar Atendimento' : 'Novo Atendimento'
    },
  },
  mounted() {
    this.inicializarTomSelect()
  },
  beforeUnmount() {
    if (this.tomSelectInstance) {
      this.tomSelectInstance.destroy()
      this.tomSelectInstance = null
    }
  },
  methods: {
    novoForm() {
      return {
        paciente_id: '',
        data_hora: '',
        descricao: '',
        status: 'ativo',
      }
    },
    formatarParaInput(dataHora) {
      if (!dataHora) return ''
      const [data, hora] = dataHora.split('T')
      return `${data}T${hora.substring(0, 5)}`
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
    async carregarPacientes(nome = '') {
      this.carregandoPacientes = true
      try {
        const resposta = await listarPacientesAtivos(nome)
        this.pacientes = resposta
        this.$nextTick(() => this.restaurarSelecaoPaciente())
      } finally {
        this.carregandoPacientes = false
      }
    },
    inicializarTomSelect() {
      if (this.tomSelectInstance) {
        this.tomSelectInstance.destroy()
      }

      this.tomSelectInstance = new TomSelect(this.$refs.selectPaciente, {
        valueField: 'id',
        labelField: 'nome',
        searchField: 'nome',
        maxItems: 1,
        placeholder: 'Selecione um paciente',
        load: (query, callback) => {
          this.carregarPacientes(query).then(() => callback(this.pacientes))
        },
        onChange: (value) => {
          this.form.paciente_id = value
          this.limparErro('paciente_id')
        },
      })

      this.carregarPacientes()
    },
    restaurarSelecaoPaciente() {
      if (!this.tomSelectInstance) return

      const pacienteId = this.form.paciente_id
      if (!pacienteId) {
        this.tomSelectInstance.clear()
        return
      }

      const jaCarregado = this.tomSelectInstance.options[pacienteId]
      if (jaCarregado) {
        this.tomSelectInstance.setValue(pacienteId, true)
        return
      }

      if (this.atendimento && this.atendimento.paciente) {
        this.tomSelectInstance.addOption(this.atendimento.paciente)
        this.tomSelectInstance.setValue(pacienteId, true)
      }
    },
    validarObrigatorios() {
      const camposObrigatorios = {
        paciente_id: 'O Paciente é obrigatório',
        data_hora: 'A Data e Hora são obrigatórias',
        descricao: 'A Descrição é obrigatória',
        status: 'O Status é obrigatório',
      }

      for (const [campo, mensagem] of Object.entries(camposObrigatorios)) {
        if (!this.form[campo]) {
          this.erros[campo] = mensagem
        }
      }
    },
    validar() {
      if (this.cancelando) {
        this.cancelando = false
        return true
      }

      this.limparErros()
      this.validarObrigatorios()

      if (this.form.data_hora && !dataHoraNaoEhFutura(this.form.data_hora)) {
        this.erros.data_hora = 'A Data e Hora não podem estar no futuro'
      }

      return Object.keys(this.erros).length === 0
    },
    salvar() {
      const valido = this.validar()
      if (!valido) return

      const dados = { ...this.form }
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
                <label for="paciente" class="form-label">Paciente</label>
                <select
                  id="paciente"
                  ref="selectPaciente"
                  class="form-select"
                  :class="{ 'is-invalid': erros.paciente_id }"
                >
                  <option value="">Selecione um paciente</option>
                </select>
                <div v-if="erros.paciente_id" class="invalid-feedback">{{ erros.paciente_id }}</div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="data_hora" class="form-label">Data e Hora</label>
                <input
                  id="data_hora"
                  v-model="form.data_hora"
                  @input="limparErro('data_hora')"
                  type="datetime-local"
                  class="form-control"
                  :class="{ 'is-invalid': erros.data_hora }"
                />
                <div v-if="erros.data_hora" class="invalid-feedback">{{ erros.data_hora }}</div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 mb-3">
                <label for="descricao" class="form-label">Descrição do atendimento</label>
                <textarea
                  id="descricao"
                  v-model="form.descricao"
                  @input="limparErro('descricao')"
                  class="form-control"
                  :class="{ 'is-invalid': erros.descricao }"
                  rows="5"
                ></textarea>
                <div v-if="erros.descricao" class="invalid-feedback">{{ erros.descricao }}</div>
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
