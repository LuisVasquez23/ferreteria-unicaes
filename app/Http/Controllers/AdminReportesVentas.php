<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminReportesVentas extends Controller
{
    public function index(Request $request)
{
    // Cargar los períodos desde el modelo Periodo
    $periodos = Periodo::whereNull('bloqueado_por')->pluck('fecha_inicio', 'periodo_id');
    // Recuperar las fechas seleccionadas del formulario
    $fechaInicio = $request->input('periodo_id_inicio');
    $fechaFin = $request->input('periodo_id_fin');

    // Realizar la consulta SQL utilizando las fechas seleccionadas para ventas
    $resultados = DB::table('ventas')
        ->select('ventas.numerosfactura', 'ventas.monto', 'usuarios.nombres', 'ventas.creado_por')
        ->join('periodos', 'ventas.periodo_id', '=', 'periodos.periodo_id')
        ->join('detalle_ventas', 'ventas.venta_id', '=', 'detalle_ventas.venta_id')
        ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.producto_id')
        ->join('usuarios', 'ventas.cliente_id', '=', 'usuarios.usuario_id')
        ->whereBetween('periodos.fecha_inicio', [$fechaInicio, $fechaFin])
        ->groupBy('ventas.numerosfactura', 'ventas.monto', 'usuarios.nombres', 'ventas.creado_por')
        ->get();

    return view('reporteVenta.index', ['resultados' => $resultados, 'periodos' => $periodos]);
}

public function pdf($num_factura)
{
    $resultados1 = DB::table('ventas')
        ->select('ventas.numerosfactura', 'ventas.monto', 'usuarios.nombres', 'usuarios.apellidos', 'periodos.fecha_inicio', 'ventas.creado_por')
        ->join('periodos', 'ventas.periodo_id', '=', 'periodos.periodo_id')
        ->join('detalle_ventas', 'ventas.venta_id', '=', 'detalle_ventas.venta_id')
        ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.producto_id')
        ->join('usuarios', 'ventas.cliente_id', '=', 'usuarios.usuario_id')
        ->where('ventas.numerosfactura', $num_factura)
        ->groupBy('ventas.numerosfactura', 'ventas.monto', 'usuarios.nombres', 'usuarios.apellidos', 'periodos.fecha_inicio', 'ventas.creado_por')
        ->get();

        $resultados2 = DB::table('ventas')
        ->select('productos.nombre', 'detalle_ventas.cantidad', 'detalle_ventas.precio', DB::raw('(detalle_ventas.cantidad * detalle_ventas.precio) AS total'), DB::raw('(detalle_ventas.cantidad * detalle_ventas.precio * 0.13) AS Iva'), DB::raw('(detalle_ventas.cantidad * detalle_ventas.precio + (detalle_ventas.cantidad * detalle_ventas.precio * 0.13)) AS TotalConIva'))
        ->join('periodos', 'ventas.periodo_id', '=', 'periodos.periodo_id')
        ->join('detalle_ventas', 'ventas.venta_id', '=', 'detalle_ventas.venta_id')
        ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.producto_id')
        ->join('usuarios', 'ventas.cliente_id', '=', 'usuarios.usuario_id')
        ->where('ventas.numerosfactura', $num_factura)
        ->get();
    

    $resultados3 = DB::table('catalogos')
        ->select('catalogos.valor')
        ->where('catalogos.nombre', 'NOMBRE_EMPRESA')
        ->get();

        $resultados4 = DB::table('catalogos')
        ->select('catalogos.valor')
        ->where('catalogos.nombre', 'LOGO_EMPRESA')
        ->first();

    $resultados5 = DB::table('ventas')
    ->selectRaw('SUM(detalle_ventas.cantidad * detalle_ventas.precio + (detalle_ventas.cantidad * detalle_ventas.precio * 0.13)) as totalMasIva')
    ->join('detalle_ventas', 'ventas.venta_id', '=', 'detalle_ventas.venta_id')
    ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.producto_id')
    ->where('ventas.numerosfactura', $num_factura)  
    ->value('totalMasIva');
    
    $totalVenta = DB::table('ventas')
    ->join('detalle_ventas', 'ventas.venta_id', '=', 'detalle_ventas.venta_id')
    ->where('ventas.numerosfactura', $num_factura) 
    ->select(DB::raw('SUM(detalle_ventas.precio * detalle_ventas.cantidad) AS total'))
    ->first();


    $imagePath = public_path('storage/upload/' . $resultados4->valor);
    $imageData = file_get_contents($imagePath);
    $base64Image = base64_encode($imageData);
    

    $data = [
        'resultados1' => $resultados1,
        'resultados2' => $resultados2,
        'resultados3' => $resultados3,
        'resultados4' => $base64Image,
        'resultados5' => $resultados5,
        'totalVenta' => $totalVenta,
    ];

    $pdf = app('dompdf.wrapper')->loadView('reporteVenta.ventaReporte', $data);
    return $pdf->stream();
}


}
