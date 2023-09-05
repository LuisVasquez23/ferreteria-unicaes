<?php

namespace App\Http\Middleware;

use App\Models\DetalleRole;
use App\Models\MenuOption;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$rolesP)
    {
        if (Auth::check()) {
            // Obtén el usuario autenticado
            $user = Auth::user();

            // Obtén el ID del usuario
            $userId = $user->usuario_id;

            // Obtén todos los roles del usuario a través de la relación
            $todosRoles = DetalleRole::where('usuario_id', $userId)->with('role')->get();

            // Pluck para obtener solo el campo 'role' de la relación
            $roles = $todosRoles->pluck('role.role')->toArray();

            // Verifica si el usuario tiene al menos uno de los roles permitidos
            foreach ($rolesP as $role) {
                if (in_array($role, $roles)) {
                    return $next($request);
                }
            }
        }

        return redirect()->route('dashboard');
    }
}
