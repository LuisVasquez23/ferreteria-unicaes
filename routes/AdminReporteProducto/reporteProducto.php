<?php

use App\Http\Controllers\AdminReportesProductos;
use Illuminate\Support\Facades\Route;


Route::controller(AdminReportesProductos::class)->group(function () {
    // GET and POST METHODS
    Route::match(['get', 'post'], '/reporteProducto', 'index')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('reporteProducto.index');

    Route::get('/reporteProducto/pdf/{num_factura}', [AdminReportesProductos::class, 'pdf'])->name('reporteProducto.pdf');
});
