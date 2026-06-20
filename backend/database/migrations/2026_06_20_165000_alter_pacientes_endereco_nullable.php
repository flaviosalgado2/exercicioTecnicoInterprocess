<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('cep', 8)->nullable()->change();
            $table->string('cidade')->nullable()->change();
            $table->string('endereco')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('cep', 8)->nullable(false)->change();
            $table->string('cidade')->nullable(false)->change();
            $table->string('endereco')->nullable(false)->change();
        });
    }
};
