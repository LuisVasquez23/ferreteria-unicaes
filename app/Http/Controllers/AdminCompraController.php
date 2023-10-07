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
use Illuminate\Support\Facades\Validator;



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
         
                // Define las reglas de validación
                $rules = [
                    'numerosfactura' => 'required|numeric|unique:compras',
                    'periodo_id' => 'required|numeric',
                    'producto_id' => 'required|numeric',
                   'cantidad' => 'required|numeric|min:1',
                    'precio_unitario' => 'required|numeric|min:0.01',
                    'fecha_vencimiento' => 'required|date',
                ];

                // Define los mensajes de error personalizados en español
                $messages = [
                    'numerosfactura.required' => 'El campo "Número de Factura" es obligatorio.',
                    'numerosfactura.numeric' => 'El campo "Número de Factura" debe ser un número.',
                    'numerosfactura.unique' => 'El campo "Número de Factura" debe ser un unico.',
                    'periodo_id.required' => 'El campo "Período" es obligatorio.',
                    'periodo_id.numeric' => 'El campo "Período" debe ser un número.',
                    'producto_id.required' => 'El campo "Producto" es obligatorio.',
                    'producto_id.numeric' => 'El campo "Producto" debe ser un número.',
                    'cantidad.required' => 'El campo "Cantidad" es obligatorio.',
                    'cantidad.numeric' => 'El campo "Cantidad" debe ser un número.',
                    'cantidad.min' => 'El campo "Cantidad" debe ser mayor o igual a 1.',
                    'precio_unitario.required' => 'El campo "Precio Unitario" es obligatorio.',
                    'precio_unitario.numeric' => 'El campo "Precio Unitario" debe ser un número.',
                    'precio_unitario.min' => 'El campo "Precio Unitario" debe ser mayor o igual a 0.01.',
                   'fecha_vencimiento.required' => 'El campo "Fecha de Vencimiento" es obligatorio.',
                    'fecha_vencimiento.date' => 'El campo "Fecha de Vencimiento" debe ser una fecha válida.',
                ];

            
                // Realiza la validación
                $validator = Validator::make($request->all(), $rules, $messages);

                
                if ($validator->fails()) {
                    return redirect()
                        ->route('compras.create')
                        ->withErrors($validator)
                        ->withInput();
                }
            // Create the compra record
            $compra = new Compra();
            $compra->numerosfactura = $request->input('numerosfactura');
            $compra->periodo_id = $request->input('periodo_id');
            $compra->comprador_id = Auth::user()->usuario_id;
            $compra->monto = $request->input('totalFin');
            $compra->fecha_creacion = now();
            $compra->creado_por = Auth::user()->nombres;
            // Set other compra fields as needed
            $compra->save();

            $compraId = $compra->compra_id;

            // Create and save detalles de compra
            $listaProductos = $request->input('lista_productos');
            var_dump($listaProductos);
            foreach ($listaProductos as $producto) {
                $detalleCompra = new DetalleCompra();
                $detalleCompra->cantidad = $producto['cantidad'];
                $detalleCompra->numero_lote = DetalleCompra::max('numero_lote') + 1;
                $detalleCompra->precioUnitario = $producto['precioUnitario'];
                $detalleCompra->producto_id = $producto['productoId'];
                $detalleCompra->compra_id = $compraId;
                $detalleCompra->fecha_vencimiento = $producto['fechaVencimiento'];
                // Set other detalle fields as needed
                $detalleCompra->save();
            }

            // Commit the database transaction
            DB::commit();

            // Redirect to a success view or wherever you want
            return redirect()->route('compras')->with('success', 'Compra creada exitosamente.');
        } catch (\Exception $e) {
            // In case of an error, roll back the transaction and handle the exception
            DB::rollBack();
        
            // You can log the error or show an error message to the user
            return redirect()->route('compras.create')->with('error', 'Error al crear la compra: ' . $e->getMessage());
        }
    }


        

    
 }
