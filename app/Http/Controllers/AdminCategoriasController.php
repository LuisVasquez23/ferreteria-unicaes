<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;




class AdminCategoriasController extends Controller
{
    public function index(Request $request)
    {
        try {
            $filtro = $request->input('filtro', 'no-bloqueados');

            if ($filtro === 'bloqueados') {
                $categorias = Categoria::whereNotNull('bloqueado_por')->get();
            } else {
                $categorias = Categoria::whereNull('bloqueado_por')->get();
            }

            return view('categorias.index', compact('categorias', 'filtro'));
        } catch (\Exception $e) {
            return back()->withError('Error al obtener las categorías: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            // Aquí puedes agregar lógica para mostrar el formulario de creación de categorías
            return view('categorias.create');
        } catch (\Exception $e) {
            // Manejar la excepción de alguna manera
            return back()->withError('Error al mostrar el formulario de creación: ' . $e->getMessage());
        }
    }


    public function store(Request $request)
    {
        try {
            // Definir mensajes de validación personalizados
            $messages = [
                'categoria.required' => 'El campo categoría es obligatorio.',
                'categoria.unique' => 'La categoría ya existe en la base de datos.',
                'descripcion.required' => 'El campo descripción es obligatorio.',
            ];

            // Validar los datos del formulario con mensajes personalizados
            $request->validate([
                'categoria' => 'required|string|max:255|unique:categorias',
                'descripcion' => 'required|string',
            ], $messages);

            $categoria = new Categoria();
            // crea los campos de la categoría con los datos del formulario
            $categoria->categoria = $request->input('categoria');
            $categoria->descripcion = $request->input('descripcion');
            $categoria->fecha_actualizacion = now();
            // Obtener el nombre del usuario autenticado y asignarlo al campo "creado_por"
            $categoria->creado_por = Auth::user()->nombres;

            // Guardar los cambios en la base de datos
            $categoria->save();


            // Redireccionar a la página de índice de categorías o a donde desees después de guardar
            return redirect()->route('categorias')->with('success', 'Categoría creada exitosamente.');
        } catch (ValidationException $e) {
            // Manejar excepciones de validación (por ejemplo, campos requeridos)
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Manejar otras excepciones generales
            return redirect()->back()->with('error', 'Ocurrió un error al crear la categoría.');
        }
    }


    public function edit($id)
    {
        try {
            // Obtener la categoría que se va a editar
            $categoria = Categoria::find($id);

            // Verificar si la categoría existe
            if (!$categoria) {
                return redirect()->route('categorias')->with('error', 'Categoría no encontrada.');
            }

            // Renderizar el formulario de edición de categorías
            return view('categorias.edit', compact('categoria'));
        } catch (\Exception $e) {
            // Manejar otras excepciones generales
            return redirect()->route('categorias')->with('error', 'Ocurrió un error al cargar el formulario de edición.');
        }
    }


    public function update(Request $request, $id)
    {
        try {
              // Obtener la categoría que se va a actualizar
              $categoria = Categoria::find($id);
    
              // Verificar si la categoría existe
              if (!$categoria) {
                  return redirect()->route('categorias')->with('error', 'Categoría no encontrada.');
              }
              // Definir mensajes de validación personalizados
              $messages = [
                  'categoria.required' => 'El campo categoría es obligatorio.',
                  'categoria.unique' => 'La categoría ya existe.',
                  'descripcion.required' => 'El campo descripción es obligatorio.',
              ];
              // Validar los datos del formulario
              $request->validate([
                  'categoria' => 'required|string|max:255',
                  'descripcion' => 'required|string',
              ],$messages);
      
              // Verificar si el nombre de la categoría se ha modificado y si es diferente del nombre actual
              if ($request->input('categoria') !== $categoria->categoria) {
                  // Si el nombre se ha modificado, validar que no exista otra categoría con el mismo nombre
                  $request->validate([
                      'categoria' => 'unique:categorias',
                  ],$messages);
              }
      
              // Actualizar los campos de la categoría con los datos del formulario
              $categoria->categoria = $request->input('categoria');
              $categoria->descripcion = $request->input('descripcion');
              $categoria->fecha_actualizacion = now();
              // Obtener el nombre del usuario autenticado y asignarlo al campo "actualizado_por"
              $categoria->actualizado_por = Auth::user()->nombres;
      
              // Guardar los cambios en la base de datos
              $categoria->save();
      
              // Redireccionar a la página de índice de categorías o a donde desees después de actualizar
              return redirect()->route('categorias')->with('success', 'Categoría actualizada exitosamente.');
  
        } catch (ValidationException $e) {
            // Manejar excepciones de validación (por ejemplo, campos requeridos)
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Manejar otras excepciones generales
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar la categoría.');
        }
    }

    public function bloquear($id)
    {
        try {
            // Obtener la categoría que se va a eliminar
            $categoria = Categoria::find($id);

            // Verificar si la categoría existe
            if (!$categoria) {
                return redirect()->route('categorias')->with('error', 'Categoría no encontrada.');
            }

            // Eliminar la categoría de la base de datos
            //$categoria->delete();

            // Cambiamos estado a bloqueado

             $categoria->fecha_bloqueo = now();
             // Obtener el nombre del usuario autenticado y asignarlo al campo "actualizado_por"
             $categoria->bloqueado_por = Auth::user()->nombres;

             // Guardar los cambios en la base de datos
             $categoria->save();

            // Redireccionar a la página de índice de categorías o a donde desees después de eliminar
            return redirect()->route('categorias')->with('success', 'Categoría eliminada exitosamente.');
        } catch (QueryException $e) {
            // Manejar excepciones relacionadas con consultas SQL
            return redirect()->route('categorias')->with('error', 'Ocurrió un error al eliminar la categoría: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Manejar otras excepciones generales
            return redirect()->route('categorias')->with('error', 'Ocurrió un error al eliminar la categoría.');
        }
    }

    public function unblock($id)
    {
        try {
            // Buscar la categoría por su ID
            $categoria = Categoria::find($id);

            // Verificar si la categoría está bloqueada
            if (!$categoria->bloqueado_por) {
                return redirect()->route('categorias')->with('error', 'La categoría no está bloqueada.');
            }

            // Desbloquear la categoría
            $categoria->bloqueado_por = null;
            $categoria->fecha_actualizacion = now();
            $categoria->actualizado_por = Auth::user()->nombres;
            $categoria->fecha_bloqueo = null;

            // Guardar los cambios
            $categoria->save();

            return redirect()->route('categorias')->with('success', 'La categoría ha sido desbloqueada con éxito.');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('categorias')->with('error', 'Error al desbloquear la categoría.');
        }
    }






}


