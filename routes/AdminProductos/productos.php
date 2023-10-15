<?php

use App\Http\Controllers\AdminProductoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(AdminProductoController::class)->group(function () {

    // GET METHOD
    Route::get('/productos', 'index')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('productos');
    Route::get('/productos/create', 'create')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('producto.create');
    Route::get('/productos/edit/{id}', 'edit')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('producto.edit');
    Route::get('/productos/detail/{id}', 'detail')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('producto.detail');

    // POST METHOD
    Route::post('/productos/create', 'store')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('producto.store');


    // PUT METHOD
    Route::put('/productos/update/{id}', 'update')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('producto.update');
    Route::put('/productos/unblock/{id}', 'unblock')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('producto.unblock');


    // DELETE METHOD
    Route::delete('/productos/destroy/{id}', 'destroy')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('producto.destroy');
});
