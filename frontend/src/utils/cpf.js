export function formatarCpf(cpf) {
  if (!cpf) return ''

  const digitos = cpf.replace(/\D/g, '').slice(0, 11)

  return digitos
    .replace(/(\d{3})(\d)/, '$1.$2')
    .replace(/(\d{3})(\d)/, '$1.$2')
    .replace(/(\d{3})(\d{1,2})$/, '$1-$2')
}

export function limparCpf(cpf) {
  return cpf ? cpf.replace(/\D/g, '') : ''
}

export function validarCpf(cpf) {
  const digitos = limparCpf(cpf)

  if (digitos.length !== 11 || /^(\d)\1{10}$/.test(digitos)) {
    return false
  }

  let soma = 0

  for (let i = 0; i < 9; i++) {
    soma += parseInt(digitos.charAt(i)) * (10 - i)
  }

  let resto = (soma * 10) % 11

  if (resto === 10 || resto === 11) resto = 0
  if (resto !== parseInt(digitos.charAt(9))) return false

  soma = 0

  for (let i = 0; i < 10; i++) {
    soma += parseInt(digitos.charAt(i)) * (11 - i)
  }

  resto = (soma * 10) % 11

  if (resto === 10 || resto === 11) resto = 0

  return resto === parseInt(digitos.charAt(10))
}
