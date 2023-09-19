<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Doctrine\DBAL\Schema\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminRolesController extends Controller
{
    public function index(Request $request)
    {

        try {


            $filtro = $request->input('filtro', 'no-bloqueados');

            if ($filtro === 'bloqueados') {
                $roles = Role::whereNotNull('bloqueado_por')->get();
            } else {
                $roles = Role::whereNull('bloqueado_por')->get();
            }

            return view('roles.index', compact('roles', 'filtro'));
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return redirect()->route('clientes')->with('error', 'Error al cargar la página de clientes');
        }
    }


    public function create()
    {
        return view('roles.create');
    }
}
