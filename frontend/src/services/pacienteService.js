import { createApiService } from './apiService'

const API_URL = '/api/pacientes'
const api = createApiService(API_URL)

export const { listar, buscar, criar, atualizar, inativar } = api

export const verificarCpfDuplicado = (cpf) =>
  api.request(`${API_URL}/verificar-cpf-duplicado?${api.buildQuery({ cpf })}`)
