<?php

namespace Database\Factories;

use App\Models\Estante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Estante>
 */
class EstanteFactory extends Factory
{
    protected $model = Estante::class;

    public function definition(): array
    {

        $estantes = ['Herramientas', 'Ferretería General', 'Tornillería', 'Pinturas', 'Electricidad', 'Fontanería', 'Jardinería'];
        
        return [

        'estante' => $this->faker->unique()->randomElement($estantes),
        'ubicacion' => '',
        'descripcion' => '',
        'creado_por' => null,
        'fecha_creacion' => null,
        'actualizado_por' => null,
        'fecha_actualizacion' => null,
        'bloqueado_por' => null,
        'fecha_bloqueo' => null,
        ];
    }
}
