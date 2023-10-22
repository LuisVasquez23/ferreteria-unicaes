<?php

namespace App\Http\Controllers;

use Doctrine\DBAL\Schema\View;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use App\Models\DetalleRole;
use Illuminate\Validation\Rule;





class AdminProveedoresController extends Controller
{
    public function index(Request $request)
    {

        try{


            $filtro = $request->input('filtro', 'no-bloqueados');

            if ($filtro === 'bloqueados') {
                $proveedores = Usuario::whereHas('detalle_roles', function ($query) {
                    $query->whereHas('role', function ($innerQuery) {
                        $innerQuery->where('role', 'Proveedor');
                    });
                })
                ->whereNotNull('bloqueado_por')
                ->get();
            } else {
                $proveedores = Usuario::whereHas('detalle_roles', function ($query) {
                    $query->whereHas('role', function ($innerQuery) {
                        $innerQuery->where('role', 'Proveedor');
                    });
                })
                ->whereNull('bloqueado_por')
                ->get();
            }

        return view('proveedores.index', compact('proveedores', 'filtro'));

        }catch(\Exception $e){

            Log::error($e->getMessage());
            return redirect()->route('proveedores.index')->with('error', 'Error al cargar la página de provedores');
        }
    }
    // Método para mostrar el formulario de creación de un proveedor
    public function create()
    {
        try {
            return view('proveedores.create');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('proveedores.index')->with('error', 'Error al cargar la página de inserción de proveedores');
        }
    }

    // Método para guardar un nuevo proveedor
    public function store(Request $request)
    {
        try {
            // Define las reglas de validación
            $rules = [
                'nit_opcion' => 'required|unique:usuarios,nit',
                'nombre_opcion' => 'required',
                'telefono_opcion' => 'required|regex:/^\d{4}-\d{4}$/|unique:usuarios,telefono',
                'direccion_opcion' => 'nullable',
                'email_opcion' => 'required|unique:usuarios,email',

            ];

            $messages = [
                'nit_opcion.required' => 'El campo "NIT" es obligatorio.',
                'nit_opcion.unique' => 'El NIT ingresado ya está registrado, intenta de nuevo.',
                'nombre_opcion.required' => 'Debes registrar al menos un nombre.',
                'telefono_opcion.required' => 'El campo "Teléfono" es obligatorio.',
                'telefono_opcion.unique' => 'Este teléfono ya está registrado, intenta de nuevo.',
                'telefono_opcion.regex' => 'El campo "Teléfono" debe tener el formato correcto (por ejemplo, 7889-1256).',
                'email_opcion.required' => 'El email es requerido.',
                'email_opcion.unique' => 'El email ya está registrado, intenta de nuevo.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()
                    ->route('proveedores.create')
                    ->withErrors($validator)
                    ->withInput();
            }

          

            $proveedor = new Usuario();

            $proveedor->nit = $request->input('nit_opcion');
            $proveedor->nombres = $request->input('nombre_opcion');
            $proveedor->telefono = $request->input('telefono_opcion');
            $proveedor->direccion = $request->input('direccion_opcion');
            $proveedor->email = $request->input('email_opcion');
            $proveedor->creado_por = Auth::user()->nombres;
            $proveedor->fecha_creacion = now();

            $proveedor->save();

            // Obtener ID del usuario que se está ingresando
            $usuarioId = $proveedor->usuario_id;

            // Buscar el ID del rol "Proveedor" en la tabla roles
            $rolProveedor = Role::where('role', 'Proveedor')->first();

            if (!$rolProveedor) {
                return redirect()->route('proveedores.index')->with('error', 'El rol "Proveedor" no se encontró.');
            }

            $detalleRol = new DetalleRole();
            $detalleRol->role_id = $rolProveedor->role_id;
            $detalleRol->usuario_id = $usuarioId;
            $detalleRol->save();

            return redirect()->route('proveedores.index')->with('success', 'El registro se ha agregado con éxito.');

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return redirect()->route('proveedores.index')->with('error', 'Sucedio un error al ingresar el proveedor');
        }
    }






    public function edit($id)
    {
        // Obtener el proveedor que se va a editar por su ID
        $proveedor = Usuario::find($id);
    
        // Verificar si el proveedor existe
        if (!$proveedor) {
            return redirect()->route('proveedores.index')->with('error', 'Proveedor no encontrado.');
        }
    
        // Aquí puedes mostrar el formulario de edición de un proveedor específico
        // Pasar el objeto $proveedor a la vista
        return view('proveedores.edit', compact('proveedor'));
    }

    // Método para actualizar un proveedor existente


