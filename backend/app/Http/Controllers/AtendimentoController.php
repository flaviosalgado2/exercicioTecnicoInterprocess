<?php

namespace App\Http\Controllers;

use App\Http\Requests\AtendimentoRequest;
use App\Models\Atendimento;
use App\Services\AtendimentoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AtendimentoController extends Controller
{
    public function __construct(
        private readonly AtendimentoService $service
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $atendimentos = $this->service->listar(
            $request->only(['paciente_id', 'paciente_nome', 'status', 'data_inicio', 'data_fim']),
            $request->input('per_page', 10)
        );

        return response()->json($atendimentos);
    }

    public function store(AtendimentoRequest $request): JsonResponse
    {
        $atendimento = $this->service->criar($request->validated());

        return response()->json($atendimento, 201);
    }

    public function show(Atendimento $atendimento): JsonResponse
    {
        return response()->json($atendimento->load('paciente'));
    }

    public function update(AtendimentoRequest $request, Atendimento $atendimento): JsonResponse
    {
        $atendimento = $this->service->atualizar($atendimento, $request->validated());

        return response()->json($atendimento);
    }

    public function destroy(Atendimento $atendimento): JsonResponse
    {
        $atendimento = $this->service->inativar($atendimento);

        return response()->json($atendimento);
    }
}
