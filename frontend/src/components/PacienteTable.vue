<script>
import { formatarCpf } from '@/utils/cpf'
import { formatarData } from '@/utils/data'

export default {
  name: 'PacienteTable',
  props: {
    pacientes: {
      type: Array,
      default: () => [],
    },
    carregando: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['view', 'edit', 'inactivate'],
  methods: {
    formatarCpf,
    formatarData,
  },
}
</script>

<template>
  <div class="table-responsive">
    <table class="table table-centered table-nowrap mb-0 rounded">
      <thead class="thead-light">
        <tr>
          <th class="border-0 rounded-start">Nome</th>
          <th class="border-0">CPF</th>
          <th class="border-0">Nascimento</th>
          <th class="border-0">Sexo</th>
          <th class="border-0">Cidade</th>
          <th class="border-0">Status</th>
          <th class="border-0 rounded-end text-center">Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="carregando">
          <td colspan="7" class="text-center py-4">Carregando...</td>
        </tr>
        <tr v-else-if="!pacientes.length">
          <td colspan="7" class="text-center py-4">Nenhum paciente encontrado</td>
        </tr>
        <tr v-for="paciente in pacientes" :key="paciente.id">
          <td>{{ paciente.nome }}</td>
          <td>{{ formatarCpf(paciente.cpf) }}</td>
          <td>{{ formatarData(paciente.data_nascimento) }}</td>
          <td>{{ paciente.sexo === 'M' ? 'Masculino' : 'Feminino' }}</td>
          <td>{{ paciente.cidade }}</td>
          <td>
            <span class="badge" :class="paciente.status === 'ativo' ? 'bg-success' : 'bg-secondary'">
              {{ paciente.status === 'ativo' ? 'Ativo' : 'Inativo' }}
            </span>
          </td>
          <td class="text-center">
            <button class="btn btn-link text-dark p-1" @click="$emit('view', paciente)" title="Visualizar">
              <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </button>
            <button class="btn btn-link text-dark p-1" @click="$emit('edit', paciente)" title="Editar">
              <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            </button>
            <button class="btn btn-link text-danger p-1" @click="$emit('inactivate', paciente)" title="Inativar" :disabled="paciente.status === 'inativo'">
              <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