    public function update(Request $request, $id)
    {
        try {
            $proveedor = Usuario::find($id);
    
            if (!$proveedor) {
                return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
            }
    
            // Validar teléfono
            $existingPhone = Usuario::where('telefono', $request->input('telefono_opcion'))
                ->where('usuario_id', '<>', $id)
                ->first();
    
            if ($existingPhone) {
                return redirect()->route('proveedores')->with('error', 'El teléfono ya está registrado en la base de datos.');
            }
    
            // Validar email
            $existingEmail = Usuario::where('email', $request->input('email_opcion'))
                ->where('usuario_id', '<>', $id)
                ->first();
    
            if ($existingEmail) {
                return redirect()->route('proveedores')->with('error', 'El correo electrónico ya está registrado en la base de datos.');
            }
    
            // Definir las reglas de validación
            $rules = [
                'nit_opcion' => 'required|unique:usuarios,nit,' . $id . ',usuario_id',
                'nombre_opcion' => 'required',
                'telefono_opcion' => 'required|regex:/^\d{4}-\d{4}$/|unique:usuarios,telefono,' . $id . ',usuario_id',
                'direccion_opcion' => 'nullable',
                'email_opcion' => 'required|email|unique:usuarios,email,' . $id . ',usuario_id',
            ];
    
            $messages = [
                'nit_opcion.required' => 'El campo "NIT" es obligatorio.',
                'nit_opcion.unique' => 'El NIT ingresado ya está registrado en la base de datos, intenta de nuevo.',
                'nombre_opcion.required' => 'Debes registrar al menos un nombre.',
                'telefono_opcion.required' => 'El campo "Teléfono" es obligatorio.',
                'telefono_opcion.unique' => 'Este teléfono ya está registrado en la base de datos, intenta de nuevo.',
                'telefono_opcion.regex' => 'El campo "Teléfono" debe tener el formato correcto (por ejemplo, 7889-1256).',
                'email_opcion.required' => 'El email es requerido.',
                'email_opcion.unique' => 'El email ya está registrado en la base de datos, intenta de nuevo.',
                'email_opcion.email' => 'El campo "Email" debe ser una dirección de correo electrónico válida.',
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return redirect()
                    ->route('proveedores.edit', $id) // Redirige al formulario de edición
                    ->withErrors($validator)
                    ->withInput();
            }
    
            $proveedor->nit = $request->input('nit_opcion');
            $proveedor->nombres = $request->input('nombre_opcion');
            $proveedor->telefono = $request->input('telefono_opcion');
            $proveedor->departamento = $request->input('departamento');
            $proveedor->email = $request->input('email_opcion');
            $proveedor->creado_por = Auth::user()->nombres;
            $proveedor->fecha_creacion = now();
    
            $proveedor->save();
    
            return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado exitosamente');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->route('proveedores.index')->with('error', 'Sucedio un error al actualizar el proveedor, revisa que todos los campos sean correctos');
        }
    }
    
    
    

    // Método para eliminar un proveedor
    public function bloquear($id)
    {
        try {

                $proveedor = Usuario::find($id);

                if (!$proveedor) {
                    return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
                }

                $proveedor->bloqueado_por = Auth::user()->nombres;
                $proveedor->fecha_bloqueo = now();

                $proveedor->save();

                return redirect()->route('proveedores.index')->with('success', 'El proveedor se ha bloqueado con éxito.');
            
        } catch (QueryException $e) {
            // Manejo de excepciones SQL
            Log::error($e->getMessage());
            return redirect()->route('proveedores.index')->with('error', 'Error de base de datos al bloquear el proveedor');
        } catch (\Exception $e) {
            // Manejo de otras excepciones
            Log::error($e->getMessage());
            return redirect()->route('proveedores.index')->with('error', 'Error al bloquear el proveedor');
        }
    }

    public function unblock($id)
    {
        try {
            $proveedor = Usuario::find($id);

            // Verificar si el proveedor está bloqueado
            if (!$proveedor->bloqueado_por) {
                return redirect()->route('proveedores.index')->with('error', 'El proveedor no está bloqueado.');
            }

            // Desbloquear al proveedor
            $proveedor->bloqueado_por = null;
            $proveedor->fecha_actualizacion = now();
            $proveedor->actualizado_por = Auth::user()->nombres;
            $proveedor->fecha_bloqueo = null;

            // Guardar los cambios
            $proveedor->save();

            return redirect()->route('proveedores.index')->with('success', 'El proveedor ha sido desbloqueado con éxito.');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('proveedores.index')->with('error', 'Error al desbloquear el proveedor.');
        }
    }


}
