<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogosTableSeeder extends Seeder
{
    public function run()
    {
        // Inserta el primer registro
        DB::table('catalogos')->insert([
            'nombre' => 'NOMBRE_EMPRESA',
            'valor' => 'FERRETERIA',
            'descripcion' => 'ESTE ES LA VARIABLE DEL SISTEMA QUE SIRVE PARA ASIGNAR EL NOMBRE DE LA EMPRESA',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Inserta el segundo registro
        DB::table('catalogos')->insert([
            'nombre' => 'LOGO_EMPRESA',
            'valor' => 'logo.jpg',
            'descripcion' => 'Esta es la direccion del logo de la empresa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
