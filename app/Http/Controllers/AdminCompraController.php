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

            return view('compras.create', compact('proveedores', 'productos', 'periodos', 'categorias'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('compras')->with('error', 'Error al cargar la página de creación para compras');
        }
    }


    public function store(Request $request)
    {
        try {


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
            $listaProductos = json_decode($request->input('lista_productos'), true);


            foreach ($listaProductos as $producto) {
                $detalleCompra = new DetalleCompra();
                $detalleCompra->cantidad = $producto['cantidad'];
                $detalleCompra->numero_lote = DetalleCompra::max('numero_lote') + 1;
                $detalleCompra->precioUnitario = $producto['precioUnitario'];
                $detalleCompra->producto_id = $producto['productoId'];
                $detalleCompra->compra_id = $compraId;
                $detalleCompra->fecha_vencimiento = $producto['fechaVencimiento'] ?? null;
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
