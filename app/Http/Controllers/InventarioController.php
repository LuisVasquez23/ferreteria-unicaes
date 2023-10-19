<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InventarioController extends Controller
{
    public function index(Request $request){
        try {


            $productos = Producto::with('periodo')->get();

            return view('inventario.index', compact('productos'));
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return redirect()->route('inventario')->with('error', 'Error al cargar la página de productos');
        }
    }

    
}
