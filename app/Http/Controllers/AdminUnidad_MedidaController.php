<?php

namespace App\Http\Controllers;

use App\Models\Unidad_Medida;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminUnidad_MedidaController extends Controller
{
    
    public function index(Request $request)
    {
        try{


            $filtro = $request->input('filtro', 'no-bloqueados');

            if ($filtro === 'bloqueados') {
                $medidas = Unidad_Medida::whereNotNull('bloqueado_por')->get();
            } else {
               
                $medidas = Unidad_Medida::whereNull('bloqueado_por')->orWhere('bloqueado_por', '')->get();

            }

        return view('unidad_medida.index', compact('medidas', 'filtro'));

        }catch(\Exception $e){
            
            Log::error($e->getMessage());
            return redirect()->route('unidades')->with('error', 'Error al cargar la página de unidades de medida');
        }  
    }

    
    public function create()
    {

        try {
            return view('unidad_medida.create');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('unidades')->with('error', 'Error al cargar la página de creacion de unidades de medida');
        }
        
    }

   
    public function store(Request $request)
    {
        try {


            // Define las reglas de validación
            $rules = [
    
                'unidad_opcion' => 'required|unique:unidades_medida,nombre',
                'descripcion_opcion' => 'nullable',
            ];
    
            $messages = [
    
                'unidad_opcion.required' => 'El campo "Nombre" es obligatorio.',
                'unidad_opcion.unique' => 'La unidad ingresada ya está registrada, inténtelo  de nuevo.',
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return redirect()
                    ->route('unidad.create') 
                    ->withErrors($validator)
                    ->withInput();
            }
    
              
    
            $unidad = new Unidad_Medida();
    
            $unidad->nombre = $request->input('unidad_opcion');
            $unidad->descripcion = $request->input('descripcion_opcion');
    
    
            $unidad->creado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
            $unidad->fecha_creacion = now();
    
            $unidad->save();
    
    
            return redirect()->route('unidades')->with('success', 'El registro se ha agregado con éxito.');
    
        } catch (\Throwable $th) {
            return redirect()->route('unidades')->with('error', 'Sucedio un error al ingresar la unidad');
        }
    }

   

  
    public function edit($id)
    {
        try {

            $unidad = Unidad_Medida::find($id);
        
            // Verifica si el registro existe
            if (!$unidad) {
                return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
            }
        
            return view('unidad_medida.edit', compact('unidad'));
        
        
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return redirect()->route('unidades')->with('error', 'Error al cargar la página para editar la unidad de medida');
            }
    }

  
    public function update(Request $request, string $id)
    {
        try{

            $medida = Unidad_Medida::find($id);
    
            if (!$medida) {
                return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
            }
    
            //validar nombre de la medida
            $existingName = Unidad_Medida::where('nombre', $request->input('nombre_opcion'))
                ->where('unidad_medida_id', '<>', $id)
                ->first();     
    
            if ($existingName) {
                return redirect()->route('unidades')->with('error', 'La unidad de medida ya está registrada, prueba con otra');         
            }
    
    
            // Definimos las reglas de validación
            $rules = [
    
                'nombre_opcion' => 'required|unique:unidades_medida,nombre,'.$id.',unidad_medida_id',
                'descripcion_opcion' => 'nullable',
    
            ];
    
            $messages = [
    
                'nombre_opcion.unique' => 'La unidad ya esta registrada, prueba con otra',
                'nombre_opcion.required' => 'El nombre es requerido',
    
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return redirect()
                    ->route('unidad.update') 
                    ->withErrors($validator)
                    ->withInput();
            }
    
            $medida->nombre = $request->input('nombre_opcion');
            $medida->descripcion = $request->input('descripcion_opcion');
    
            $medida->save();
    
            return redirect()->route('unidades')->with('success', 'Medida actualizada exitosamente');
            
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->route('unidades')->with('error', 'Sucedio un error al actualizar la medida, ya existe, prueba con otra');
        }
    }

   
    public function destroy($id)
    {
        try {

        $action = request()->input('action');

        if ($action === 'update') {

            $unidad = Unidad_Medida::find($id);

            if (!$unidad) {
                return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
            }

            $unidad->bloqueado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
            $unidad->fecha_bloqueo = now();

            $unidad->save();

        return redirect()->route('unidades')->with('success', 'El registro se ha bloqueado con éxito.');
        }

    } catch (QueryException $e) {
        // Manejo de excepciones SQL
        Log::error($e->getMessage());
        return redirect()->route('unidades')->with('error', 'Error de base de datos al bloquear el estante');
    } catch (\Exception $e) {
        // Manejo de otras excepciones
        Log::error($e->getMessage());
        return redirect()->route('unidades')->with('error', 'Error al bloquear el estante');
    }
   }



   public function unblock($id)
    {
    try {

        $unidad = Unidad_Medida::find($id);

        // Verificar si la unidad está bloqueada
        if (!$unidad->bloqueado_por) {
            return redirect()->route('unidades')->with('error', 'La unidad de medida no está bloqueada.');
        }

        // Desbloquear la unidad
        $unidad->bloqueado_por = null;
        $unidad->fecha_actualizacion = now();
        $unidad->actualizado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
        $unidad->fecha_bloqueo = null;


        //Guardo los cambios
        $unidad->save();

        return redirect()->route('unidades')->with('success', 'La unidad de medida ha sido desbloqueada con éxito.');

    } catch (\Exception $e) {
        Log::error($e->getMessage());
        return redirect()->route('unidades')->with('error', 'Error al desbloquear la unidad de medida.');
    }
    }
}
