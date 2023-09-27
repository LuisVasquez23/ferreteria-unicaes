<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;
    private static $contador = 1; // Contador para asegurar unicidad


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categorias = [
            'Agriculta y jardín',
            'Construcción',
            'Domésticos',
            'Pinturas',
            'Electrico e iluminicación',
            'Fontanería',
            'Ferreteria',
            'Herramientas',
            'Seguridad y salud ocupacional'
        ];

        // Agregar el contador al final de la categoría
        $categoria = $categorias[array_rand($categorias)] . ' ' . self::$contador;

        self::$contador++; // Incrementar el contador para la siguiente categoría

        return [
            'categoria' => $categoria,
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
