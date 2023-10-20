<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Periodo;

class InventarioController extends Controller
{
    public function index(Request $request){
        try {
            // Obtener todos los periodos
            $periodos = Periodo::all();
            
            // Obtener productos con relación a los periodos
            $productos = Producto::with('periodo');
    
            // Verificar si se ha enviado un filtro por periodo
            if ($request->has('periodo')) {
                $periodoId = $request->input('periodo');
                $productos->where('periodo_id', $periodoId);
            }
    
            $productos = $productos->get();
    
            return view('inventario.index', compact('productos', 'periodos'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('inventario')->with('error', 'Error al cargar la página de productos');
        }
    }

    
}
