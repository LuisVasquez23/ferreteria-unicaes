<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Catalogo;

class NombreEmpresaMiddleware
{
    public function handle($request, Closure $next)
    {
        // Obtener el nombre de la empresa
        $nombreEmpresa = Catalogo::where('nombre', 'NOMBRE_EMPRESA')->first()->valor;

        // Compartir la variable con todas las vistas
        view()->share('nombreEmpresa', $nombreEmpresa);

        return $next($request);
    }
}
