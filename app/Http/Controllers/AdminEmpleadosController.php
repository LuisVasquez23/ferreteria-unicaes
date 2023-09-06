<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use App\Models\Usuario;
use App\Models\Role;
class AdminEmpleadosController extends Controller
{
    public function index()
    {

        try{

            $empleadoRole = Role::where('role', 'Empleado')->first();

        // Si el rol existe, obtener todos los usuarios con ese rol
        if ($empleadoRole) {
            $empleados = Usuario::whereHas('detalle_roles', function ($query) use ($empleadoRole) {
                $query->where('role_id', $empleadoRole->role_id);
            })->get();
            // $proveedores contendrá todos los usuarios con el rol de "proveedor"
        } else {
            // Manejar el caso en el que el rol "proveedor" no existe
            $empleados = [];
        }

        return view('empleados.index', compact('empleados'));
        }catch(Exception $e){

        }
       
    }

    public function create()
    {
        try{
            return view('empleados.create');
        }catch(Exception $e){

        }
    }


    public function store(Request $request)
    {
        try{
            
        }catch(Exception $e){

        }
    }


    public function edit($id)
    {
        try{
            
        }catch(Exception $e){

        }
    }


    public function update(Request $request, $id)
    {
        try{
            
        }catch(Exception $e){

        }
    }

    public function destroy($id)
    {
        
    }



}
