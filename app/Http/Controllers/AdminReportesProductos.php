<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminReportesProductos extends Controller
{
    public function index(Request $request){

        return view('reporteProducto.index');
    }

}
