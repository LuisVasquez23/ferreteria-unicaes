<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\MenuOption;
use Illuminate\Support\Facades\Auth;

class MenuComposer
{
    public function compose(View $view)
    {
        // Obtén el usuario autenticado
        $user = Auth::user();

        if ($user) {
            // Obtén las opciones de menú basadas en el rol del usuario
            $menuOptions = MenuOption::whereNull('parent_id')->get();

            // Comparte las opciones de menú con la vista
            $view->with('menuOptions', $menuOptions);
        }
    }
}
