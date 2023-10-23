<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class AdminReporteDiario extends Controller
{
    public function index(Request $request)
    {

       $fechasDetalleVenta = DetalleVenta::select(DB::raw('DATE(created_at) as created_date'))
        ->distinct()
        ->groupBy(DB::raw('DATE(created_at)'))
        ->get();

    
    
        return view('reporteDiario.index', ['fechasDetalleVenta' => $fechasDetalleVenta]);


    }

    public function pdf($fechaInicio){
    $resultados = DB::table('detalle_ventas AS dv')
        ->select(
            DB::raw('DATE(dv.created_at) AS Fecha'),
            'p.nombre AS Nombre_Producto',
            DB::raw('SUM(dv.cantidad) AS Cantidad_Productos_Vendidos'),
            'dv.precio AS Precio_Unitario',
            DB::raw('SUM(dv.cantidad * dv.precio) as Monto_Total_Venta'),
            'p.cantidad AS Cantidad_Disponible'
        )
        ->join('productos AS p', 'dv.producto_id', '=', 'p.producto_id')
        ->whereDate('dv.created_at', '=', $fechaInicio)
        ->groupBy('Fecha', 'p.nombre', 'dv.precio', 'p.cantidad')
        ->orderBy('Fecha')
        ->get();

        $resultados3 = DB::table('catalogos')
        ->select('catalogos.valor')
        ->where('catalogos.nombre', 'NOMBRE_EMPRESA')
        ->get();

        $resultados4 = DB::table('catalogos')
        ->select('catalogos.valor')
        ->where('catalogos.nombre', 'LOGO_EMPRESA')
        ->first();

        $totalMonto = DB::table('detalle_ventas AS dv')
        ->join('productos AS p', 'dv.producto_id', '=', 'p.producto_id')
        ->whereDate('dv.created_at', $fechaInicio)
        ->sum(DB::raw('dv.cantidad * dv.precio'));

        $imagePath = public_path('storage/upload/' . $resultados4->valor);
        $imageData = file_get_contents($imagePath);
        $base64Image = base64_encode($imageData);
    


        $fechaInicio = Carbon::createFromFormat('Y-m-d', $fechaInicio);
            $data = [
                'resultados' => $resultados, 
                'resultados3' => $resultados3,
                'resultados4' => $base64Image,
                'fechaInicio' => $fechaInicio,
                'totalMonto' => $totalMonto,
            ];

    $pdf = app('dompdf.wrapper')->loadView('reporteDiario.reporteDiario', $data);
    return $pdf->stream();

}

    
    
}
