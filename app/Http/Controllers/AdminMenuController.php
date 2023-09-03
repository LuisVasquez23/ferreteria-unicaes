<?php

namespace App\Http\Controllers;

use App\Models\MenuOption;
use Illuminate\Http\Request;

class AdminMenuController extends Controller
{

    public function index()
    {

        $opcionesMenu = MenuOption::all();

        return view('menu.index', compact('opcionesMenu'));
    }

    public function create()
    {
        return view('menu.create');
    }
}
