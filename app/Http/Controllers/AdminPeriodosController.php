<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class AdminPeriodosController extends Controller
{
    //
    public function index(Request $request)
    {
        try{         

            $filtro = $request->input('filtro', 'no-bloqueados');

            if ($filtro === 'bloqueados') {
                $periodos = Periodo::whereNotNull('bloqueado_por')->get();
            } else {
                $periodos = Periodo::whereNull('bloqueado_por')->get();
            }

            return view('periodos.index', compact('periodos', 'filtro'));
            
        } catch (\Exception $e) {
            return back()->withError('Error al obtener las categorías: ' . $e->getMessage());
        }
        
    }


       

        

    public function create()
    {
        // Obtiene el nombre del usuario autenticado
        $createdByName = auth()->user()->nombres;
        
        // Verifica si ya existen registros para el año actual
        $yearExists = DB::select("SELECT PeriodsExistForYear() as year_exists")[0]->year_exists;

        // Si existen registros para el año actual, muestra un mensaje de error
        if ($yearExists) {
            return redirect()->route('periodos')->with('error', 'Los períodos ya se han creado para este año.');
        }

        // Si no existen registros para el año actual, llama al procedimiento almacenado
        DB::select("CALL create_periods_for_year_if_not_exist(?)", [$createdByName]);

        return redirect()->route('periodos')->with('success', 'Períodos agregados exitosamente.');
    }

    
    




    public function store(Request $request)
    {
        // Obtiene la fecha actual
        $fechaActual = Carbon::now();
        
        // Obtiene el primer día del mes actual
        $primerDiaMesActual = $fechaActual->startOfMonth();
        
        // Obtiene el primer día del mes siguiente
        $primerDiaMesSiguiente = $primerDiaMesActual->copy()->addMonth();
        
        // Calcula la diferencia en meses entre el mes actual y el mes de inicio seleccionado
        $diferenciaMeses = $primerDiaMesSiguiente->diffInMonths($request->input('fecha_inicio'));
        
        // Valida que la fecha de inicio esté dentro del rango adecuado
        if ($diferenciaMeses < 0) {
            return redirect()
                ->route('periodos.create')
                ->with('error', 'La "Fecha de Inicio" debe estar dentro del mes actual o el mes siguiente.');
        }
        
        // Crea un período para cada mes restante
        for ($i = 0; $i <= $diferenciaMeses; $i++) {
            $periodo = new Periodo([
                'fecha_inicio' => $primerDiaMesActual->format('Y-m-d'),
                'fecha_fin' => $primerDiaMesSiguiente->subDay()->format('Y-m-d'),
                'creado_por' => Auth::user()->nombres,
                'fecha_creacion' => Carbon::now(),
            ]);
        
            $periodo->save();
        
            // Avanza al próximo mes
            $primerDiaMesActual->addMonth();
            $primerDiaMesSiguiente->addMonth();
        }
        
        // Redirige a una página de éxito o donde desees después de guardar
        return redirect()->route('periodos')->with('success', 'Períodos creados exitosamente.');
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



    public function bloquear($id)
    {
        try {
            // Obtener la categoría que se va a eliminar
            $periodo = Periodo::find($id);

            // Verificar si la categoría existe
            if (!$periodo) {
                return redirect()->route('periodos')->with('error', 'Categoría no encontrada.');
            }

            // Eliminar la categoría de la base de datos
            //$categoria->delete();

            // Cambiamos estado a bloqueado

             $periodo->fecha_bloqueo = now();
             // Obtener el nombre del usuario autenticado y asignarlo al campo "actualizado_por"
             $periodo->bloqueado_por = Auth::user()->nombres;

             // Guardar los cambios en la base de datos
             $periodo->save();

            // Redireccionar a la página de índice de categorías o a donde desees después de eliminar
            return redirect()->route('periodos')->with('success', 'Periodo eliminado exitosamente.');
        } catch (QueryException $e) {
            // Manejar excepciones relacionadas con consultas SQL
            return redirect()->route('periodos')->with('error', 'Ocurrió un error al eliminar la categoría: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Manejar otras excepciones generales
            return redirect()->route('periodos')->with('error', 'Ocurrió un error al eliminar la categoría.');
        }
    }

    public function unblock($id)
    {
        try {
            // Buscar la categoría por su ID
            $periodo = Periodo::find($id);

            // Verificar si la categoría está bloqueada
            if (!$periodo->bloqueado_por) {
                return redirect()->route('periodos')->with('error', 'El periodo no está bloqueada.');
            }

            // Desbloquear la categoría
            $periodo->bloqueado_por = null;
            $periodo->fecha_actualizacion = now();
            $periodo->actualizado_por = Auth::user()->nombres;
            $periodo->fecha_bloqueo = null;

            // Guardar los cambios
            $periodo->save();

            return redirect()->route('periodos')->with('success', 'El periodo ha sido desbloqueada con éxito.');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('periodos')->with('error', 'Error al desbloquear el Periodo.');
        }
    }


    

}
