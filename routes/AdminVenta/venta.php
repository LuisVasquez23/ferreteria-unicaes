<?php

use App\Http\Controllers\AdminVentaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(AdminVentaController::class)->group(function () {

    // GET METHOD
    Route::get('/ventas', 'index')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('ventas');
    Route::get('/ventas/create', 'create')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('ventas.create');

    // POST METHOD
    Route::post('/ventas/create', 'store')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('ventas.store');


   
});
