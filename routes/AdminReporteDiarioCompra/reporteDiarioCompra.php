<?php

use App\Http\Controllers\AdminReporteDiarioCompra;
use Illuminate\Support\Facades\Route;


Route::controller(AdminReporteDiarioCompra::class)->group(function () {
    // GET and POST METHODS
    Route::match(['get', 'post'], '/reporteDiarioCompra', 'index')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('reporteDiarioCompra.index');

    Route::get('/reporteDiarioCompra/pdf/{fechaInicio}', [AdminReporteDiarioCompra::class, 'pdf'])->name('reporteDiarioCompra.pdf');

});
