<?php

use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;

Route::get('pacientes/verificar-cpf-duplicado', [PacienteController::class, 'verificarCpfDuplicado']);
Route::apiResource('pacientes', PacienteController::class);
