<?php

use App\Http\Controllers\AdminVentaController;
use Illuminate\Support\Facades\Route;

Route::controller(AdminVentaController::class)->group(function () {

    // GET METHOD
    Route::get('/ventas', 'index')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('ventas');
    Route::get('/ventas/create', 'create')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('ventas.create');

    // POST METHOD
    Route::post('/ventas/create', 'store')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('ventas.store');
    Route::post('/verificar-cantidad', 'verificarCantidad')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('verificar-cantidad');

});
