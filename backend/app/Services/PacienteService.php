<?php

namespace App\Services;

use App\Models\Paciente;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PacienteService
{
    public function listar(array $filtros, int $porPagina = 10): LengthAwarePaginator
    {
        $query = Paciente::query();

        if (! empty($filtros['nome'])) {
            $query->where('nome', 'ilike', '%'.$filtros['nome'].'%');
        }

        if (! empty($filtros['cpf'])) {
            $query->where('cpf', 'like', '%'.$filtros['cpf'].'%');
        }

        if (! empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        return $query->orderBy('nome')->paginate($porPagina);
    }

    public function buscar(int $id): Paciente
    {
        return Paciente::findOrFail($id);
    }

    public function criar(array $dados): Paciente
    {
        return Paciente::create($dados);
    }

    public function atualizar(Paciente $paciente, array $dados): Paciente
    {
        $paciente->update($dados);

        return $paciente->fresh();
    }

    public function inativar(Paciente $paciente): Paciente
    {
        $paciente->update(['status' => 'inativo']);

        return $paciente->fresh();
    }
}
