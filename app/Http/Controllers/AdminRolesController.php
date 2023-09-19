<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Doctrine\DBAL\Schema\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminRolesController extends Controller
{
    public function index(Request $request)
    {

        try {


            $filtro = $request->input('filtro', 'no-bloqueados');

            if ($filtro === 'bloqueados') {
                $roles = Role::whereNotNull('bloqueado_por')->get();
            } else {
                $roles = Role::whereNull('bloqueado_por')->get();
            }

            return view('roles.index', compact('roles', 'filtro'));
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return redirect()->route('roles')->with('error', 'Error al cargar la página de roles');
        }
    }


    public function create()
    {
        try {
            return view('roles.create');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('roles')->with('error', 'Error al cargar la página de creacion de roles');
        }
    }



    public function store(Request $request)
    {

        try {


        // Define las reglas de validación
        $rules = [

            'nombreOpcion' => 'required|unique:roles,role',
            'descripcion_opcion' => 'required',
        ];

        $messages = [

            'nombreOpcion.required' => 'El campo "rol" es obligatorio.',
            'nombreOpcion.unique' => 'El rol ingresado ya está registrado, intentelo de nuevo.',
            'descripcion_opcion.required' => 'Debes agregar una descripción para el rol',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->route('rol.create') 
                ->withErrors($validator)
                ->withInput();
        }



        $rol = new Role();

        $rol->role = $request->input('nombreOpcion');
        $rol->descripcion = $request->input('descripcion_opcion');


        $rol->creado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
        $rol->fecha_creacion = now();

        $rol->save();


        return redirect()->route('roles')->with('success', 'El registro se ha agregado con éxito.');

    } catch (\Throwable $th) {
        return redirect()->route('roles')->with('error', 'Sucedio un error al ingresar el rol');
    }
}



public function edit($id)
{
    try {

    $rol = Role::find($id);

    // Verifica si el registro existe
    if (!$rol) {
        return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
    }

    return view('roles.edit', compact('rol'));


    } catch (\Exception $e) {
        Log::error($e->getMessage());
        return redirect()->route('roles')->with('error', 'Error al cargar la página para editar el rol.');
    }
}



public function update(Request $request, $id)
{

try{

    $rol = Role::find($id);

    if (!$rol) {
        return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
    }

    //validar nombre del estante
    $existingRol = Role::where('role', $request->input('estante_opcion'))
        ->where('role_id', '<>', $id)
        ->first();     

    if ($existingRol) {
        return redirect()->route('roles')->with('error', 'El rol ya está registrado, prueba con otro');         
    }


    // Definimos las reglas de validación
    $rules = [

        'nombreOpcion' => 'required|unique:roles,role,'.$id.',role_id',
        'descripcion_opcion' => 'required',

    ];

    $messages = [

        'nombreOpcion.unique' => 'El rol ya esta registrado, prueba con otro',
        'nombreOpcion.required' => 'El rol es requerido',
        'descripcion_opcion.required' => 'Debes agregar una descripción para el rol',

    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return redirect()
            ->route('rol.update') 
            ->withErrors($validator)
            ->withInput();
    }

    $rol->role = $request->input('nombreOpcion');
    $rol->descripcion = $request->input('descripcion_opcion');

    $rol->save();

    return redirect()->route('roles')->with('success', 'Rol actualizado exitosamente');
    
} catch (ValidationException $e) {
    return redirect()->back()->withErrors($e->errors())->withInput();
} catch (\Throwable $th) {
    return redirect()->route('roles')->with('error', 'Sucedio un error al actualizar el rol, posibles errores: El rol ya existe o no añadiste descripción');
}
}




public function destroy($id)
{
    try {

    $action = request()->input('action');

    if ($action === 'update') {

        $rol = Role::find($id);

        if (!$rol) {
            return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
        }

        $rol->bloqueado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
        $rol->fecha_bloqueo = now();

        $rol->save();

    return redirect()->route('roles')->with('success', 'El registro se ha bloqueado con éxito.');
    }

} catch (QueryException $e) {
    // Manejo de excepciones SQL
    Log::error($e->getMessage());
    return redirect()->route('roles')->with('error', 'Error de base de datos al bloquear el rol');
} catch (\Exception $e) {
    // Manejo de otras excepciones
    Log::error($e->getMessage());
    return redirect()->route('roles')->with('error', 'Error al bloquear el rol');
}
}



public function unblock($id)
{
try {

    $rol = Role::find($id);

    // Verificar si el rol está bloqueado
    if (!$rol->bloqueado_por) {
        return redirect()->route('roles')->with('error', 'El rol no está bloqueado.');
    }

    // Desbloquear al estante
    $rol->bloqueado_por = null;
    $rol->fecha_actualizacion = now();
    $rol->actualizado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
    $rol->fecha_bloqueo = null;


    //Guardo los cambios
    $rol->save();

    return redirect()->route('roles')->with('success', 'El rol ha sido desbloqueado con éxito.');

} catch (\Exception $e) {
    Log::error($e->getMessage());
    return redirect()->route('roles')->with('error', 'Error al desbloquear el rol.');
}
}

}
