<?php

use App\Http\Controllers\AdminReporteDiario;
use Illuminate\Support\Facades\Route;


Route::controller(AdminReporteDiario::class)->group(function () {
    // GET and POST METHODS
    Route::match(['get', 'post'], '/reporteDiario', 'index')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('reporteDiario.index');

    Route::get('/reporteDiario/pdf/{id}', [AdminReporteDiario::class, 'pdf'])->name('reporteDiario.pdf');

});
