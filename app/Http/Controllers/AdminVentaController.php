<?php

namespace App\Http\Controllers;

use App\Models\Venta; // Asegúrate de importar el modelo Venta
use App\Models\Periodo;
use App\Models\Categoria;
use App\Models\DetalleCompra;
use App\Models\DetalleVenta;
use App\Models\Usuario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdminVentaController extends Controller
{
    public function index()
    {
        // Recuperar todas las ventas
        $ventas = Venta::with('detalle_ventas')->get();;

        // Devolver vista de lista de ventas
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        try {
            // Obtener la lista de proveedores
            $proveedores = Usuario::whereHas('detalle_roles.role', function ($query) {
                $query->where('role', 'Proveedor');
            })->whereNull('bloqueado_por')
                ->pluck('nombres', 'usuario_id');
            //obtener los clientes
            $clientes = Usuario::whereHas('detalle_roles.role', function ($query) {
                $query->where('role', 'Cliente');
            })->whereNull('bloqueado_por')
                ->pluck('nombres', 'usuario_id');


            // Obtener la lista de productos disponibles
            $productos = Producto::whereNull('bloqueado_por')
                ->with('usuario') // Carga la relación 'usuario'
                ->get();

            $categorias = Categoria::all(); // Asumiendo que tienes un modelo "Categoria"

            // Obtener la lista de periodos u otras necesidades si las tienes
            $periodos = Periodo::whereNull('bloqueado_por')->pluck('fecha_inicio', 'periodo_id');


            // Devolver vista de creación de venta
            return view('ventas.create', compact('proveedores', 'productos', 'periodos', 'categorias', 'clientes'));
        } catch (\Throwable $th) {
            return redirect()->route('ventas.create')->with('error', 'Por favor, verificar los campos');
        }
    }


    public function store(Request $request)
    {
        try {

            // Lógica para guardar la compra y sus detalles en la base de datos
            // Recuperar los datos del formulario
            $data = $request->validate([
                'numero_factura' => 'required|numeric',
                'periodo_id' => 'required|numeric',
                'totalMasIVA' => 'required|numeric',
                'cliente_id' => 'required|numeric',
            ]);

            // Recuperar la lista de productos desde el campo oculto
            $listaProductos = json_decode($request->input('lista_productos'), true);




            // Iniciar una transacción de base de datos
            DB::beginTransaction();

            // Crear la compra
            $venta = new Venta();
            $venta->periodo_id = $data['periodo_id'];
            $venta->cliente_id = $data['cliente_id'];
            $venta->vendedor_id = Auth::user()->usuario_id;
            $venta->monto = $data['totalMasIVA'];
            $venta->numerosfactura = $data['numero_factura'];

            // Aquí debes establecer otros campos de la compra según tu estructura de datos
            $venta->save();
            // Obtener ID de la venta que se está creando
            $ventaId = $venta->venta_id;

            // Recorrer la lista de productos y guardar detalles de compra
            foreach ($listaProductos as $producto) {
                $productoId = $producto['productoId'];
                $cantidadComprar = $producto['cantidad'];

                $productoDis = Producto::find($productoId);
                $cantidad = $productoDis->cantidad;
                $nombre =  $productoDis->nombre;

                if ($cantidadComprar > $cantidad) {
                    DB::rollBack();
                    return redirect()->route('ventas.create')->with('error', 'No existe suficiente stock de: ' . $nombre);
                }


                // Obtener los lotes disponibles para el producto
                // Consultar la disponibilidad de lotes teniendo en cuenta compras y ventas
                $lotesDisponibles = DB::table('detalle_compras AS dc')
                    ->select('dc.numero_lote', DB::raw('SUM(dc.cantidad) - COALESCE(SUM(dv.cantidad), 0) AS cantidad_disponible'))
                    ->leftJoin('detalle_ventas AS dv', function ($join) use ($productoId) {
                        $join->on('dc.numero_lote', '=', 'dv.numero_lote')
                            ->where('dv.producto_id', '=', $productoId);
                    })
                    ->where('dc.producto_id', $productoId)
                    ->groupBy('dc.numero_lote')
                    ->havingRaw('cantidad_disponible > 0')
                    ->orderBy('dc.numero_lote')
                    ->get();

                // Inicializar un array para almacenar los lotes disponibles para la compra
                $lotesParaCompra = [];

                // Calcular la cantidad disponible en los lotes para la compra
                foreach ($lotesDisponibles as $lote) {
                    $cantidadDisponible = $lote->cantidad_disponible;
                    if ($cantidadComprar > 0 && $cantidadDisponible > 0) {
                        // Verificar si la cantidad a comprar cabe en el lote
                        $cantidadAComprar = min($cantidadComprar, $cantidadDisponible);
                        if ($cantidadAComprar <= $cantidadDisponible) {
                            $lotesParaCompra[] = [
                                'numero_lote' => $lote->numero_lote,
                                'cantidad_a_comprar' => $cantidadAComprar,
                            ];
                            $cantidadComprar -= $cantidadAComprar;
                        } else {
                            // La cantidad a comprar no cabe en el lote, maneja el error aquí si es necesario
                            DB::rollBack();
                            return redirect()->route('ventas.create')->with('error', 'La cantidad a comprar no cabe en el lote.');
                        }
                    }
                }

                // Guardar los detalles de compra para los lotes disponibles
                foreach ($lotesParaCompra as $loteCompra) {
                    $detalleVenta = new DetalleVenta();
                    $detalleVenta->cantidad = $loteCompra['cantidad_a_comprar'];
                    $detalleVenta->numero_lote = $loteCompra['numero_lote'];
                    $detalleVenta->precio = $producto['precioUnitario'];
                    $detalleVenta->venta_id = $ventaId;
                    $detalleVenta->producto_id = $productoId;
                    // Aquí puedes establecer otros campos del detalle según tu estructura de datos
                    $detalleVenta->save();
                }
            }

            // Confirmar la transacción
            DB::commit();

            // Redirigir a la vista de éxito o a donde desees
            return redirect()->route('ventas')->with('success', 'Compra creada exitosamente.');
        } catch (\Exception $e) {
            // En caso de error, revertir la transacción y manejar la excepción
            DB::rollBack();
            // Puedes registrar el error en los registros o mostrar un mensaje de error al usuario
            return redirect()->route('ventas.create')->with('error', 'Por favor, revisar los campos');
        }
    }
}
