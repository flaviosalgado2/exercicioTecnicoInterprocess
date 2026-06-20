<?php

namespace App\Services;

use App\Models\Atendimento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AtendimentoService
{
    public function listar(array $filtros, int $porPagina = 10): LengthAwarePaginator
    {
        $query = Atendimento::with('paciente');

        if (! empty($filtros['paciente_id'])) {
            $query->where('paciente_id', $filtros['paciente_id']);
        }

        if (! empty($filtros['paciente_nome'])) {
            $query->whereHas('paciente', function ($q) use ($filtros) {
                $q->where('nome', 'ilike', '%'.$filtros['paciente_nome'].'%');
            });
        }

        if (! empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        if (! empty($filtros['data_inicio'])) {
            $query->whereDate('data_hora', '>=', $filtros['data_inicio']);
        }

        if (! empty($filtros['data_fim'])) {
            $query->whereDate('data_hora', '<=', $filtros['data_fim']);
        }

        return $query->orderByDesc('data_hora')->paginate($porPagina);
    }

    public function buscar(int $id): Atendimento
    {
        return Atendimento::with('paciente')->findOrFail($id);
    }

    public function criar(array $dados): Atendimento
    {
        return Atendimento::create($dados);
    }

    public function atualizar(Atendimento $atendimento, array $dados): Atendimento
    {
        $atendimento->update($dados);

        return $atendimento->fresh(['paciente']);
    }

    public function inativar(Atendimento $atendimento): Atendimento
    {
        $atendimento->update(['status' => 'inativo']);

        return $atendimento->fresh(['paciente']);
    }
}
