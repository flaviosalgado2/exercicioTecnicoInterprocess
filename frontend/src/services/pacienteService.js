const API_URL = '/api/pacientes'

async function request(url, options = {}) {
  const response = await fetch(url, {
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
    },
    ...options,
  })

  const data = await response.json().catch(() => null)

  if (!response.ok) {
    const error = new Error(data?.message || 'Erro na requisição')
    error.response = response
    error.data = data
    throw error
  }

  return data
}

function buildQuery(params) {
  const query = new URLSearchParams()

  Object.entries(params).forEach(([key, value]) => {
    if (value !== undefined && value !== null && value !== '') {
      query.append(key, value)
    }
  })

  return query.toString()
}

export function listar(params) {
  return request(`${API_URL}?${buildQuery(params)}`)
}

export function buscar(id) {
  return request(`${API_URL}/${id}`)
}

export function criar(dados) {
  return request(API_URL, {
    method: 'POST',
    body: JSON.stringify(dados),
  })
}

export function atualizar(id, dados) {
  return request(`${API_URL}/${id}`, {
    method: 'PUT',
    body: JSON.stringify(dados),
  })
}

export function inativar(id) {
  return request(`${API_URL}/${id}`, {
    method: 'DELETE',
  })
}
