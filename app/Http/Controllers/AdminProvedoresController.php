<?php

namespace App\Http\Controllers;

use Doctrine\DBAL\Schema\View;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Role;


class AdminProvedoresController extends Controller
{
    public function index()
    {
        // Obtener el rol de "proveedor"
        $proveedorRole = Role::where('role', 'provedor')->first();

        // Si el rol existe, obtener todos los usuarios con ese rol
        if ($proveedorRole) {
            $proveedores = Usuario::whereHas('detalle_roles', function ($query) use ($proveedorRole) {
                $query->where('role_id', $proveedorRole->role_id);
            })->get();
            // $proveedores contendrá todos los usuarios con el rol de "proveedor"
        } else {
            // Manejar el caso en el que el rol "proveedor" no existe
            $proveedores = [];
        }

        return view('provedores.index', compact('proveedores'));
    }
    // Método para mostrar el formulario de creación de un proveedor
    public function create()
    {
        // Aquí puedes mostrar el formulario de creación de proveedores
        return view('provedores.create');
    }

    // Método para guardar un nuevo proveedor
    public function store(Request $request)
    {
        // Aquí deberías agregar la lógica para guardar el proveedor en la base de datos
        // Puedes usar $request para obtener los datos del formulario
        // Después de guardar, puedes redirigir a la lista de proveedores o mostrar un mensaje de éxito
    }

    // Método para mostrar el formulario de edición de un proveedor
    public function edit($id)
    {
        // Aquí puedes mostrar el formulario de edición de un proveedor específico
        // El parámetro $id contiene el ID del proveedor que se va a editar
        return view('provedores.edit', compact('id'));
    }

    // Método para actualizar un proveedor existente
    public function update(Request $request, $id)
    {
        // Aquí deberías agregar la lógica para actualizar el proveedor en la base de datos
        // Puedes usar $request para obtener los datos del formulario
        // El parámetro $id contiene el ID del proveedor que se va a actualizar
        // Después de actualizar, puedes redirigir a la lista de proveedores o mostrar un mensaje de éxito
    }

    // Método para eliminar un proveedor
    public function destroy($id)
    {
        // Aquí deberías agregar la lógica para eliminar el proveedor de la base de datos
        // El parámetro $id contiene el ID del proveedor que se va a eliminar
        // Después de eliminar, puedes redirigir a la lista de proveedores o mostrar un mensaje de éxito
    }

}
