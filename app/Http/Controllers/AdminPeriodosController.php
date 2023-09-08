<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator; 

class AdminPeriodosController extends Controller
{
    //
    public function index()
    {
        $periodos = Periodo::all();

        return view('periodos.index', compact('periodos'));
    }

    public function create()
    {
        return view('periodos.create');
    }



    public function store(Request $request)
    {
        // Define las reglas de validación
        $rules = [
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ];
    
        // Define mensajes personalizados para las reglas de validación
        $messages = [
            'fecha_inicio.required' => 'El campo "Fecha de Inicio" es obligatorio.',
            'fecha_inicio.date' => 'El campo "Fecha de Inicio" debe ser una fecha válida.',
            'fecha_fin.required' => 'El campo "Fecha de Fin" es obligatorio.',
            'fecha_fin.date' => 'El campo "Fecha de Fin" debe ser una fecha válida.',
            'fecha_fin.after' => 'La "Fecha de Fin" debe ser posterior a la "Fecha de Inicio".',
        ];
    
        // Valida los datos del formulario
        $validator = Validator::make($request->all(), $rules, $messages);
    
        // Si la validación falla, redirige de vuelta con los errores
        if ($validator->fails()) {
            return redirect()
                ->route('periodos.create') // Reemplaza 'periodos.create' con la ruta de tu formulario
                ->withErrors($validator)
                ->withInput();
        }
    
        // Si la validación pasa, guarda los datos y realiza otras acciones según sea necesario
        $creadoPor = Auth::user()->nombres;
        $fechaCreacion = Carbon::now();
    
        $periodo = new Periodo([
            'fecha_inicio' => $request->input('fecha_inicio'),
            'fecha_fin' => $request->input('fecha_fin'),
            'creado_por' => $creadoPor,
            'fecha_creacion' => $fechaCreacion,
        ]);
    
        $periodo->save();
    
        // Redirige a una página de éxito o donde desees después de guardar
        return redirect()->route('periodos')->with('success', 'Período creado exitosamente.');
    }

    
    /* MODIFICAR PERIODOS */

    public function edit($id)
    {
        $periodo = Periodo::find($id);

        if (!$periodo) {
            return redirect()->route('periodos')->with('error', 'Período no encontrado.');
        }

        return view('periodos.edit', compact('periodo'));
    }


    public function update(Request $request, $id)
    {
        // Define las reglas de validación
        $rules = [
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ];

        // Define mensajes personalizados para las reglas de validación
        $messages = [
            'fecha_inicio.required' => 'El campo "Fecha de Inicio" es obligatorio.',
            'fecha_inicio.date' => 'El campo "Fecha de Inicio" debe ser una fecha válida.',
            'fecha_fin.required' => 'El campo "Fecha de Fin" es obligatorio.',
            'fecha_fin.date' => 'El campo "Fecha de Fin" debe ser una fecha válida.',
            'fecha_fin.after' => 'La "Fecha de Fin" debe ser posterior a la "Fecha de Inicio".',
        ];

        // Valida los datos del formulario
        $validator = Validator::make($request->all(), $rules, $messages);

        // Si la validación falla, redirige de vuelta con los errores
        if ($validator->fails()) {
            return redirect()
                ->route('periodos.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        // Si la validación pasa, actualiza los datos y realiza otras acciones según sea necesario
        $periodo = Periodo::find($id);
        $periodo->fecha_inicio = $request->input('fecha_inicio');
        $periodo->fecha_fin = $request->input('fecha_fin');
        
        // Obtiene el usuario autenticado que está realizando la actualización
        $usuario = auth()->user();
        
        // Actualiza los campos 'actualizado_por' y 'fecha_actualizacion'
        $periodo->actualizado_por = $usuario->nombres; 
        $periodo->fecha_actualizacion = now(); 

        $periodo->save();

        // Redirige a la página de índice de períodos o a donde desees después de actualizar
        return redirect()->route('periodos')->with('success', 'Período actualizado exitosamente.');
    }




   


    public function destroy($id)
    {
        $periodos = Periodo::find($id);

        if (!$periodos) {
            return redirect()->route('periodos')->with('error', 'Periodo no encontrada.');
        }
        $periodos->delete();

        return redirect()->route('periodos')->with('success', 'Periodo eliminada exitosamente.');
    }

}
