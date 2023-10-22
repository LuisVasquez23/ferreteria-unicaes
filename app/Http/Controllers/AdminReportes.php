<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function pdf($num_factura)
    {
        $resultados1 = DB::table('compras')
            ->select('compras.numerosfactura', 'compras.monto', 'usuarios.nombres', 'usuarios.apellidos', 'periodos.fecha_inicio', 'compras.creado_por')
            ->join('periodos', 'compras.periodo_id', '=', 'periodos.periodo_id')
            ->join('detalle_compras', 'compras.compra_id', '=', 'detalle_compras.compra_id')
            ->join('productos', 'detalle_compras.producto_id', '=', 'productos.producto_id')
            ->join('usuarios', 'compras.comprador_id', '=', 'usuarios.usuario_id')
            ->where('compras.numerosfactura', $num_factura)
            ->groupBy('compras.numerosfactura', 'compras.monto', 'usuarios.nombres', 'usuarios.apellidos', 'periodos.fecha_inicio', 'compras.creado_por')
            ->get();

        $resultados2 = DB::table('compras')
            ->select('productos.nombre', 'detalle_compras.cantidad', 'detalle_compras.precioUnitario', DB::raw('(detalle_compras.cantidad * detalle_compras.precioUnitario) AS total'), DB::raw('(detalle_compras.cantidad * detalle_compras.precioUnitario * 0.13) AS Iva'), DB::raw('(detalle_compras.cantidad * detalle_compras.precioUnitario + (detalle_compras.cantidad * detalle_compras.precioUnitario * 0.13)) AS TotalConIva'))
            ->join('periodos', 'compras.periodo_id', '=', 'periodos.periodo_id')
            ->join('detalle_compras', 'compras.compra_id', '=', 'detalle_compras.compra_id')
            ->join('productos', 'detalle_compras.producto_id', '=', 'productos.producto_id')
            ->join('usuarios', 'compras.comprador_id', '=', 'usuarios.usuario_id')
            ->where('compras.numerosfactura', $num_factura)
            ->get();

        $resultados3 = DB::table('catalogos')
            ->select('catalogos.valor')
            ->where('catalogos.nombre', 'NOMBRE_EMPRESA')
            ->get();


        $resultados4 = DB::table('catalogos')
            ->select('catalogos.valor')
            ->where('catalogos.nombre', 'LOGO_EMPRESA')
            ->first();

        $resultados5 = DB::table('compras')
            ->selectRaw('SUM(detalle_compras.cantidad * detalle_compras.precioUnitario + (detalle_compras.cantidad * detalle_compras.precioUnitario * 0.13)) as totalMasIva')
            ->join('detalle_compras', 'compras.compra_id', '=', 'detalle_compras.compra_id')
            ->join('productos', 'detalle_compras.producto_id', '=', 'productos.producto_id')
            ->where('compras.numerosfactura', $num_factura)
            ->get();

        $imagePath = public_path('storage/upload/' . $resultados4->valor);
        $imageData = file_get_contents($imagePath);
        $base64Image = base64_encode($imageData);

        $data = [
            'resultados1' => $resultados1,
            'resultados2' => $resultados2,
            'resultados3' => $resultados3,
            'resultados4' => $base64Image,
            'resultados5' => $resultados5,
        ];

        $pdf = Pdf::loadView('reportes.compraReporte', $data);

        return $pdf->stream();
    }
}
