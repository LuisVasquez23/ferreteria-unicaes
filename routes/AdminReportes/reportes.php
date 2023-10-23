<?php

use App\Http\Controllers\AdminReportes;
use Illuminate\Support\Facades\Route;


Route::controller(AdminReportes::class)->group(function () {
    // GET and POST METHODS
    Route::match(['get', 'post'], '/reportes', 'index')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('reporteCompra.index');

    Route::get('/reportes/pdf/{num_factura}', [AdminReportes::class, 'pdf'])->name('reportes.pdf');
});
