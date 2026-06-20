export function formatarData(data) {
  if (!data) return ''

  const partes = data.split('T')[0].split('-')

  return `${partes[2]}/${partes[1]}/${partes[0]}`
}

export function formatarDataHora(dataHora) {
  if (!dataHora) return ''

  const [data, hora] = dataHora.split('T')
  const [ano, mes, dia] = data.split('-')
  const horaFormatada = hora ? hora.substring(0, 5) : ''

  return `${dia}/${mes}/${ano} ${horaFormatada}`.trim()
}

export function dataHoraNaoEhFutura(dataHora) {
  if (!dataHora) return false

  const data = new Date(dataHora)
  const agora = new Date()

  return data <= agora
}
