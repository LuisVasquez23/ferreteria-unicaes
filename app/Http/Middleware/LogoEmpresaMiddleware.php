<?php

namespace App\Http\Middleware;

use App\Models\Catalogo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class LogoEmpresaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener el nombre del archivo del logo de la empresa desde la base de datos
        $nombreArchivoLogo = Catalogo::where('nombre', 'LOGO_EMPRESA')->first()->valor;

        // Generar la URL del logo de la empresa utilizando asset()
        $urlLogoEmpresa = asset('storage/upload/' . $nombreArchivoLogo);

        // Compartir la URL del logo de la empresa con todas las vistas
        view()->share('urlLogoEmpresa', $urlLogoEmpresa);

        return $next($request);
    }
}
