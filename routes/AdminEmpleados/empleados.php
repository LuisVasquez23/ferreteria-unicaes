<?php

use App\Http\Controllers\AdminCategoriasController;
use App\Http\Controllers\AdminEmpleadosController;
use Illuminate\Support\Facades\Route;


Route::controller(AdminEmpleadosController::class)->group(function () {
    Route::get('/empleados', 'index')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin'])->name('empleados.index');
    Route::get('/empleados/create', 'create')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin'])->name('empleados.create');
    Route::get('/empleados/edit/{id}', 'edit')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin'])->name('empleados.edit');

    // POST METHOD
    Route::post('/empleados/create', 'store')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin'])->name('empleados.store');


    // PUT METHOD
    Route::put('/empleados/update/{id}', 'update')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin'])->name('empleados.update');
    Route::put('/empleados/unblock/{id}', 'unblock')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin'])->name('empleados.unblock');


    // DELETE METHOD
    Route::delete('/empleados/destroy/{id}', 'destroy')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin'])->name('empleados.destroy');
});
