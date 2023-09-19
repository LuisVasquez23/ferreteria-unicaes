<?php

use App\Http\Controllers\AdminEstanteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(AdminEstanteController::class)->group(function () {

    // GET METHOD
    Route::get('/estantes', 'index')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('estantes');
    Route::get('/estantes/create', 'create')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('estante.create');
    Route::get('/estantes/edit/{id}', 'edit')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('estante.edit');

    // POST METHOD
    Route::post('/estantes/create', 'store')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('estante.store');


    // PUT METHOD
    Route::put('/estantes/update/{id}', 'update')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('estante.update');
    Route::put('/estantes/unblock/{id}', 'unblock')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('estante.unblock');


    // DELETE METHOD
    Route::delete('/estantes/destroy/{id}', 'destroy')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('estante.destroy');
});
