<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Convertir los roles permitidos en un array plano (por si se pasan separados por coma)
        $allowedRoles = [];
        foreach ($roles as $roleArg) {
            $allowedRoles = array_merge($allowedRoles, explode(',', $roleArg));
        }

        // Verificar si el rol del usuario está dentro de los permitidos
        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}