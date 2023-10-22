<?php

namespace App\Http\Controllers;

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
            // Obtener todos los periodos
            $periodos = Periodo::all();

            // Obtener productos con relación a los periodos
            $productos = Producto::with('periodo');

            // Verificar si se ha enviado un filtro por periodo
            if ($request->has('periodo') && $request->input('periodo') !== 'Seleccionar...') {
                $periodoId = $request->input('periodo');
                $productos->where('periodo_id', $periodoId);
            }

            // Obtener todos los nombres de productos
            $productosNombre = Producto::pluck('nombre')->unique();

            // Obtener todas las fechas de vencimiento sin repetirse
            $fechasVencimiento = Producto::whereNotNull('fecha_vencimiento')
                ->pluck('fecha_vencimiento')
                ->unique()
                ->map(function ($fecha) {
                    return Carbon::parse($fecha)->format('Y-m-d');
                });

            // Verificar si se ha enviado un filtro por nombre
            if ($request->has('nombre') && $request->input('nombre') !== 'Seleccionar...') {
                $nombre = $request->input('nombre');
                $productos->where('nombre', $nombre);
            }

            // Verificar si se ha enviado un filtro por fecha de vencimiento
            if ($request->has('vencimiento') && $request->input('vencimiento') !== 'Seleccionar...') {
                $vencimiento = $request->input('vencimiento');
                $productos->whereDate('fecha_vencimiento', $vencimiento);
            }

            $productosFiltrados = $productos->get();

            return view('inventario.index', compact('productosFiltrados', 'periodos', 'productosNombre', 'fechasVencimiento'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('inventario')->with('error', 'Error al cargar la página de productos');
        }
    }
}