<?php

namespace App\Http\Controllers;

use App\Models\Catalogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    public function cambiarLogo(Request $request)
    {
        try {
            // Validación de la imagen (tamaño, formato, etc.)
            $request->validate([
                'nuevoLogo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            ]);

            // Sube el nuevo logo a la ubicación deseada
            $newLogo = $request->file('nuevoLogo');
            $newLogoExtension = $newLogo->getClientOriginalExtension(); // Obtiene la extensión original

            // Define el nombre del archivo como 'logo' con la extensión original
            $newLogoName = 'public/upload/logo.' . $newLogoExtension;

            // Elimina el archivo existente si existe
            if (Storage::exists($newLogoName)) {
                Storage::delete($newLogoName);
            }

            // Almacena el nuevo logo en la ubicación deseada con el mismo nombre y formato original
            Storage::put($newLogoName, file_get_contents($newLogo));

            // Actualiza el registro en la tabla catalogos con el nuevo nombre y formato
            $catalogo = Catalogo::where('nombre', 'LOGO_EMPRESA')->first();
            if ($catalogo) {
                $catalogo->valor = 'logo.' . $newLogoExtension;
                $catalogo->save();
            }

            // Redirige de vuelta con un mensaje de éxito
            return redirect()->back()->with('success', 'Logo de la empresa cambiado exitosamente');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Sucedió un error al almacenar la imagen");
        }
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
