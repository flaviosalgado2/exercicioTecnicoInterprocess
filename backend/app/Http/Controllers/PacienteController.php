<?php

namespace App\Http\Controllers;

use App\Http\Requests\PacienteRequest;
use App\Models\Paciente;
use App\Services\PacienteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function __construct(
        private readonly PacienteService $service
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $pacientes = $this->service->listar(
            $request->only(['nome', 'cpf', 'status']),
            $request->input('per_page', 10)
        );

        return response()->json($pacientes);
    }

    public function store(PacienteRequest $request): JsonResponse
    {
        $paciente = $this->service->criar($request->validated());

        return response()->json($paciente, 201);
    }

    public function show(Paciente $paciente): JsonResponse
    {
        return response()->json($paciente);
    }

    public function update(PacienteRequest $request, Paciente $paciente): JsonResponse
    {
        $paciente = $this->service->atualizar($paciente, $request->validated());

        return response()->json($paciente);
    }

    public function destroy(Paciente $paciente): JsonResponse
    {
        $paciente = $this->service->inativar($paciente);

        return response()->json($paciente);
    }
}
