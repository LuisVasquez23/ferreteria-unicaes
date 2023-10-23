<?php

use App\Http\Controllers\AdminCategoriasController;
use App\Http\Controllers\InventarioController;
use Illuminate\Support\Facades\Route;



Route::controller(InventarioController::class)->group(function () {
    Route::get('/inventario', 'index')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('inventario.index');
    Route::get('/inventario/validarCantidadProductos', 'validarCantidadProductos')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('inventario.productos_cantidad');
});
