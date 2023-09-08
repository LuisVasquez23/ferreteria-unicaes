<?php

use App\Http\Controllers\AdminProveedoresController;
use App\Http\Controllers\ProfileController;
use App\Models\DetalleRole;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::controller(AdminProveedoresController::class)->group(function () {

    // GET METHOD
    Route::get('/proveedores', 'index')->middleware(['auth', 'verified'])->name('proveedores.index');
    Route::get('/proveedores/create', 'create')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('proveedores.create');
    Route::get('/proveedores/edit/{id}', 'edit')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('proveedores.edit');

    // POST METHOD
    Route::post('/proveedores/create', 'store')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('proveedores.store');

    // PUT METHOD
    Route::put('/proveedores/update/{id}', 'update')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('proveedores.update');
    Route::put('/proveedores/unblock/{id}', 'unblock')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('proveedores.unblock');

    // DELETE METHOD
    Route::put('/proveedores/bloquear/{id}', 'bloquear')->middleware(['auth', 'verified'])->name('proveedores.bloquear');

});
