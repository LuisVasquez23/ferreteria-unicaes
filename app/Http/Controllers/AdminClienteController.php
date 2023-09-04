<?php

namespace App\Http\Controllers;

use App\Models\DetalleRole;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminClienteController extends Controller
{
    //

    public function index()
    {

        try{


        $usuarios = Usuario::whereHas('detalle_roles', function ($query) {
            $query->whereHas('role', function ($innerQuery) {
                $innerQuery->where('role', 'Cliente');
            });
        })->get();

        return view('cliente.index', compact('usuarios'));

        }catch(\Exception $e){
            
            Log::error($e->getMessage());
            return redirect()->route('clientes')->with('error', 'Error al cargar la página de clientes');
        }  
    }


    public function create()
    {
        try {
            return view('cliente.create');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('clientes')->with('error', 'Error al cargar la página de insercion de clientes');
        }
    }


    

    public function store(Request $request)
    {

        try {

        // Define las reglas de validación
        $rules = [

            'dui_opcion' => 'required|unique:usuarios,dui',

            'nombre_opcion' => 'required',
            'apellido_opcion' => 'required',

            'telefono_opcion' => 'required|regex:/^\d{4}-\d{4}$/|unique:usuarios,telefono',

            'departamento' => 'required',
            'municipio' => 'required',

            'direccion_opcion' => 'nullable',

            'email_opcion' => 'nullable|unique:usuarios,email',
        ];

        $messages = [

            'dui_opcion.required' => 'El campo "Dui" es obligatorio.',

            'dui_opcion.unique' => 'El DUI ingresado ya está registrado en la base de datos, intenta de nuevo.',

            'nombre_opcion.required' => 'Debes registrar al menos un nombre',
            'apellido_opcion.required' => 'Debes registrar al menos un apellido',


            'telefono_opcion.required' => 'El campo "Teléfono" es obligatorio.',
            'telefono_opcion.unique' => 'Este teléfono ya está registrado en la base de datos, intenta de nuevo.',
            'telefono_opcion.regex' => 'El campo "Teléfono" debe tener el formato correcto (por ejemplo, 7889-1256).',


            'departamento.required' => 'Debes seleccionar un departamento.', 
            'municipio.required' => 'Debes seleccionar un municipio.',

            'email_opcion.unique' => 'El email ya está registrado en la base de datos, intenta de nuevo'

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->route('cliente.create') 
                ->withErrors($validator)
                ->withInput();
        }

        $cliente = new Usuario();

        $cliente->dui = $request->input('dui_opcion');
        $cliente->nombres = $request->input('nombre_opcion');
        $cliente->apellidos = $request->input('apellido_opcion');
        $cliente->telefono = $request->input('telefono_opcion');
        $cliente->departamento = $request->input('departamento');
        $cliente->municipio = $request->input('municipio');
        $cliente->direccion = $request->input('direccion_opcion');
        $cliente->email = $request->input('email_opcion');

        $cliente->save();

        $usuarioId = $cliente->usuario_id;

        // Busco el ID del rol "cliente" en la tabla roles
        $rolCliente = Role::where('role', 'Cliente')->first();

        if (!$rolCliente) {
            return redirect()->route('clientes')->with('error', 'El rol "cliente" no se encontró.');
        }


        $detalleRol = new DetalleRole();
        $detalleRol->role_id = $rolCliente->role_id;
        $detalleRol->usuario_id = $usuarioId;
        $detalleRol->save();



        return redirect()->route('clientes')->with('success', 'El registro se ha agregado con éxito.');

    } catch (\Throwable $th) {
        return redirect()->route('clientes')->with('error', 'Sucedio un error al ingresar el cliente');
    }
}




    public function edit($id)
    {
        $usuario = Usuario::find($id);

        // Verifica si el registro existe
        if (!$usuario) {
            return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
        }

        return view('cliente.edit', compact('usuario'));
    }


    

    // public function update(Request $request, $id)
    // {
    //     // Validación similar a la del método 'store'

    //     $menuOption = MenuOption::find($id);

    //     if (!$menuOption) {
    //         return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
    //     }

    //     // Actualiza los datos del registro con los nuevos valores del formulario
    //     $menuOption->nombre = $request->input('nombreOpcion');
    //     $menuOption->direccion = $request->input('direccion');
    //     $menuOption->parent_id = $request->input('parent_id');
    //     $menuOption->role_id = $request->input('role_id');
    //     $menuOption->save();

    //     // Redirige a una página de éxito o donde desees después de actualizar
    //     return redirect()->route('menu')->with('success', 'El registro se ha actualizado con éxito.');
    // }



    public function destroy($id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
        }

        $usuario->delete();

        return redirect()->route('clientes')->with('success', 'El registro se ha eliminado con éxito.');
    }

}
