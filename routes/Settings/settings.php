<?php

use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;


Route::controller(SettingsController::class)->group(function () {

    // GET METHOD
    Route::get('/settings', 'index')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('settings.index');

    // POST METHODS 
    Route::post('/settings/logo', 'cambiarLogo')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('settings.logo');
    Route::post('/settings/nombreEmpresa', 'cambiarNombreEmpresa')->middleware(['auth', 'verified', 'checkRole:MegaAdmin'])->name('settings.nombreEmpresa');
});
