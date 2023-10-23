<?php

use App\Http\Controllers\AdminCategoriasController;
use App\Http\Controllers\InventarioController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->group(function () {
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
});
