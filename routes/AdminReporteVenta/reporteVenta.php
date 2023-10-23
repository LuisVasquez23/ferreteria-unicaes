<?php


use App\Http\Controllers\AdminReportesVentas;
use Illuminate\Support\Facades\Route;


Route::controller(AdminReportesVentas::class)->group(function () {
    // GET and POST METHODS
    Route::match(['get', 'post'], '/reporteVenta', 'index')->middleware(['auth', 'verified', 'checkRole:MegaAdmin,Admin'])->name('reporteVenta.index');

    Route::get('/reporteVenta/pdf/{num_factura}', [AdminReportesVentas::class, 'pdf'])->name('reporteVenta.pdf');
});
