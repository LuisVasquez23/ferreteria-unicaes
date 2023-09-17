<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $roles = ['MegaAdmin', 'Admin', 'Empleado', 'Cliente', 'Proveedor'];

        return [
            'role' => $this->faker->unique()->randomElement($roles),
            'descripcion' => $this->faker->sentence,
            'creado_por' => $this->faker->unique()->name,
            'fecha_creacion' => $this->faker->unique()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
