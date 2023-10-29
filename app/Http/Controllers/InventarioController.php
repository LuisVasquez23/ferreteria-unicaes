<?php

namespace App\Http\Controllers;

use App\Models\DetalleCompra;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Periodo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Obtener todos los periodos
            $periodos = Periodo::all();

            // Obtener una consulta base de productos con las relaciones necesarias
            $productos = Producto::with(['periodo', 'detalle_compras', 'estante', 'medida', 'detalle_ventas']);

            // Filtrar productos
            $productosFiltrados = $productos->when($request->filled('periodo') && $request->input('periodo') !== 'MostrarTodos', function ($query) use ($request) {
                return $query->whereHas('periodo', function ($subquery) use ($request) {
                    $subquery->where('periodo_id', $request->input('periodo'));
                });
            })->when($request->filled('nombre') && $request->input('nombre') !== 'MostrarTodos', function ($query) use ($request) {
                return $query->where('nombre', $request->input('nombre'));
            })->when($request->filled('vencimiento') && $request->input('vencimiento') !== 'MostrarTodos', function ($query) use ($request) {
                return $query->whereHas('detalle_compras', function ($subquery) use ($request) {
                    $subquery->whereNotNull('fecha_vencimiento')->whereDate('fecha_vencimiento', $request->input('vencimiento'));
                });
            })->get();

            // Obtener todos los nombres de productos
            $productosNombre = Producto::pluck('nombre')->unique();

            // Obtener todas las fechas de vencimiento sin repetirse
            $fechasVencimiento = DetalleCompra::whereNotNull('fecha_vencimiento')
                ->pluck('fecha_vencimiento')
                ->unique()
                ->map(function ($fecha) {
                    return Carbon::parse($fecha)->format('Y-m-d');
                });

            // Verificar existencia y vencimiento cercano
            $productosConPocaExistencia = $productosFiltrados->contains(function ($producto) {
                return $producto->cantidad <= 10;
            });

            $productosConVencimientoCercano = $productosFiltrados->contains(function ($producto) {
                return $producto->detalle_compras->contains(function ($detalle) {
                    $diasVencimiento = optional($detalle->fecha_vencimiento)->diffInDays(now(), false);
                    return $diasVencimiento !== null && $diasVencimiento <= 10;
                });
            });

            // Obtener todos los detalles de compras asociados a los productos filtrados
            $detallesCompras = DetalleCompra::whereIn('producto_id', $productosFiltrados->pluck('producto_id'))->get();
            $detalleVentas = DetalleVenta::whereIn('producto_id', $productosFiltrados->pluck('producto_id'))->get();

            // Comenntario 
            return view('Inventario.index', compact('periodos', 'productosNombre', 'productosFiltrados', 'productosConPocaExistencia', 'productosConVencimientoCercano', 'fechasVencimiento', 'detallesCompras', 'detalleVentas'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            dd($e->getMessage());
            return redirect()->route('Inventario')->with('error', 'Error al cargar la página de productos');
        }
    }

    public function validarCantidadProductos()
    {
        $productos = Producto::with('periodo')->get();
        $advertenciaProductos = $productos->filter(function ($producto) {
            return $producto->cantidad <= 10;
        });

        if ($advertenciaProductos->isNotEmpty()) {
            return new JsonResponse([
                'advertencia' => 'Hay productos con baja existencia en el inventario.',
                'productosAdvertencia' => $advertenciaProductos
            ], 200); // Usamos un código de respuesta 400 para indicar un error.
        }

        return new JsonResponse([
            'mensaje' => 'Todos los productos están dentro de los límites adecuados.'
        ], 200); // Usamos un código de respuesta 200 para indicar éxito.
    }
}
