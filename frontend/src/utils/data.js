export function formatarData(data) {
  if (!data) return ''

  const partes = data.split('T')[0].split('-')

  return `${partes[2]}/${partes[1]}/${partes[0]}`
}
