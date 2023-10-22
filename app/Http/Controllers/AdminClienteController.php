<?php

namespace App\Http\Controllers;

use App\Models\DetalleRole;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class AdminClienteController extends Controller
{
    //

    public function index(Request $request)
    {

        try{


            $filtro = $request->input('filtro', 'no-bloqueados');

            if ($filtro === 'bloqueados') {
                $clientes = Usuario::whereHas('detalle_roles', function ($query) {
                    $query->whereHas('role', function ($innerQuery) {
                        $innerQuery->where('role', 'Cliente');
                    });
                })
                ->whereNotNull('bloqueado_por')
                ->get();
            } else {
                $clientes = Usuario::whereHas('detalle_roles', function ($query) {
                    $query->whereHas('role', function ($innerQuery) {
                        $innerQuery->where('role', 'Cliente');
                    });
                })
                ->whereNull('bloqueado_por')
                ->get();
            }

        return view('cliente.index', compact('clientes', 'filtro'));

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
            
            return redirect()->route('clientes')->with('error', 'Error al cargar la página de creacion de clientes');
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
            'direccion_opcion' => 'required',
            'email_opcion' => 'required|email|unique:usuarios,email',
        ];

        $messages = [

            'dui_opcion.required' => 'El campo "Dui" es obligatorio.',
            'dui_opcion.unique' => 'El DUI ingresado ya está registrado, intentelo de nuevo.',
            'nombre_opcion.required' => 'Debes registrar al menos un nombre',
            'apellido_opcion.required' => 'Debes registrar al menos un apellido',
            'telefono_opcion.required' => 'El campo "Teléfono" es obligatorio.',
            'telefono_opcion.unique' => 'Este teléfono ya está registrado, intentelo de nuevo.',
            'telefono_opcion.regex' => 'El campo "Teléfono" debe tener el formato correcto (por ejemplo, 7889-1256).',
            'direccion_opcion.required' => 'Debes registrar una direccion',
            'email_opcion.required' => 'El email es requerido',
            'email_opcion.unique' => 'El email ya está registrado, intentelo de nuevo',
            'email_opcion.email' => 'El campo "Email" debe ser una dirección de correo electrónico válida.',
           

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

        $cliente->direccion = $request->input('direccion_opcion');
        $cliente->email = $request->input('email_opcion');

        $cliente->creado_por = Auth::user()->nombres;
        $cliente->fecha_creacion = now();

        $cliente->save();


         //Obtener ID del usuario que se esta ingresando
        $usuarioId = $cliente->usuario_id;

        // Busco el ID del rol "cliente" en la tabla roles
        $rolCliente = Role::where('role', 'Cliente')->first();

        if (!$rolCliente) {
            return redirect()->route('clientes')->with('error', 'El rol de cliente no se encontró.');
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
        try {

        $usuario = Usuario::find($id);

        // Verifica si el registro existe
        if (!$usuario) {
            return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
        }

        return view('cliente.edit', compact('usuario'));


        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('clientes')->with('error', 'Error al cargar la página para editar el cliente');
        }
    }


    

    public function update(Request $request, $id)
    {

    try{

        $cliente = Usuario::find($id);

        if (!$cliente) {
            return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
        }

        //validar teléfono
        $existingPhone = Usuario::where('telefono', $request->input('telefono_opcion'))
            ->where('usuario_id', '<>', $id)
            ->first();     
 
         if ($existingPhone) {
            return redirect()->route('clientes')->with('error', 'El teléfono ya está registrado.');         
        }

        //validar email
        $existingEmail = Usuario::where('email', $request->input('email_opcion'))
            ->where('usuario_id', '<>', $id)
            ->first();

        if ($existingEmail) {
            return redirect()->route('clientes')->with('error', 'El correo electrónico ya está registrado.');         
        }

        if($request->input('direccion_opcion') == null){
            return redirect()->route('clientes')->with('error', 'Dejaste el campo direccion vacio, intenta de nuevo');         

        }

          // Definimos las reglas de validación
          $rules = [

            'nombre_opcion' => 'required',
            'apellido_opcion' => 'required',

            'telefono_opcion' => 'required|regex:/^\d{4}-\d{4}$/|unique:usuarios,telefono,'.$id.',usuario_id',

            'direccion_opcion' => 'required',

            'email_opcion' => 'required|email|unique:usuarios,email,'.$id.',usuario_id',
        ];

        $messages = [

            'nombre_opcion.required' => 'Debes registrar al menos un nombre',
            'apellido_opcion.required' => 'Debes registrar al menos un apellido',


            'telefono_opcion.required' => 'El campo "Teléfono" es obligatorio.',
            'telefono_opcion.unique' => 'Este teléfono ya está registrado, intenta de nuevo.',
            'telefono_opcion.regex' => 'El campo "Teléfono" debe tener el formato correcto (por ejemplo, 7889-1256).',

            'direccion_opcion.required' => 'El campo "Direccion" es obligatorio.',


            'email_opcion.unique' => 'El email ya está registrado, intenta de nuevo',
            'email_opcion.required' => 'El email es requerido',
            'email_opcion.email' => 'El campo "Email" debe ser una dirección de correo electrónico válida.',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->route('cliente.update') 
                ->withErrors($validator)
                ->withInput();
        }

     


        $cliente->dui = $request->input('dui_opcion');
        $cliente->nombres = $request->input('nombre_opcion');
        $cliente->apellidos = $request->input('apellido_opcion');
        $cliente->telefono = $request->input('telefono_opcion');

      
        $cliente->direccion = $request->input('direccion_opcion');


        $cliente->email = $request->input('email_opcion');
        
        $cliente->save();

        return redirect()->route('clientes')->with('success', 'Cliente actualizado exitosamente');
        
    } catch (ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Throwable $th) {
        return redirect()->route('clientes')->with('error', 'Sucedio un error al actualizar el cliente, revisa que todos los campos sean correctos');
    }
}



    public function destroy($id)
    {
        try {

        $action = request()->input('action');

        if ($action === 'update') {

            $cliente = Usuario::find($id);

            if (!$cliente) {
                return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
            }

            $cliente->bloqueado_por = Auth::user()->nombres;
            $cliente->fecha_bloqueo = now();

            $cliente->save();

        return redirect()->route('clientes')->with('success', 'El registro se ha bloqueado con éxito.');
        }

    } catch (QueryException $e) {
        // Manejo de excepciones SQL
        Log::error($e->getMessage());
        return redirect()->route('clientes')->with('error', 'Error de base de datos al eliminar el cliente');
    } catch (\Exception $e) {
        // Manejo de otras excepciones
        Log::error($e->getMessage());
        return redirect()->route('clientes')->with('error', 'Error al eliminar el cliente');
    }
   }


   public function unblock($id)
{
    try {

        $cliente = Usuario::find($id);

        // Verificar si el cliente está bloqueado
        if (!$cliente->bloqueado_por) {
            return redirect()->route('clientes')->with('error', 'El cliente no está bloqueado.');
        }

        // Desbloquear al cliente
        $cliente->bloqueado_por = null;
        $cliente->fecha_actualizacion = now();
        $cliente->actualizado_por = Auth::user()->nombres;
        $cliente->fecha_bloqueo = null;


        //Guardo los cambios
        $cliente->save();

        return redirect()->route('clientes')->with('success', 'El cliente ha sido desbloqueado con éxito.');

    } catch (\Exception $e) {
        Log::error($e->getMessage());
        return redirect()->route('clientes')->with('error', 'Error al desbloquear el cliente.');
    }
}





}
