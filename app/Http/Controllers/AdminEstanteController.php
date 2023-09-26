<?php

namespace App\Http\Controllers;

use App\Models\Estante;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminEstanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{


            $filtro = $request->input('filtro', 'no-bloqueados');

            if ($filtro === 'bloqueados') {
                $estantes = Estante::whereNotNull('bloqueado_por')->get();
            } else {
               
                $estantes = Estante::whereNull('bloqueado_por')->orWhere('bloqueado_por', '')->get();

            }

        return view('estantes.index', compact('estantes', 'filtro'));

        }catch(\Exception $e){
            
            Log::error($e->getMessage());
            return redirect()->route('estantes')->with('error', 'Error al cargar la página de estantes');
        }  
    }

    public function create()
    {
        try {
            return view('estantes.create');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('estantes')->with('error', 'Error al cargar la página de creacion de estantes');
        }
    }

    public function store(Request $request)
    {

        try {


        // Define las reglas de validación
        $rules = [

            'estante_opcion' => 'required|unique:estantes,estante',
            'ubicacion_opcion' => 'required',
            'descripcion_opcion' => 'nullable',
        ];

        $messages = [

            'estante_opcion.required' => 'El campo "Nombre" es obligatorio.',
            'estante_opcion.unique' => 'El estante ingresado ya está registrado, intentelo de nuevo.',
            'ubicacion_opcion.required' => 'El campo "Ubicación" es obligatorio.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->route('estante.create') 
                ->withErrors($validator)
                ->withInput();
        }



        $estante = new Estante();

        $estante->estante = $request->input('estante_opcion');
        $estante->ubicacion = $request->input('ubicacion_opcion');
        $estante->descripcion = $request->input('descripcion_opcion');


        $estante->creado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
        $estante->fecha_creacion = now();

        $estante->save();


        return redirect()->route('estantes')->with('success', 'El registro se ha agregado con éxito.');

    } catch (\Throwable $th) {
        return redirect()->route('estantes')->with('error', 'Sucedio un error al ingresar el estante');
    }
}

public function edit($id)
{
    try {

    $estante = Estante::find($id);

    // Verifica si el registro existe
    if (!$estante) {
        return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
    }

    return view('estantes.edit', compact('estante'));


    } catch (\Exception $e) {
        Log::error($e->getMessage());
        return redirect()->route('estantes')->with('error', 'Error al cargar la página para editar el estante');
    }
}



    public function update(Request $request, $id)
    {

    try{

        $estante = Estante::find($id);

        if (!$estante) {
            return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
        }

        //validar nombre del estante
        $existingName = Estante::where('estante', $request->input('estante_opcion'))
            ->where('estante_id', '<>', $id)
            ->first();     

        if ($existingName) {
            return redirect()->route('estantes')->with('error', 'El estante ya está registrado, prueba con otro');         
        }


        // Definimos las reglas de validación
        $rules = [

            'estante_opcion' => 'required|unique:estantes,estante,'.$id.',estante_id',

            'ubicacion_opcion' => 'required',
            'descripcion_opcion' => 'nullable',

        ];

        $messages = [

            'estante_opcion.unique' => 'El estante ya esta registrado, prueba con otro',
            'estante_opcion.required' => 'El estante es requerido',
            'ubicacion_opcion.required' => 'El campo "Ubicación" es obligatorio.',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->route('estante.update') 
                ->withErrors($validator)
                ->withInput();
        }

        $estante->estante = $request->input('estante_opcion');
        $estante->ubicacion = $request->input('ubicacion_opcion');
        $estante->descripcion = $request->input('descripcion_opcion');

        $estante->save();

        return redirect()->route('estantes')->with('success', 'Estante actualizado exitosamente');
        
    } catch (ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Throwable $th) {
        return redirect()->route('estantes')->with('error', 'Sucedio un error al actualizar el estante, revisa que todos los campos sean correctos');
    }
}


    public function destroy($id)
    {
        try {

        $action = request()->input('action');

        if ($action === 'update') {

            $estante = Estante::find($id);

            if (!$estante) {
                return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
            }

            $estante->bloqueado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
            $estante->fecha_bloqueo = now();

            $estante->save();

        return redirect()->route('estantes')->with('success', 'El registro se ha bloqueado con éxito.');
        }

    } catch (QueryException $e) {
        // Manejo de excepciones SQL
        Log::error($e->getMessage());
        return redirect()->route('estantes')->with('error', 'Error de base de datos al bloquear el estante');
    } catch (\Exception $e) {
        // Manejo de otras excepciones
        Log::error($e->getMessage());
        return redirect()->route('estantes')->with('error', 'Error al bloquear el estante');
    }
   }



   public function unblock($id)
    {
    try {

        $estante = Estante::find($id);

        // Verificar si el estante está bloqueado
        if (!$estante->bloqueado_por) {
            return redirect()->route('estantes')->with('error', 'El estante no está bloqueado.');
        }

        // Desbloquear al estante
        $estante->bloqueado_por = null;
        $estante->fecha_actualizacion = now();
        $estante->actualizado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
        $estante->fecha_bloqueo = null;


        //Guardo los cambios
        $estante->save();

        return redirect()->route('estantes')->with('success', 'El estante ha sido desbloqueado con éxito.');

    } catch (\Exception $e) {
        Log::error($e->getMessage());
        return redirect()->route('estantes')->with('error', 'Error al desbloquear el estante.');
    }
    }
}
