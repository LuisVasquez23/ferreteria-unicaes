<?php

use App\Http\Controllers\AdminCategoriasController;
use Illuminate\Support\Facades\Route;


Route::controller(AdminCategoriasController::class)->group(function () {

    //aca falta ponerle que chequee el rol en cada uno
    //ejemplo:    ['auth', 'verified', 'checkRole:MegaAdmin'])->name('categorias');
    Route::get('/categorias', 'index')->middleware(['auth', 'verified' ])->name('categorias');
    Route::get('/categorias/create', 'create')->middleware(['auth', 'verified'])->name('categorias.create');
    Route::get('/categorias/edit/{id}', 'edit')->middleware(['auth', 'verified' ])->name('categorias.edit');
    Route::get('/categorias/search', 'serch')->middleware(['auth', 'verified' ])->name('categorias.search');

    // POST METHOD
    Route::post('/categorias/create', 'store')->middleware(['auth', 'verified' ])->name('categorias.store');

    // PUT METHOD
    Route::put('/categorias/update/{id}', 'update')->middleware(['auth', 'verified'])->name('categorias.update');

    // DELETE METHOD
    Route::put('/categorias/bloquear/{id}', 'bloquear')->middleware(['auth', 'verified'])->name('categorias.bloquear');
    Route::put('/categorias/unblock/{id}', 'unblock')->middleware(['auth', 'verified'])->name('categorias.unblock');

});
