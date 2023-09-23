<?php

use App\Http\Controllers\AdminUnidad_MedidaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::controller(AdminUnidad_MedidaController::class)->group(function () {

    // GET METHOD
    Route::get('/unidades', 'index')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('unidades');
    Route::get('/unidades/create', 'create')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('unidad.create');
    Route::get('/unidades/edit/{id}', 'edit')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('unidad.edit');

    // POST METHOD
    Route::post('/unidades/create', 'store')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('unidad.store');


    // PUT METHOD
    Route::put('/unidades/update/{id}', 'update')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('unidad.update');
    Route::put('/unidades/unblock/{id}', 'unblock')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('unidad.unblock');


    // DELETE METHOD
    Route::delete('/unidades/destroy/{id}', 'destroy')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('unidad.destroy');
});
