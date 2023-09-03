<?php

use App\Http\Controllers\AdminMenuController;
use App\Http\Controllers\ProfileController;
use App\Models\DetalleRole;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::controller(AdminMenuController::class)->group(function () {

    // GET METHOD
    Route::get('/menu', 'index')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('menu');
    Route::get('/menu/create', 'create')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('menu.create');

    // POST METHOD

});
