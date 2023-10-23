<?php

namespace App\Http\Controllers;

use App\Models\DetalleCompra;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Periodo;
use Carbon\Carbon;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        try {
            $diasVencimiento = 0;

            // Obtener todos los periodos
            $periodos = Periodo::all();
            
            // Obtener productos con relación a los periodos y detalles de compra
            $productos = Producto::with(['periodo', 'detalle_compras']);

            // Verificar si se ha enviado un filtro por periodo
            if ($request->has('periodo') && $request->input('periodo') !== 'Seleccionar...') {
                $periodoId = $request->input('periodo');
                $productos = $productos->whereHas('periodo', function ($query) use ($periodoId) {
                    $query->where('periodo_id', $periodoId);
                });
            }

            // Verificar si se ha enviado un filtro por nombre
            if ($request->has('nombre') && $request->input('nombre') !== 'Seleccionar...') {
                $nombre = $request->input('nombre');
                $productos = $productos->where('nombre', $nombre);
            }

            // Verificar si se ha enviado un filtro por fecha de vencimiento
            if ($request->has('vencimiento') && $request->input('vencimiento') !== 'Seleccionar...') {
                $vencimiento = $request->input('vencimiento');
                $productos = $productos->whereHas('detalle_compras', function ($query) use ($vencimiento) {
                    $query->whereDate('fecha_vencimiento', $vencimiento);
                });
            }

            // Obtener todos los nombres de productos
            $productosNombre = Producto::pluck('nombre')->unique();

            // Obtener todas las fechas de vencimiento sin repetirse
            $fechasVencimiento = DetalleCompra::whereNotNull('fecha_vencimiento')
                ->pluck('fecha_vencimiento')
                ->unique()
                ->map(function ($fecha) {
                    return Carbon::parse($fecha)->format('Y-m-d');
                });

            // Obtener los productos filtrados
            $productosFiltrados = $productos->get();

            $productosConPocaExistencia = $productosFiltrados->contains(function ($producto) {
                return $producto->cantidad <= 10;
            });
        
            $productosConVencimientoCercano = $productosFiltrados->contains(function ($producto) {
                // Verificar si algún detalle de compra cumple con la condición
                return $producto->detalle_compras->contains(function ($detalle) {
                    $diasVencimiento = $detalle->fecha_vencimiento ? now()->diffInDays($detalle->fecha_vencimiento, false) : null;
                    return $diasVencimiento !== null && $diasVencimiento <= 10;
                });
            });

            return view('inventario.index', compact('periodos', 'productosNombre', 'productosFiltrados', 'productosConPocaExistencia', 'productosConVencimientoCercano', 'fechasVencimiento', 'diasVencimiento'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('inventario')->with('error', 'Error al cargar la página de productos');
        }
    }
}
