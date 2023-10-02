<?php

use App\Http\Controllers\AdminCompraController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(AdminCompraController::class)->group(function () {

    // GET METHOD
    Route::get('/compras', 'index')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('compras');
    Route::get('/compras/create', 'create')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('compras.create');

    // POST METHOD
    Route::post('/compras/create', 'store')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('compras.store');
});
