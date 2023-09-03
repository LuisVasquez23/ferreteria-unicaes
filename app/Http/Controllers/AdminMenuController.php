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
            'role_id' => 'required', 'not_in:""',
        ];

        $messages = [
            'nombreOpcion.required' => 'El campo "Nombre" es obligatorio.',
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
        $menu->direccion = $request->input('direccion') ?? "#";
        $menu->parent_id = $request->input('parent_id');
        $menu->role_id = $request->input('role_id');
        $menu->save();

        // Redirige a una página de éxito o donde desees después de guardar
        return redirect()->route('menu')->with('success', 'El registro se ha agregado con éxito.');
    }

    public function edit($id)
    {
        // Recupera el registro que deseas editar por su ID
        $menuOption = MenuOption::find($id);
        $roles = Role::all();
        $menusOption = MenuOption::all();

        // Verifica si el registro existe
        if (!$menuOption) {
            return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
        }

        // Puedes cargar los datos en una vista de edición
        return view('menu.edit', compact('menuOption', 'menusOption', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // Validación similar a la del método 'store'

        $menuOption = MenuOption::find($id);

        if (!$menuOption) {
            return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
        }

        // Actualiza los datos del registro con los nuevos valores del formulario
        $menuOption->nombre = $request->input('nombreOpcion');
        $menuOption->direccion = $request->input('direccion');
        $menuOption->parent_id = $request->input('parent_id');
        $menuOption->role_id = $request->input('role_id');
        $menuOption->save();

        // Redirige a una página de éxito o donde desees después de actualizar
        return redirect()->route('menu')->with('success', 'El registro se ha actualizado con éxito.');
    }

    public function destroy($id)
    {
        $menuOption = MenuOption::find($id);

        if (!$menuOption) {
            return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
        }

        // Elimina el registro de la base de datos
        $menuOption->delete();

        // Redirige a una página de éxito o donde desees después de eliminar
        return redirect()->route('menu')->with('success', 'El registro se ha eliminado con éxito.');
    }
}
