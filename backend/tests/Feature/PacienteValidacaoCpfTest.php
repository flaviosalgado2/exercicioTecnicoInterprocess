<?php

namespace Tests\Feature;

use App\Models\Paciente;
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

    public function test_cpf_duplicado_e_rejeitado(): void
    {
        Paciente::factory()->create(['cpf' => '52998224725']);

        $response = $this->postJson('/api/pacientes', [
            'nome' => 'Outro João',
            'data_nascimento' => '1991-02-02',
            'cpf' => '52998224725',
            'sexo' => 'M',
            'cep' => '87654321',
            'cidade' => 'Rio de Janeiro',
            'endereco' => 'Rua B',
            'complemento' => '',
            'status' => 'ativo',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['cpf']);
    }

    public function test_endpoint_verificar_cpf_retorna_true_quando_existe(): void
    {
        Paciente::factory()->create(['cpf' => '52998224725']);

        $response = $this->getJson('/api/pacientes/verificar-cpf-duplicado?cpf=52998224725');

        $response->assertOk()
            ->assertJson(['existe' => true]);
    }

    public function test_endpoint_verificar_cpf_retorna_false_quando_nao_existe(): void
    {
        $response = $this->getJson('/api/pacientes/verificar-cpf-duplicado?cpf=52998224725');

        $response->assertOk()
            ->assertJson(['existe' => false]);
    }
}
