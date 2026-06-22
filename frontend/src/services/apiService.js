const DEFAULT_HEADERS = {
  'Content-Type': 'application/json',
  Accept: 'application/json',
}

const request = async (url, options = {}) => {
  const response = await fetch(url, { headers: DEFAULT_HEADERS, ...options })
  const data = await response.json().catch(() => null)

  if (!response.ok) {
    const error = new Error(data?.message || 'Erro na requisição')
    error.response = response
    error.data = data
    throw error
  }

  return data
}

const buildQuery = (params) =>
  new URLSearchParams(
    Object.entries(params).filter(([, value]) => value !== undefined && value !== null && value !== '')
  ).toString()

export const createApiService = (baseUrl) => ({
  listar: (params) => request(`${baseUrl}?${buildQuery(params)}`),
  buscar: (id) => request(`${baseUrl}/${id}`),
  criar: (dados) => request(baseUrl, { method: 'POST', body: JSON.stringify(dados) }),
  atualizar: (id, dados) => request(`${baseUrl}/${id}`, { method: 'PUT', body: JSON.stringify(dados) }),
  inativar: (id) => request(`${baseUrl}/${id}`, { method: 'DELETE' }),
  request,
  buildQuery,
})
