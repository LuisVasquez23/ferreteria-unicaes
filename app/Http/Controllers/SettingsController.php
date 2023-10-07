<?php

namespace App\Http\Controllers;

use App\Models\Catalogo;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    // INDEX PARA RENDERIZAR LA VISTA
    public function index()
    {

        // OBTENER EL NOMBRE DE LA EMPRESA
        $nombreEmpresa = Catalogo::where('nombre', 'NOMBRE_EMPRESA')->first()->valor;


        return View('settings.index', compact('nombreEmpresa'));
    }

    // FUNCION PARA CAMBIAR EL LOGO DE LA EMPRESA
    public function cambiarLogo()
    {
    }

    // FUNCION PARA CAMBIAR EL NOMBRE DE LA EMPRESA
    public function cambiarNombreEmpresa(Request $request)
    {
        // Valida el formulario 
        $request->validate([
            'nuevoNombre' => 'required|string|max:255',
        ]);

        // Busca el registro del catálogo por nombre
        $catalogo = Catalogo::where('nombre', 'NOMBRE_EMPRESA')->first();

        if ($catalogo) {
            // Actualiza el valor del campo 'valor' en el registro del catálogo con el nuevo nombre
            $catalogo->valor = $request->input('nuevoNombre');
            $catalogo->save();

            return redirect()->back()->with('success', 'El nombre de la empresa ha sido actualizado.');
        } else {
            return redirect()->back()->with('error', 'La empresa no fue encontrada.');
        }
    }
}
