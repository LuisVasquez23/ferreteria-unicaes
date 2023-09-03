<?php

namespace App\Http\Controllers;

use App\Models\MenuOption;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminMenuController extends Controller
{

    public function index()
    {

        $opcionesMenu = MenuOption::all();

        return view('menu.index', compact('opcionesMenu'));
    }

    public function create()
    {

        $roles = Role::all();
        $menusOption = MenuOption::all();

        return view('menu.create', compact('roles', 'menusOption'));
    }

    public function store(Request $request)
    {

        // Define las reglas de validación
        $rules = [
            'nombreOpcion' => 'required',
            'direccion' => 'required',
            'role_id' => 'required', 'not_in:""',
        ];

        $messages = [
            'nombreOpcion.required' => 'El campo "Nombre" es obligatorio.',
            'direccion.required' => 'El campo "Direccion" es obligatorio.',
            'role_id.required' => 'Debes seleccionar un Rol en la lista desplegable.',
        ];

        // Valida los datos del formulario
        $validator = Validator::make($request->all(), $rules, $messages);

        // Si la validación falla, redirige de vuelta con los errores
        if ($validator->fails()) {
            return redirect()
                ->route('menu.create') // Reemplaza 'menu.create' con la ruta de tu formulario
                ->withErrors($validator)
                ->withInput();
        }

        // Si la validación pasa, guarda los datos y realiza otras acciones según sea necesario
        $menu = new MenuOption();
        $menu->nombre = $request->input('nombreOpcion');
        $menu->direccion = $request->input('direccion');
        $menu->parent_id = $request->input('parent_id');
        $menu->role_id = $request->input('role_id');
        $menu->save();

        // Redirige a una página de éxito o donde desees después de guardar
        return redirect()->route('menu'); // Reemplaza 'menu.index'
    }
}
