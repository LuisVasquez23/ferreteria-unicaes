<?php

use App\Http\Controllers\AdminRolesController;
use Illuminate\Support\Facades\Route;


Route::controller(AdminRolesController::class)->group(function () {

    // GET METHOD
    Route::get('/roles', 'index')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('roles');


    // POST METHOD
    // PUT METHOD
    // DELETE METHOD
});
