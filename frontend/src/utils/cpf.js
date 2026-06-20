import { isValid, format, strip } from '@fnando/cpf'

export const cpfMaskOptions = { mask: '000.000.000-00', lazy: true }

export function formatarCpf(cpf) {
  if (!cpf) return ''
  return format(cpf, { pad: false })
}

export function limparCpf(cpf) {
  return cpf ? strip(cpf) : ''
}

export function validarCpf(cpf) {
  return isValid(cpf)
}
