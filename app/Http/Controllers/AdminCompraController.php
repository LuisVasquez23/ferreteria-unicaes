<?php

namespace App\Http\Controllers;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\DetalleCompra;
use App\Models\Periodo;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;


class AdminCompraController extends Controller
{
    public function index()
    {
        // Recuperar todas las compras
        $compras = Compra::with('detalle_compras')->get();


        // Devolver vista de lista de compras
        return view('compras.index', compact('compras'));
    }

    public function create()
    {
        try {
            // Obtener la lista de proveedores
            $proveedores = Usuario::whereHas('detalle_roles.role', function ($query) {
                $query->where('role', 'Proveedor');
            })->whereNull('bloqueado_por')
                ->pluck('nombres', 'usuario_id');
        
            // Obtener la lista de productos disponibles
            $productos = Producto::whereNull('bloqueado_por')
            ->with('usuario') // Carga la relación 'usuario'
            ->get();
        
            $categorias = Categoria::all(); // Asumiendo que tienes un modelo "Categoria"

            // Obtener la lista de periodos u otras necesidades si las tienes
            $periodos = Periodo::whereNull('bloqueado_por')->pluck('fecha_inicio', 'periodo_id');

            return view('compras.create', compact('proveedores', 'productos','periodos','categorias'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('compras')->with('error', 'Error al cargar la página de creación para compras');
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
                'totalFin' => 'required|numeric',
            ]);

            // Recuperar la lista de productos desde el campo oculto
            $listaProductos = json_decode($request->input('lista_productos'), true);

            // Iniciar una transacción de base de datos
            DB::beginTransaction();

            // Crear la compra
            $compra = new Compra();
            $compra->numerosfactura = $data['numero_factura'];
            $compra->periodo_id = $data['periodo_id'];
            $compra->comprador_id = Auth::user()->usuario_id;
            $compra->monto = $data['totalFin'];
            // Aquí debes establecer otros campos de la compra según tu estructura de datos
            $compra->save();
            //Obtener ID del usuario que se esta ingresando
            $compraId = $compra->compra_id;
            
            // Guardar los detalles de la compra
            foreach ($listaProductos as $producto) {
                $detalleCompra = new DetalleCompra();
                $detalleCompra->cantidad = $producto['cantidad'];
                $detalleCompra->numero_lote = $detalleCompra::max('numero_lote')+1;
                $detalleCompra->precioUnitario = $producto['precioUnitario'];
                $detalleCompra->producto_id = $producto['productoId'];
                $detalleCompra->compra_id = $compraId;
                // Aquí puedes establecer otros campos del detalle según tu estructura de datos
                $detalleCompra->save();
            }



            // Confirmar la transacción
            DB::commit();

            // Redirigir a la vista de éxito o a donde desees
            return redirect()->route('compras')->with('success', 'Compra creada exitosamente.');
        } catch (\Exception $e) {
            // En caso de error, revertir la transacción y manejar la excepción
            DB::rollBack();
            

            // Puedes registrar el error en los registros o mostrar un mensaje de error al usuario
            return redirect()->route('compras.create')->with('error', 'Error al crear la compra: ' . $e->getMessage());
        }
    }

    

  
}
