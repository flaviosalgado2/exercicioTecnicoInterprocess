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
                'cpf',
                Rule::unique('pacientes', 'cpf')->ignore($pacienteId),
            ],
            'sexo' => ['required', 'in:M,F'],
            'cep' => ['nullable', 'string', 'size:8'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:ativo,inativo'],
        ];
    }
}
