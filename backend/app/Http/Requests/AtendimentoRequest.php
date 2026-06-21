<?php

namespace App\Http\Requests;

use App\Models\Paciente;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AtendimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'paciente_id' => ['required', 'uuid', 'exists:pacientes,id'],
            'data_hora' => ['required', 'date', 'before_or_equal:now'],
            'descricao' => ['required', 'string'],
            'status' => ['required', Rule::in(['ativo', 'inativo'])],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $pacienteId = $this->input('paciente_id');

            if (! $pacienteId) {
                return;
            }

            $paciente = Paciente::find($pacienteId);

            if ($paciente && $paciente->status !== 'ativo') {
                $validator->errors()->add('paciente_id', 'O paciente deve estar ativo para receber um atendimento.');
            }
        });
    }
}
