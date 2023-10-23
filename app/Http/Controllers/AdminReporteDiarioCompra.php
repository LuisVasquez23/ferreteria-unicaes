<?php

namespace App\Http\Controllers;

use App\Models\DetalleCompra;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReporteDiarioCompra extends Controller
{
    public function index(Request $request){

        $fechasDetalleCompra = DetalleCompra::select(DB::raw('DATE(created_at) as created_date'))
        ->distinct()
        ->groupBy(DB::raw('DATE(created_at)'))
        ->get();


        return view('reporteDiarioCompra.index', ['fechasDetalleCompra' => $fechasDetalleCompra]);
    }

    public function pdf($fechaInicio) {
        $resultados = DB::table('detalle_compras AS dc')
            ->select(
                DB::raw('DATE(dc.created_at) AS Fecha'),
                'p.nombre AS Nombre_Producto',
                DB::raw('SUM(dc.cantidad) AS Cantidad_Productos_Comprados'),
                'dc.precioUnitario AS Precio_Unitario',
                DB::raw('SUM(dc.cantidad * dc.precioUnitario) as Monto_Total_Compra'),
                'p.cantidad AS stock'
            )
            ->join('productos AS p', 'dc.producto_id', '=', 'p.producto_id')
            ->whereDate('dc.created_at', '=', $fechaInicio)
            ->groupBy('Fecha', 'p.nombre', 'dc.precioUnitario', 'p.cantidad')
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
    
        $totalMonto = DB::table('detalle_compras AS dc')
            ->join('productos AS p', 'dc.producto_id', '=', 'p.producto_id')
            ->whereDate('dc.created_at', $fechaInicio)
            ->sum(DB::raw('dc.cantidad * dc.precioUnitario'));
    
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
    
        $pdf = app('dompdf.wrapper')->loadView('reporteDiarioCompra.CompraReporte', $data);
        return $pdf->stream();
    }
    
}
