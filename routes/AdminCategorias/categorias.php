<?php

use App\Http\Controllers\AdminCategoriasController;
use Illuminate\Support\Facades\Route;


Route::controller(AdminCategoriasController::class)->group(function () {

    //aca falta ponerle que chequee el rol en cada uno
    //ejemplo:    ['auth', 'verified', 'checkRole:MegaAdmin'])->name('categorias');
    Route::get('/categorias', 'index')->middleware(['auth', 'verified' ])->name('categorias');
    Route::get('/categorias/create', 'create')->middleware(['auth', 'verified'])->name('categorias.create');
    Route::get('/categorias/edit/{id}', 'edit')->middleware(['auth', 'verified' ])->name('categorias.edit');

    // POST METHOD
    Route::post('/categorias/create', 'store')->middleware(['auth', 'verified' ])->name('categorias.store');

    // PUT METHOD
    Route::put('/categorias/update/{id}', 'update')->middleware(['auth', 'verified'])->name('categorias.update');

    // DELETE METHOD
    Route::delete('/categorias/destroy/{id}', 'destroy')->middleware(['auth', 'verified'])->name('categorias.destroy');
});
