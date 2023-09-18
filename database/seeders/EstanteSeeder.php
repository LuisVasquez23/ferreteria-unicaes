<?php

namespace Database\Seeders;

use App\Models\Estante;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Estante::factory(7)->create();
    }
}
