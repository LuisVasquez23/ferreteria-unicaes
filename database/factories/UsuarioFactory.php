<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dui' => $this->faker->numerify('########-#'), // Genera un número de DUI ficticio
            'nombres' => $this->faker->firstName,
            'apellidos' => $this->faker->lastName,
            'telefono' => $this->faker->numerify('####-####'),
            'departamento' => $this->faker->state,
            'municipio' => $this->faker->city,
            'direccion' => $this->faker->address,
            'fecha_nacimiento' => $this->faker->date('Y-m-d', '-18 years'), // Genera una fecha de nacimiento válida
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'), // Cambia esto según tus necesidades de seguridad
        ];
    }
}
