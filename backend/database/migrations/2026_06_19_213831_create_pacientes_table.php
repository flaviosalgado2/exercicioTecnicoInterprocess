<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->date('data_nascimento');
            $table->string('cpf', 11)->unique();
            $table->string('sexo', 1);
            $table->string('cep', 8);
            $table->string('cidade');
            $table->string('endereco');
            $table->string('complemento')->nullable();
            $table->string('status', 10)->default('ativo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
