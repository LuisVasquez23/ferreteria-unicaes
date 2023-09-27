<?php

namespace App\Http\Controllers;

use App\Models\Venta; // Asegúrate de importar el modelo Venta
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminVentaController extends Controller
{
    public function index()
    {
        // Recuperar todas las ventas
        $ventas = Venta::all();

        // Devolver vista de lista de ventas
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        // Devolver vista de creación de venta
        return view('ventas.create');
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            // Agrega aquí las reglas de validación para los campos de la venta
        ]);

        // Crea una nueva venta
        Venta::create([
            // Asigna aquí los valores de los campos de la venta a partir de $request
        ]);

        // Redireccionar a la lista de ventas o a donde desees
        return redirect()->route('ventas');
    }

    public function edit($id)
    {
        // Buscar la venta por ID
        $venta = Venta::find($id);

        // Devolver vista de edición de venta
        return view('ventas.edit', compact('venta'));
    }

    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            // Agrega aquí las reglas de validación para los campos de la venta
        ]);

        // Buscar la venta por ID
        $venta = Venta::find($id);

        // Actualizar los campos de la venta
        $venta->update([
            // Asigna aquí los valores de los campos de la venta a partir de $request
        ]);

        // Redireccionar a la lista de ventas o a donde desees
        return redirect()->route('ventas');
    }

    public function destroy($id)
    {
        try {
            // Buscar la venta por ID y eliminarla
            $venta = Venta::find($id);
            $venta->delete();

            // Redireccionar a la lista de ventas o a donde desees
            return redirect()->route('ventas')->with('success', 'La venta se ha eliminado con éxito.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('ventas')->with('error', 'Error al eliminar la venta.');
        }
    }

    public function unblock($id)
    {
        try {
            // Implementa aquí la lógica para desbloquear la venta, si es necesario
            // Asegúrate de importar el modelo correspondiente y realizar las operaciones necesarias
            // Redireccionar a la lista de ventas o a donde desees después de desbloquear

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('ventas')->with('error', 'Error al desbloquear la venta.');
        }
    }
}
