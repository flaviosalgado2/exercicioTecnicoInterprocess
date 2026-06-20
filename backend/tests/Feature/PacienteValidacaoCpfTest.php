<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PacienteValidacaoCpfTest extends TestCase
{
    use RefreshDatabase;

    public function test_cpf_invalido_e_rejeitado(): void
    {
        $response = $this->postJson('/api/pacientes', [
            'nome' => 'João da Silva',
            'data_nascimento' => '1990-01-01',
            'cpf' => '12345678901',
            'sexo' => 'M',
            'cep' => '12345678',
            'cidade' => 'São Paulo',
            'endereco' => 'Rua A',
            'complemento' => '',
            'status' => 'ativo',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['cpf']);
    }

    public function test_cpf_valido_e_aceito(): void
    {
        $response = $this->postJson('/api/pacientes', [
            'nome' => 'João da Silva',
            'data_nascimento' => '1990-01-01',
            'cpf' => '52998224725',
            'sexo' => 'M',
            'cep' => '12345678',
            'cidade' => 'São Paulo',
            'endereco' => 'Rua A',
            'complemento' => '',
            'status' => 'ativo',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('pacientes', ['cpf' => '52998224725']);
    }
}
