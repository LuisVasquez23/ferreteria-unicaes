<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Estante;
use App\Models\Periodo;
use App\Models\Producto;
use App\Models\Unidad_Medida;
use App\Models\Usuario;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Compra;
use App\Models\DetalleCompra;
use Illuminate\Support\Facades\Storage;

class AdminProductoController extends Controller
{

    public function index(Request $request)
    {
        try {


            $filtro = $request->input('filtro', 'no-bloqueados');

            if ($filtro === 'bloqueados') {
                $productos = Producto::whereNotNull('bloqueado_por')->get();
            } else {
                $productos = Producto::whereNull('bloqueado_por')->get();
            }

            return view('productos.index', compact('productos', 'filtro'));
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return redirect()->route('productos')->with('error', 'Error al cargar la página de productos');
        }
    }


    public function create()
    {
        try {

            //Proveedores de la db
            $proveedores = Usuario::whereHas('detalle_roles.role', function ($query) {
                $query->where('role', 'Proveedor');
            })->whereNull('bloqueado_por')
                ->pluck('nombres', 'usuario_id');

            //categorias de la db

            $categorias = Categoria::whereNull('bloqueado_por')->pluck('categoria', 'categoria_id');

            //estantes de la db
            $estantes = Estante::whereNull('bloqueado_por')->pluck('estante', 'estante_id');

            // //unidades de la db

            $unidades = Unidad_Medida::whereNull('bloqueado_por')->pluck('nombre', 'unidad_medida_id');


            // //periodos de la db

            $periodos = Periodo::whereNull('bloqueado_por')->pluck('fecha_inicio', 'periodo_id');




            return view('productos.create', compact('proveedores', 'categorias', 'estantes', 'unidades', 'periodos'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('productos')->with('error', 'Error al cargar la página de creacion para productos');
        }
    }


    public function store(Request $request)
    {
        try {
            // Define las reglas de validación
            $rules = [
                'nombre_opcion' => 'required|unique:productos,nombre',
                'descripcion_opcion' => 'required',
                'imagenProducto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            ];

            $messages = [
                'nombre_opcion.required' => 'El campo "nombre" es obligatorio.',
                'nombre_opcion.unique' => 'El producto ingresado ya está registrado, intentelo de nuevo.',
                'descripcion_opcion.required' => 'El campo "descripción" es obligatorio.',
                'imagenProducto.required' => 'El campo "imagen" es obligatorio.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()
                    ->route('producto.create')
                    ->withErrors($validator)
                    ->withInput();
            }

            // Iniciar una transacción de base de datos
            DB::beginTransaction();

            $producto = new Producto();

            $producto->nombre = $request->input('nombre_opcion');
            $producto->descripcion = $request->input('descripcion_opcion');
            $producto->cantidad = 0;
            $producto->proveedor_id = $request->input('usuario_id');
            $producto->categoria_id = $request->input('categoria_id');
            $producto->estante_id = $request->input('estante_id');
            $producto->unidad_medida_id = $request->input('unidad_medida_id');
            $producto->periodo_id = $request->input('periodo_id');

            $producto->creado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;
            $producto->fecha_creacion = now();

            // Sube la imagen del producto a la ubicación deseada
            $imagenProducto = $request->file('imagenProducto');
            $imagenProductoExtension = $imagenProducto->getClientOriginalExtension();
            $nombreImagen = $request->input('nombre_opcion') . '_' . $request->input('categoria_id') . '_' . $request->input('estante_id') . '.' . $imagenProductoExtension;
            $imagenProductoName = 'public/upload/productos/' . str_replace(' ', '', $nombreImagen);

            Storage::put($imagenProductoName, file_get_contents($imagenProducto));

            // Actualiza el campo de imagen en la tabla de productos
            // Tendra el siguiente formato: PRODUCTO_1_1.jpeg
            $producto->img_path =  str_replace(' ', '', $nombreImagen);
            $producto->save();

            // Confirmar la transacción
            DB::commit();

            return redirect()->route('productos')->with('success', 'El producto se ha agregado con éxito.');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            return redirect()->route('productos')->with('error', 'Sucedio un error al ingresar el producto, todos los campos deben ser correctos');
        }
    }



    public function edit(string $id)
    {

        try {

            //Proveedores de la db
            $proveedores = Usuario::whereHas('detalle_roles', function ($query) {
                $query->where('role_id', 5);
            })->whereNull('bloqueado_por')->pluck('nombres', 'usuario_id');

            //categorias de la db

            $categorias = Categoria::whereNull('bloqueado_por')->pluck('categoria', 'categoria_id');

            //estantes de la db
            $estantes = Estante::whereNull('bloqueado_por')->pluck('estante', 'estante_id');

            // //unidades de la db

            $unidades = Unidad_Medida::whereNull('bloqueado_por')->pluck('nombre', 'unidad_medida_id');


            // //periodos de la db

            $periodos = Periodo::whereNull('bloqueado_por')->pluck('fecha_inicio', 'periodo_id');


            $producto = Producto::find($id);

            // Verifica si el registro existe
            if (!$producto) {
                return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
            }

            return view('productos.edit', compact('producto', 'proveedores', 'categorias', 'estantes', 'unidades', 'periodos'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('productos')->with('error', 'Error al cargar la página para editar el producto');
        }
    }


    public function update(Request $request, string $id)
    {
        try {
            $producto = Producto::find($id);

            if (!$producto) {
                return redirect()->route('productos')->with('error', 'Producto no encontrado');
            }

            // // Define las reglas de validación
            $rules = [
                'nombre_opcion' => 'required|unique:productos,nombre,' . $id . ',producto_id',
                'descripcion_opcion' => 'required',
                'imagenProducto' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            ];

            $messages = [
                'nombre_opcion.required' => 'El campo "nombre" es obligatorio.',
                'nombre_opcion.unique' => 'El producto ya está registrado, prueba con otro.',
                'descripcion_opcion.required' => 'El campo "descripción" es obligatorio.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()
                    ->route('productos.edit', ['id' => $id])
                    ->withErrors($validator)
                    ->withInput();
            }

            // Actualiza los campos del producto
            $producto->nombre = $request->input('nombre_opcion');
            $producto->descripcion = $request->input('descripcion_opcion');
            $producto->proveedor_id = $request->input('usuario_id');
            $producto->categoria_id = $request->input('categoria_id');
            $producto->estante_id = $request->input('estante_id');
            $producto->unidad_medida_id = $request->input('unidad_medida_id');
            $producto->periodo_id = $request->input('periodo_id');

            $producto->actualizado_por = Auth::user()->nombres . ' ' . Auth::user()->apellidos;

            // Actualiza la imagen del producto si se proporciona una nueva
            if ($request->hasFile('imagenProducto')) {
                $imagenProducto = $request->file('imagenProducto');
                $imagenProductoExtension = $imagenProducto->getClientOriginalExtension();
                $imagenProductoName = 'public/upload/productos/' . $producto->id . '.' . $imagenProductoExtension;

                // Elimina el archivo existente si existe
                if (Storage::exists($producto->imagen)) {
                    Storage::delete($producto->imagen);
                }

                // Sube la nueva imagen
                Storage::put($imagenProductoName, file_get_contents($imagenProducto));

                // Actualiza el campo de imagen en la tabla de productos
                $producto->imagen = $imagenProductoName;
            }

            $producto->save();

            return redirect()->route('productos')->with('success', 'Producto actualizado con éxito.');
        } catch (\Throwable $th) {
            return redirect()->route('productos')->with('error', 'Sucedio un error al actualizar el producto, todos los campos deben ser correctos' . $th->getMessage());
        }
    }



    public function destroy(string $id)
    {
        try {

            $action = request()->input('action');

            if ($action === 'update') {

                $producto = Producto::find($id);

                if (!$producto) {
                    return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
                }

                $producto->bloqueado_por = Auth::user()->nombres;
                $producto->fecha_bloqueo = now();

                $producto->save();

                return redirect()->route('productos')->with('success', 'El registro se ha bloqueado con éxito.');
            }
        } catch (QueryException $e) {
            // Manejo de excepciones SQL
            Log::error($e->getMessage());
            return redirect()->route('productos')->with('error', 'Error de base de datos al eliminar el producto');
        } catch (\Exception $e) {
            // Manejo de otras excepciones
            Log::error($e->getMessage());
            return redirect()->route('productos')->with('error', 'Error al bloquear el producto');
        }
    }




    public function unblock($id)
    {
        try {

            $producto = Producto::find($id);

            // Verificar si el producto está bloqueado
            if (!$producto->bloqueado_por) {
                return redirect()->route('productos')->with('error', 'El producto no está bloqueado.');
            }

            // Desbloquear al cliente
            $producto->bloqueado_por = null;
            $producto->fecha_actualizacion = now();
            $producto->actualizado_por = Auth::user()->nombres;
            $producto->fecha_bloqueo = null;


            //Guardo los cambios
            $producto->save();

            return redirect()->route('productos')->with('success', 'El producto ha sido desbloqueado con éxito.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('productos')->with('error', 'Error al desbloquear el producto.');
        }
    }

    public function detail(string $id)
    {

        try {


            //Proveedores de la db
            $proveedores = Usuario::whereHas('detalle_roles', function ($query) {
                $query->where('role_id', 5);
            })->whereNull('bloqueado_por')->pluck('nombres', 'usuario_id');

            //categorias de la db

            $categorias = Categoria::whereNull('bloqueado_por')->pluck('categoria', 'categoria_id');

            //estantes de la db
            $estantes = Estante::whereNull('bloqueado_por')->pluck('estante', 'estante_id');

            // //unidades de la db

            $unidades = Unidad_Medida::whereNull('bloqueado_por')->pluck('nombre', 'unidad_medida_id');


            // //periodos de la db

            $periodos = Periodo::whereNull('bloqueado_por')->pluck('fecha_inicio', 'periodo_id');


            $producto = Producto::find($id);

            // Verifica si el registro existe
            if (!$producto) {
                return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
            }

            return view('productos.detail', compact('producto', 'proveedores', 'categorias', 'estantes', 'unidades', 'periodos'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('productos')->with('error', 'Error al cargar la página para editar el producto');
        }
    }
}
