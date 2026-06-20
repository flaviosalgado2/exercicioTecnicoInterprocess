<?php

use App\Http\Controllers\AtendimentoController;
use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;

Route::get('pacientes/verificar-cpf-duplicado', [PacienteController::class, 'verificarCpfDuplicado']);
Route::get('pacientes/ativos', [PacienteController::class, 'ativos']);
Route::apiResource('pacientes', PacienteController::class);

Route::apiResource('atendimentos', AtendimentoController::class);
