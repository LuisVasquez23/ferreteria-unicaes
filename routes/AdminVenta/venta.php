<?php

use App\Http\Controllers\AdminVentaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(AdminVentaController::class)->group(function () {

    // GET METHOD
    Route::get('/ventas', 'index')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('ventas');
    Route::get('/ventas/create', 'create')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('ventas.create');
    Route::get('/ventas/edit/{id}', 'edit')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('ventas.edit');

    // POST METHOD
    Route::post('/ventas/create', 'store')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('ventas.store');


    // PUT METHOD
    Route::put('/ventas/update/{id}', 'update')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('ventas.update');
    Route::put('/ventas/unblock/{id}', 'unblock')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('ventas.unblock');


    // DELETE METHOD
    Route::delete('/ventas/destroy/{id}', 'destroy')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('ventas.destroy');
});
