<?php

use App\Http\Controllers\AdminRolesController;
use Illuminate\Support\Facades\Route;


Route::controller(AdminRolesController::class)->group(function () {

    // GET METHOD
    Route::get('/roles', 'index')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('roles');
    Route::get('/roles/create', 'create')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('roles.create');
    Route::get('/roles/edit/{id}', 'index')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('roles.edit');

    // POST METHOD
    Route::post('/roles/create', 'store')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('roles.store');


    // PUT METHOD
    Route::put('/roles/update/{id}', 'update')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('roles.update');
    Route::put('/roles/unblock/{id}', 'unblock')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('roles.unblock');


    // DELETE METHOD
    Route::delete('/roles/destroy/{id}', 'destroy')->middleware(['auth', 'verified', 'checkRole:Admin'])->name('roles.destroy');
});
