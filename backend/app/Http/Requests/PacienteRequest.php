<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pacienteId = $this->route('paciente');

        return [
            'nome' => ['required', 'string', 'max:255'],
            'data_nascimento' => ['required', 'date', 'before_or_equal:today'],
            'cpf' => [
                'required',
                'string',
                'size:11',
                $this->validarCpf(),
                Rule::unique('pacientes', 'cpf')->ignore($pacienteId),
            ],
            'sexo' => ['required', 'in:M,F'],
            'cep' => ['required', 'string', 'size:8'],
            'cidade' => ['required', 'string', 'max:255'],
            'endereco' => ['required', 'string', 'max:255'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:ativo,inativo'],
        ];
    }

    private function validarCpf(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail): void {
            if (! $this->cpfValido($value)) {
                $fail('O CPF informado é inválido.');
            }
        };
    }

    private function cpfValido(string $cpf): bool
    {
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($posicao = 9; $posicao < 11; $posicao++) {
            $soma = 0;

            for ($indice = 0; $indice < $posicao; $indice++) {
                $soma += (int) $cpf[$indice] * (($posicao + 1) - $indice);
            }

            $digito = ((10 * $soma) % 11) % 10;

            if ($digito !== (int) $cpf[$posicao]) {
                return false;
            }
        }

        return true;
    }
}
