<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Categoria;
use App\Models\MenuOption;
use App\Models\Unidad_Medida;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(RoleSeeder::class);

        //Idea para dejarle las 3 opciones pre-cargadas al MegaAdmin

        $option = new MenuOption();

        $option->nombre = "Menu";
        $option->direccion = "/menu";
        $option->role_id = 1;
        $option->save();


        $option2 = new MenuOption();

        $option2->nombre = "Roles";
        $option2->direccion = "/roles";
        $option2->role_id = 1;
        $option2->save();


        $option3 = new MenuOption();

        $option3->nombre = "Detalle rol";
        $option3->direccion = "/detalles_roles";
        $option3->role_id = 1;

        $option3->save();



        $this->call(UsuariosSeeder::class);
        $this->call(EstanteSeeder::class);
        $this->call(Unidad_MedidaSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(CatalogosTableSeeder::class);
    }
}
