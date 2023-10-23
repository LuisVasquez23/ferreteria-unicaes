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
use PhpParser\Node\Expr\FuncCall;

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
        
            //Obtener clientes
                $clientes = Usuario::whereHas('detalle_roles.role', function ($query) {
                    $query->where('role', 'Cliente');
                })->whereNull('bloqueado_por')
                  ->pluck(DB::raw("CONCAT(nombres, ' ', apellidos)"), 'usuario_id');
                


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
            // Recuperar los datos de la solicitud
            $data = $request->validate([
                'numero_factura' => 'required|numeric',
                'periodo_id' => 'required|numeric',
                'totalMasIVA' => 'required|numeric',
                'cliente_id' => 'required|numeric',
            ]);
            // Comprobar si ya existe una venta con el mismo número de factura
            $ventaExistente = Venta::where('numerosfactura', $data['numero_factura'])->first();

            if ($ventaExistente) {
                return redirect()->route('ventas.create')->with('error', 'Ya existe una venta con el número de factura proporcionado. Por favor, ingrese un número de factura único.');
            }
            // Recuperar la lista de productos desde el campo oculto
            $listaProductos = json_decode($request->input('lista_productos'), true);
    
            // Iniciar una transacción de base de datos
            DB::beginTransaction();
    
            // Crear la venta
            $venta = new Venta();
            $venta->periodo_id = $data['periodo_id'];
            $venta->cliente_id = $data['cliente_id'];
            $venta->vendedor_id = Auth::user()->usuario_id;
            $venta->monto = $data['totalMasIVA'];
            $venta->numerosfactura = $data['numero_factura'];
            $venta->fecha_creacion = now();
            $venta->creado_por = Auth::user()->nombres;
            
            $venta->save();
            $ventaId = $venta->venta_id;
    
            // Recorrer la lista de productos y guardar detalles de compra
            foreach ($listaProductos as $producto) {
                $productoId = $producto['productoId'];
                $cantidadComprar = $producto['cantidad'];
    
                $detalleVenta = new DetalleVenta();
                $detalleVenta->cantidad = $cantidadComprar;
                $detalleVenta->precio = $producto['precioUnitario'];
                $detalleVenta->venta_id = $ventaId;
                $detalleVenta->producto_id = $productoId;
                $detalleVenta->numero_lote = $producto['numeroLote'];
                $detalleVenta->save();
            }
    
            // Confirmar la transacción
            DB::commit();
    
            // Redirigir a la vista de éxito o a donde desees
            return redirect()->route('ventas')->with('success', 'Venta creada exitosamente.');
        } catch (\Exception $e) {
            // En caso de error, revertir la transacción y manejar la excepción
            DB::rollBack();
            // Puedes registrar el error en los registros o mostrar un mensaje de error al usuario
            return redirect()->route('ventas.create')->with('error', 'Por favor, revisar los campos');
        }
    }
    
    //metodo para buscar cantidades en lotes y verificar si tenemos suficiente stock
    public function verificarCantidad(Request $request)
    {
        // Recupera los datos de la solicitud AJAX
        $productoId = $request->input('producto_id');
        $cantidad = $request->input('cantidad');
    
        // Realiza la lógica para verificar la cantidad de productos (puedes usar tu lógica actual aquí)
        $producto = Producto::find($productoId);
        $cantidadDisponible = $producto->cantidad;
    
        // Inicializa un array para los lotes disponibles
        $lotesDisponibles = [];
        $lotesVendidos = [];
        // Si la cantidad solicitada es menor o igual a la cantidad disponible
        if ($cantidad <= $cantidadDisponible) {
            
                $suficiente = true;
                    
                // Consultar la disponibilidad de lotes teniendo en cuenta compras y ventas
                    $lotesDisponibles = DB::select('CALL OBTENER_CANTIDAD_DISPONIBLE(?)', array($productoId));
                
                     // Calcular cuántos productos se venderán de cada lote
                    
                     $cantidadRestante = $cantidad;

                    foreach ($lotesDisponibles as $lote) {
                        
                        $numeroLote = $lote->NUMERO_LOTE;
                        $cantidadDisponible = $lote->CANTIDAD_DISPONIBLE;
                        $precioUnitario = $lote->PRECIOUNITARIO; // Precio unitario

                        $cantidadAVender = min($cantidadDisponible, $cantidadRestante);

                        $lotesVendidos[] = [
                            'numero_lote' => $numeroLote,
                            'cantidad_disponible' => $cantidadDisponible,
                            'cantidad_comprada' => $cantidadAVender,
                            'precio_unitario' => $precioUnitario, // Agregar precio unitario al array

                        ];

                        $cantidadRestante -= $cantidadAVender;
                        if ($cantidadRestante <= 0) {
                            break; // Detén el bucle si no se necesita más cantidad
                        }
                    }
        } else {
            // La cantidad solicitada es mayor que la cantidad disponible
            $lotesVendidos = $cantidadDisponible;
            // Obtener los lotes disponibles para el producto
    
    
            $suficiente = false;
        }
    
        // Devuelve una respuesta JSON con el resultado y los lotes disponibles si es necesario
        return response()->json(['suficiente' => $suficiente, 'lotesDisponibles' => $lotesVendidos]);
    }
   
}
