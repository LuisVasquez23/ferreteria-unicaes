<?php

use App\Http\Controllers\AdminClienteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(AdminClienteController::class)->group(function () {

    // GET METHOD
    Route::get('/clientes', 'index')->middleware(['auth', 'verified', 'checkRole:Admin,Empleado,MegaAdmin'])->name('clientes');
    Route::get('/clientes/create', 'create')->middleware(['auth', 'verified', 'checkRole:Admin,Empleado,MegaAdmin'])->name('cliente.create');
    Route::get('/clientes/edit/{id}', 'edit')->middleware(['auth', 'verified', 'checkRole:Admin,Empleado,MegaAdmin'])->name('cliente.edit');

    // POST METHOD
    Route::post('/clientes/create', 'store')->middleware(['auth', 'verified', 'checkRole:Admin,Empleado,MegaAdmin'])->name('cliente.store');


    // PUT METHOD
    Route::put('/clientes/update/{id}', 'update')->middleware(['auth', 'verified', 'checkRole:Admin,Empleado,MegaAdmin'])->name('cliente.update');
    Route::put('/clientes/unblock/{id}', 'unblock')->middleware(['auth', 'verified', 'checkRole:Admin,Empleado,MegaAdmin'])->name('cliente.unblock');


    // DELETE METHOD
    Route::delete('/clientes/destroy/{id}', 'destroy')->middleware(['auth', 'verified', 'checkRole:Admin,Empleado,MegaAdmin'])->name('cliente.destroy');
});
