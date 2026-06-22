import { createApiService } from './apiService'

const api = createApiService('/api/atendimentos')

export const { listar, buscar, criar, atualizar, inativar } = api

export const listarPacientesAtivos = (nome = '') =>
  api.request(`/api/pacientes/ativos?${api.buildQuery({ nome })}`)
