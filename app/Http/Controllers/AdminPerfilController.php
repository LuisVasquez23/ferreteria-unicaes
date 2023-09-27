<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminPerfilController extends Controller
{
    //

    public function edit()
    {
        $usuario = Auth::user();
        
        return view('perfil.edit', compact('usuario'));
    }



    public function update(Request $request, $id)
    {

    try{

        $usuario = Usuario::find($id);

        if (!$usuario) {
            return redirect()->back()->with('error', 'Ha ocurrido un error. No se pudo realizar la operación.');
        }

        //validar teléfono
        $existingPhone = Usuario::where('telefono', $request->input('telefono_opcion'))
            ->where('usuario_id', '<>', $id)
            ->first();     
 
         if ($existingPhone) {
            return redirect()->route('perfiles')->with('error', 'El teléfono ya está registrado.');         
        }

        //validar email
        $existingEmail = Usuario::where('email', $request->input('email_opcion'))
            ->where('usuario_id', '<>', $id)
            ->first();

        if ($existingEmail) {
            return redirect()->route('perfiles')->with('error', 'El correo electrónico ya está registrado.');         
        }

          // Definimos las reglas de validación
          $rules = [

            'nombre_opcion' => 'required',
            'apellido_opcion' => 'required',

            'telefono_opcion' => 'required|regex:/^\d{4}-\d{4}$/|unique:usuarios,telefono,'.$id.',usuario_id',


            'email_opcion' => 'required|email|unique:usuarios,email,'.$id.',usuario_id',

            'contrasenia_nueva' => [
                'nullable',
                'min:8', 
                function ($attribute, $value, $fail) use ($request, $usuario) {
                    // Verifica si la contraseña nueva es igual a la contraseña antigua
                    if (Hash::check($value, $usuario->password)) {
                        $fail('La contraseña nueva no puede ser igual a la contraseña antigua.');
                    }
                },
            ],
        ];

        $messages = [

            'nombre_opcion.required' => 'Debes registrar al menos un nombre',
            'apellido_opcion.required' => 'Debes registrar al menos un apellido',


            'telefono_opcion.required' => 'El campo "Teléfono" es obligatorio.',
            'telefono_opcion.unique' => 'Este teléfono ya está registrado.',
            'telefono_opcion.regex' => 'El campo "Teléfono" debe tener el formato correcto (por ejemplo, 7889-1256).',

            'email_opcion.required' => 'El campo "email" es obligatorio.',
            'email_opcion.unique' => 'El email ya está registrado, intenta de nuevo',
            'email_opcion.email' => 'Ingresa una dirección de email correcta.',

            'contrasenia_nueva.min' => 'La contraseña nueva debe tener al menos 8 caracteres.',


        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->route('perfiles') 
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->filled('contrasenia_nueva')) {
            if ($request->input('contrasenia_nueva') === $request->input('comprobar_contrasenia')) {
                if (Hash::check($request->input('contrasenia_antigua'), $usuario->password)) {
                    $usuario->password = bcrypt($request->input('contrasenia_nueva'));
                } else {
                    return redirect()->route('perfiles')->with('error', 'La contraseña actual es incorrecta.');
                }
            } else {
                return redirect()->route('perfiles')->with('error', 'La nueva contraseña y la confirmación no coinciden.');
            }
        }
        
        
        // Si no se proporcionó una nueva contraseña, actualizar otros datos
        $usuario->nombres = $request->input('nombre_opcion');
        $usuario->apellidos = $request->input('apellido_opcion');
        $usuario->email = $request->input('email_opcion');
        $usuario->telefono = $request->input('telefono_opcion');
        
        $usuario->save();
        
        return redirect()->route('dashboard')->with('success', 'Información actualizada exitosamente');
        
        
    } catch (ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Throwable $th) {
        return redirect()->route('dashboard')->with('error', 'Sucedio un error al actualizar la informaciòn');
    }
}

}
