<?php

use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;

Route::apiResource('pacientes', PacienteController::class);
