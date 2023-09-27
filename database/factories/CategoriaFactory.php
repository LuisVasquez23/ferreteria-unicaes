<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categorias = ['Agriculta y jardín', 'Construcción', 'Domésticos', 'Pinturas', 'Electrico e iluminicación', 'Fontanería', 'Ferreteria','Herramientas','Seguridad y salud ocupacional'];

        return [
            'categoria' => $this->faker->unique()->randomElement($categorias),
            'descripcion' => '',
            'creado_por' => '',
            'fecha_creacion' => null,
            'actualizado_por' => null,
            'fecha_actualizacion' => null,
            'bloqueado_por' => null,
            'fecha_bloqueo' => null,
        ];
    }
}
