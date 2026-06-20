<?php

namespace Database\Factories;

use App\Models\Atendimento;
use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\Factory;

class AtendimentoFactory extends Factory
{
    protected $model = Atendimento::class;

    public function definition(): array
    {
        return [
            'paciente_id' => Paciente::factory(),
            'data_hora' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'descricao' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['ativo', 'inativo']),
        ];
    }
}
