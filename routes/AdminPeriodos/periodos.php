<?php

use App\Http\Controllers\AdminPeriodosController;
use Illuminate\Support\Facades\Route;

Route::controller(AdminPeriodosController::class)->group(function (){
    Route::get('/periodos', 'index')->middleware(['auth', 'verified' ])->name('periodos');
    Route::get('/periodos/create', 'create')->middleware(['auth', 'verified'])->name('periodos.create');
    Route::post('/periodos/create', 'store')->middleware(['auth', 'verified' ])->name('periodos.store');


    Route::get('/periodos/edit/{id}', 'edit')->middleware(['auth', 'verified' ])->name('periodos.edit');
    Route::put('/periodos/update/{id}', 'update')->middleware(['auth', 'verified'])->name('periodos.update');


    Route::put('/periodos/bloquear/{id}', 'bloquear')->middleware(['auth', 'verified'])->name('periodos.bloquear');
    Route::put('/periodos/unblock/{id}', 'unblock')->middleware(['auth', 'verified'])->name('periodos.unblock');
});



