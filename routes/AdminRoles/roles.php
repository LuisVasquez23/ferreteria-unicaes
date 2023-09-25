<?php

use App\Http\Controllers\AdminRolesController;
use Illuminate\Support\Facades\Route;


Route::controller(AdminRolesController::class)->group(function () {

    // GET METHOD
    Route::get('/roles', 'index')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('roles');
    Route::get('/roles/create', 'create')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('rol.create');
    Route::get('/roles/edit/{id}', 'edit')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('rol.edit');

    // POST METHOD
    Route::post('/roles/create', 'store')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('rol.store');


    // PUT METHOD
    Route::put('/roles/update/{id}', 'update')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('rol.update');
    Route::put('/roles/unblock/{id}', 'unblock')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('rol.unblock');


    // DELETE METHOD
    Route::delete('/roles/destroy/{id}', 'destroy')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('rol.destroy');
});
