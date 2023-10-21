<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection; // Importa la clase Collection

class AdminReportes extends Controller
{
  public function index(Request $request)
{
    // Cargar los períodos desde el modelo Periodo
    $periodos = Periodo::whereNull('bloqueado_por')->pluck('fecha_inicio', 'periodo_id');
    // Recuperar las fechas seleccionadas del formulario
    $fechaInicio = $request->input('periodo_id_inicio');
    $fechaFin = $request->input('periodo_id_fin');
   


    // Realizar la consulta SQL utilizando las fechas seleccionadas
    $resultados = DB::table('compras')
        ->select('compras.numerosfactura', 'compras.monto', 'usuarios.nombres', 'periodos.fecha_inicio', 'compras.creado_por')
        ->join('periodos', 'compras.periodo_id', '=', 'periodos.periodo_id')
        ->join('detalle_compras', 'compras.compra_id', '=', 'detalle_compras.compra_id')
        ->join('productos', 'detalle_compras.producto_id', '=', 'productos.producto_id')
        ->join('usuarios', 'compras.comprador_id', '=', 'usuarios.usuario_id')
        ->whereBetween('periodos.fecha_inicio', [$fechaInicio, $fechaFin])
        ->groupBy('compras.numerosfactura', 'compras.monto', 'usuarios.nombres', 'periodos.fecha_inicio', 'compras.creado_por')

        ->get();


    return view('reportes.index', ['resultados' => $resultados, 'periodos' => $periodos]);
    
}

}








