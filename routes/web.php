<?php

use App\Http\Controllers\ProfileController;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
});

Route::get('/dashboard', function () {
    $userId = Auth::user()->usuario_id;
    $usuario = Usuario::with('detalle_roles.role')->find($userId); // Cargar relaciones

    return view('dashboard', compact('usuario'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

require __DIR__ . '/AdminMenu/menu.php';
require __DIR__ . '/AdminRoles/roles.php';
require __DIR__ . '/AdminCategorias/categorias.php';


require __DIR__ . '/AdminEmpleados/empleados.php';
