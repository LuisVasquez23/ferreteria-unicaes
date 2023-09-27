<?php

use App\Http\Controllers\AdminCompraController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(AdminCompraController::class)->group(function () {

    // GET METHOD
    Route::get('/compras', 'index')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('compras');
    Route::get('/compras/create', 'create')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('compras.create');
    Route::get('/compras/edit/{id}', 'edit')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('compras.edit');

    // POST METHOD
    Route::post('/compras/create', 'store')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('compras.store');


    // PUT METHOD
    Route::put('/compras/update/{id}', 'update')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('compras.update');
    Route::put('/compras/unblock/{id}', 'unblock')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('cliente.unblock');


    // DELETE METHOD
    Route::delete('/compras/destroy/{id}', 'destroy')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('compras.destroy');
});

