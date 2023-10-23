<?php

use App\Http\Controllers\AdminCategoriasController;
use App\Http\Controllers\InventarioController;
use Illuminate\Support\Facades\Route;


<<<<<<< HEAD
Route::middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->group(function () {
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');


=======
Route::controller(InventarioController::class)->group(function () {

    Route::get('/inventario', 'index')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('inventario.index');
    Route::get('/inventario', 'validarCantidadProductos')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('inventario.productos_cantidad');
>>>>>>> 6d5e5a1b0f160ae221680ebd987a9dd374d11c05
});


