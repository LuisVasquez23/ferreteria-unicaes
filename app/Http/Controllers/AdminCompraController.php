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
            $productos = Producto::whereNull('bloqueado_por')->get(['producto_id', 'nombre', 'precio']);

            $categorias = Categoria::all(); // Asumiendo que tienes un modelo "Categoria"

            // Obtener la lista de periodos u otras necesidades si las tienes
            $periodos = Periodo::whereNull('bloqueado_por')->pluck('fecha_inicio', 'periodo_id');

            return view('compras.create', compact('proveedores', 'productos','periodos','categorias'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            dd($e);
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
                'proveedor_id' => 'required|numeric',
              
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
            $compra->vendedor_id = $data['proveedor_id'];
            $compra->cliente_id = Auth::user()->usuario_id;
            $compra->monto = $data['totalFin'];
            // Aquí debes establecer otros campos de la compra según tu estructura de datos
            $compra->save();
            //Obtener ID del usuario que se esta ingresando
            $compraId = $compra->compra_id;
            
            // Guardar los detalles de la compra
            foreach ($listaProductos as $producto) {
                $detalleCompra = new DetalleCompra();
                $detalleCompra->cantidad = $producto['cantidad'];
                $detalleCompra->precioUnitario = $producto['precioUnitario'];
                $detalleCompra->producto_id = $producto['productoId'];
                $detalleCompra->compra_id = $compraId;
                $producto = Producto::find($producto['productoId']);
                //actualizar cantidad de producto
                if (!$producto) {
    
                    return redirect()->route('compras.create')->with('error', 'Producto no encontrado');
                }
                $nuvacantidas = $producto['cantidad'] + $producto->cantidad;
                $producto->cantidad = $nuvacantidas;
                $producto->actualizado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
                $producto->save();
                // Aquí puedes establecer otros campos del detalle según tu estructura de datos
                $detalleCompra->save();
            }



            // Confirmar la transacción
            DB::commit();

            // Redirigir a la vista de éxito o a donde desees
            return redirect()->route('compras.create')->with('success', 'Compra creada exitosamente.');
        } catch (\Exception $e) {
            // En caso de error, revertir la transacción y manejar la excepción
            DB::rollBack();
            dd($e);

            // Puedes registrar el error en los registros o mostrar un mensaje de error al usuario
            return redirect()->route('compras.create')->with('error', 'Error al crear la compra: ' . $e->getMessage());
        }
    }

    

    public function edit($id)
    {
        // Buscar la compra por ID
        $compra = Compra::find($id);

        // Devolver vista de edición de compra
        return view('compras.edit', compact('compra'));
    }

    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'monto' => 'required|numeric',
            'periodo_id' => 'required|integer',
            'cliente_id' => 'required|integer',
        ]);

        // Buscar la compra por ID
        $compra = Compra::find($id);

        // Actualizar los campos de la compra
        $compra->monto = $request->input('monto');
        $compra->periodo_id = $request->input('periodo_id');
        $compra->cliente_id = $request->input('cliente_id');
        $compra->save();

        // Redireccionar a la lista de compras o a donde desees
        return redirect()->route('compras');
    }
    public function destroy($id)
    {
        try {
            $action = request()->input('action');

            if ($action === 'update') {
                $compra = Compra::find($id);

                if (!$compra) {
                    return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
                }

                $compra->bloqueado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
                $compra->fecha_bloqueo = now();

                $compra->save();

                return redirect()->route('compras')->with('success', 'La compra se ha bloqueado con éxito.');
            }
        } catch (QueryException $e) {
            // Manejo de excepciones SQL
            Log::error($e->getMessage());
            return redirect()->route('compras')->with('error', 'Error de base de datos al bloquear la compra');
        } catch (\Exception $e) {
            // Manejo de otras excepciones
            Log::error($e->getMessage());
            return redirect()->route('compras')->with('error', 'Error al bloquear la compra');
        }
    }

    public function unblock($id)
    {
        try {
            $compra = Compra::find($id);

            // Verificar si la compra está bloqueada
            if (!$compra->bloqueado_por) {
                return redirect()->route('compras')->with('error', 'La compra no está bloqueada.');
            }

            // Desbloquear la compra
            $compra->bloqueado_por = null;
            $compra->fecha_actualizacion = now();
            $compra->actualizado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
            $compra->fecha_bloqueo = null;

            // Guardar los cambios
            $compra->save();

            return redirect()->route('compras')->with('success', 'La compra ha sido desbloqueada con éxito.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('compras')->with('error', 'Error al desbloquear la compra.');
        }
    }
}
