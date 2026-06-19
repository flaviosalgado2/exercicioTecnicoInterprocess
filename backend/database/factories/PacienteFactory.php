<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PacienteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'data_nascimento' => fake()->date(),
            'cpf' => $this->cpf(),
            'sexo' => fake()->randomElement(['M', 'F']),
            'cep' => fake()->numerify('########'),
            'cidade' => fake()->city(),
            'endereco' => fake()->streetAddress(),
            'complemento' => fake()->optional()->secondaryAddress(),
            'status' => fake()->randomElement(['ativo', 'inativo']),
        ];
    }

    private function cpf(): string
    {
        $digitos = [];

        for ($i = 0; $i < 9; $i++) {
            $digitos[] = fake()->randomDigit();
        }

        $digitos[] = $this->digitoVerificador($digitos, 10);
        $digitos[] = $this->digitoVerificador($digitos, 11);

        return implode('', $digitos);
    }

    private function digitoVerificador(array $digitos, int $pesoInicial): int
    {
        $soma = 0;

        foreach ($digitos as $indice => $digito) {
            $soma += $digito * ($pesoInicial - $indice);
        }

        $resto = ($soma * 10) % 11;

        return $resto === 10 ? 0 : $resto;
    }
}
