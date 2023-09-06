<?php

use App\Http\Controllers\AdminCategoriasController;
use App\Http\Controllers\AdminEmpleadosController;
use Illuminate\Support\Facades\Route;


Route::controller(AdminEmpleadosController::class)->group(function () {

    //aca falta ponerle que chequee el rol en cada uno
    //ejemplo:    ['auth', 'verified', 'checkRole:MegaAdmin'])->name('categorias');
    Route::get('/empleados', 'index')->middleware(['auth', 'verified' ])->name('empleados');
    Route::get('/empleados/create', 'create')->middleware(['auth', 'verified'])->name('empleados.create');
    Route::get('/empleados/edit/{id}', 'edit')->middleware(['auth', 'verified' ])->name('empleados.edit');

    // POST METHOD
    Route::post('/empleados/create', 'store')->middleware(['auth', 'verified' ])->name('empleados.store');

    // PUT METHOD
    Route::put('/empleados/update/{id}', 'update')->middleware(['auth', 'verified'])->name('empleados.update');

    // DELETE METHOD
    Route::delete('/empleados/destroy/{id}', 'destroy')->middleware(['auth', 'verified'])->name('empleados.destroy');
});
