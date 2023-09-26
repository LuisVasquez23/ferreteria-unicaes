<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminProductoController extends Controller
{
    
    public function index(Request $request)
    {
        try {


            $filtro = $request->input('filtro', 'no-bloqueados');

            if ($filtro === 'bloqueados') {
                $productos = Producto::whereNotNull('bloqueado_por')->get();
            } else {
                $productos = Producto::whereNull('bloqueado_por')->get();
            }

            return view('productos.index', compact('productos', 'filtro'));
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return redirect()->route('productos')->with('error', 'Error al cargar la página de productos');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
