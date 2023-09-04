<?php

use App\Http\Controllers\AdminProvedoresController;
use App\Http\Controllers\ProfileController;
use App\Models\DetalleRole;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::controller(AdminProvedoresController::class)->group(function () {

    // GET METHOD
    Route::get('/provedores', 'index')->middleware(['auth', 'verified'])->name('provedores');
    Route::get('/provedores/create', 'create')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('proveedores.create');
    Route::get('/provedores/edit/{id}', 'edit')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('proveedores.edit');

    // POST METHOD
    Route::post('/provedores/create', 'store')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('proveedores.store');

    // PUT METHOD
    Route::put('/provedores/update/{id}', 'update')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('proveedores.update');

    // DELETE METHOD
    Route::delete('/provedores/destroy/{id}', 'destroy')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('proveedores.destroy');
});
