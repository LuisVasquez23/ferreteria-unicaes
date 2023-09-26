<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unidad_Medida>
 */
class Unidad_MedidaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unidadesComunes = ['libras', 'kilogramos', 'centimetros', 'metros', 'pulgadas', 'litros', 'galones', 'unidad'];
        $unidadNombre = $this->faker->unique()->randomElement($unidadesComunes);

        return [
            'nombre' => $unidadNombre,
            'descripcion' => null,
        ];
    }
}
