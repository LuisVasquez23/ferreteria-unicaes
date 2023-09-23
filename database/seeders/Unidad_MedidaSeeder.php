<?php

namespace Database\Seeders;

use App\Models\Unidad_Medida;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Unidad_MedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unidad_Medida::factory(8)->create();
    }
}
