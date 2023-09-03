<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminCategoriasController extends Controller
{
    public function index()
    {
        // Obtener todas las categorías desde el modelo Categoria
        $categorias = Categoria::all();

        // Renderizar la vista 'categorias.index' y pasar las categorías a la vista
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        // Aquí puedes agregar lógica para mostrar el formulario de creación de categorías
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'categoria' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        // Obtener el nombre del usuario autenticado como "creado_por"
        $creadoPor = Auth::user()->nombres;

        // Crear una nueva instancia de Categoria con los datos del formulario
        $categoria = new Categoria([
            'categoria' => $request->input('categoria'),
            'descripcion' => $request->input('descripcion'),
            'creado_por' => $creadoPor, // Establecer el nombre del usuario como "creado_por"
        ]);

        // Guardar la categoría en la base de datos
        $categoria->save();

        // Redireccionar a la página de índice de categorías o a donde desees después de guardar
        return redirect()->route('categorias')->with('success', 'Categoría creada exitosamente.');
    }

    public function edit($id)
    {
        // Obtener la categoría que se va a editar
        $categoria = Categoria::find($id);

        // Verificar si la categoría existe
        if (!$categoria) {
            return redirect()->route('categorias')->with('error', 'Categoría no encontrada.');
        }

        // Renderizar el formulario de edición de categorías
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'categoria' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        // Obtener la categoría que se va a actualizar
        $categoria = Categoria::find($id);

        // Verificar si la categoría existe
        if (!$categoria) {
            return redirect()->route('categorias')->with('error', 'Categoría no encontrada.');
        }

        // Actualizar los campos de la categoría con los datos del formulario
        $categoria->categoria = $request->input('categoria');
        $categoria->descripcion = $request->input('descripcion');

        // Obtener el nombre del usuario autenticado y asignarlo al campo "actualizado_por"
        $categoria->actualizado_por = Auth::user()->nombres;

        // Guardar los cambios en la base de datos
        $categoria->save();

        // Redireccionar a la página de índice de categorías o a donde desees después de actualizar
        return redirect()->route('categorias')->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy($id)
    {
        // Obtener la categoría que se va a eliminar
        $categoria = Categoria::find($id);

        // Verificar si la categoría existe
        if (!$categoria) {
            return redirect()->route('categorias')->with('error', 'Categoría no encontrada.');
        }

        // Eliminar la categoría de la base de datos
        $categoria->delete();

        // Redireccionar a la página de índice de categorías o a donde desees después de eliminar
        return redirect()->route('categorias')->with('success', 'Categoría eliminada exitosamente.');
    }

}


