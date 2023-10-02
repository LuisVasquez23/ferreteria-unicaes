<?php

use App\Http\Controllers\AdminPerfilController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::controller(AdminPerfilController::class)->group(function () {

    // GET METHOD
    Route::get('/perfiles', 'edit')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('perfiles');


    // PUT METHOD
    Route::put('/perfiles/update/{id}', 'update')->middleware(['auth', 'verified', 'checkRole:Admin,MegaAdmin,Empleado'])->name('perfil.update');
});
