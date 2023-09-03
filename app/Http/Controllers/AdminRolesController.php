<?php

namespace App\Http\Controllers;

use Doctrine\DBAL\Schema\View;
use Illuminate\Http\Request;

class AdminRolesController extends Controller
{
    public function index()
    {
        return View('roles.index');
    }
}
