<?php

namespace App\Http\Controllers;

use App\Models\DetalleRole;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminDetalleRolController extends Controller
{
  
    public function index(Request $request)
    {
        try {

            $filtro = $request->input('filtro', 'no-bloqueados');

            if ($filtro === 'bloqueados') {
                $detalles_roles = DetalleRole::with(['role', 'usuario'])
                    ->whereNotNull('bloqueado_por')
                    ->get();
            } else {
                $detalles_roles = DetalleRole::with(['role', 'usuario'])
                    ->whereNull('bloqueado_por')
                    ->orWhere('bloqueado_por', '')
                    ->get();
            }

            return view('detalles_rol.index', compact('detalles_roles', 'filtro'));


        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('detalles_roles')->with('error', 'Error al cargar la página de detalle rol');
        }
    }


    public function create()
    {
        try {

            // listo los roles de la db

            $rolesFromDB = Role::pluck('role', 'role_id');

            //filtro para que no pueda ingresar otro MegaAdmin
            $roles = $rolesFromDB->reject(function ($role, $roleId) {
                return $role === 'MegaAdmin';
            });



            $usuarios = Usuario::pluck(DB::raw("CONCAT(nombres, ' ', apellidos) AS nombre_completo"), 'usuario_id');

            return view('detalles_rol.create', compact('roles', 'usuarios'));
            
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('detalles_roles')->with('error', 'Error al cargar la página de creacion de detalles para los roles');
        }
    }


    public function store(Request $request)
    {
        try {


            // Define las reglas de validación
            $rules = [
    
                'rol_input' => 'required|string',
                'usuario_input' => 'required|string',
            ];
    
            $messages = [
    
                'rol_input.required' => 'El campo "rol" es obligatorio.',
                'usuario_input.required' => 'Debes agregar el usuario para el rol',
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return redirect()
                    ->route('detalle_rol.create') 
                    ->withErrors($validator)
                    ->withInput();
            }


            $detalle = new DetalleRole();
    
            $detalle->role_id = $request->input('rol_input');
            $detalle->usuario_id = $request->input('usuario_input');
    
    
            $detalle->creado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
            $detalle->fecha_creacion = now();
    
            $detalle->save();
    
    
            return redirect()->route('detalles_roles')->with('success', 'El registro se ha agregado con éxito.');
    
        } catch (\Throwable $th) {
            return redirect()->route('detalles_roles')->with('error', 'Sucedio un error al ingresar el detalle');
        }
    }

 
    public function edit($id)
    {
        try {
    
        $detalle = DetalleRole::find($id);

        // listo los roles de la db

        $rolesFromDB = Role::pluck('role', 'role_id');

        //filtro para que no pueda ingresar otro MegaAdmin
        $roles = $rolesFromDB->reject(function ($role, $roleId) {
            return $role === 'MegaAdmin';
        });
        
        $usuarios = Usuario::pluck(DB::raw("CONCAT(nombres, ' ', apellidos) AS nombre_completo"), 'usuario_id');

    
        // Verifica si el registro existe
        if (!$detalle) {
            return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
        }
    
        return view('detalles_rol.edit', compact('detalle', 'roles', 'usuarios'));
    
    
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('detalles_roles')->with('error', 'Error al cargar la página para editar el detalle del rol');
        }
    }


    public function update(Request $request, $id)
    {

        try {

            $detalle = DetalleRole::find($id);

            if (!$detalle) {

                return redirect()->route('detalles_roles')->with('error', 'Detalle no encontrado');
            }

            $detalle->role_id = $request->input('rol_input');
            $detalle->usuario_id = $request->input('usuario_input');

            $detalle->save();

            return redirect()->route('detalles_roles')->with('success', 'El detalle para el rol fue actualizado correctamente');
        } catch (\Throwable $th) {
            return redirect()->route('detalles_roles')->with('error', 'Error al actualizar, el usuario ya tiene un rol seleccionado');
        }
    }
  
    public function destroy($id)
    {
        try {

        $action = request()->input('action');

        if ($action === 'update') {

            $detalle = DetalleRole::find($id);

            if (!$detalle) {
                return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
            }

            $detalle->bloqueado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
            $detalle->fecha_bloqueo = now();

            $detalle->save();

        return redirect()->route('detalles_roles')->with('success', 'El registro se ha bloqueado con éxito.');
        }

    } catch (QueryException $e) {
        // Manejo de excepciones SQL
        Log::error($e->getMessage());
        return redirect()->route('detalles_roles')->with('error', 'Error de base de datos al bloquear el detalle');
    } catch (\Exception $e) {
        // Manejo de otras excepciones
        Log::error($e->getMessage());
        return redirect()->route('detalles_roles')->with('error', 'Error al bloquear el detalle');
    }
   }



   public function unblock($id)
    {
    try {

        $detalle = DetalleRole::find($id);

        // Verificar si el detalle está bloqueado
        if (!$detalle->bloqueado_por) {
            return redirect()->route('detalles_roles')->with('error', 'El estante no está bloqueado.');
        }

        // Desbloquear al estante
        $detalle->bloqueado_por = null;
        $detalle->fecha_actualizacion = now();
        $detalle->actualizado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
        $detalle->fecha_bloqueo = null;


        //Guardo los cambios
        $detalle->save();

        return redirect()->route('detalles_roles')->with('success', 'El detalle ha sido desbloqueado con éxito.');

    } catch (\Exception $e) {
        Log::error($e->getMessage());
        return redirect()->route('detalles_roles')->with('error', 'Error al desbloquear el detalle.');
    }
    }
}
