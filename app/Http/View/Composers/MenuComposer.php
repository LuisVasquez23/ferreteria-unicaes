<?php

namespace App\Http\View\Composers;

use App\Models\DetalleRole;
use Illuminate\View\View;
use App\Models\MenuOption;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class MenuComposer
{
    public function compose(View $view)
    {
        // Obtén el usuario autenticado
        $user = Auth::user();

        if ($user) {
            // Obtén el ID del usuario
            $userId = Auth::user()->usuario_id;

            // Obtén todos los roles del usuario
            $todosRoles = DetalleRole::where('usuario_id', $userId)->get();

            // Crea un arreglo de IDs de roles
            $rolesIds = $todosRoles->pluck('role_id')->toArray();

            // Obtén las opciones de menú basadas en los roles del usuario
            $menuOptions = MenuOption::whereIn('role_id', $rolesIds)
                ->whereNull('parent_id')
                ->get();

            // Comparte las opciones de menú con la vista
            $view->with('menuOptions', $menuOptions);
        }
    }
}
