<?php

use App\Http\Controllers\AdminDetalleRolController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(AdminDetalleRolController::class)->group(function () {

    // GET METHOD
    Route::get('/detalles_roles', 'index')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('detalles_roles');
    Route::get('/detalles_roles/create', 'create')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('detalle_rol.create');
    Route::get('/detalles_roles/edit/{id}', 'edit')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('detalle_rol.edit');

    // POST METHOD
    Route::post('/detalles_roles/create', 'store')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('detalle_rol.store');


    // PUT METHOD
    Route::put('/detalles_roles/update/{id}', 'update')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('detalle_rol.update');
    Route::put('/detalles_roles/unblock/{id}', 'unblock')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('detalle_rol.unblock');


    // DELETE METHOD
    Route::delete('/detalles_roles/destroy/{id}', 'destroy')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('detalle_rol.destroy');
});
