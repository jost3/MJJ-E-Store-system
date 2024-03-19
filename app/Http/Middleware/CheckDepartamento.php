<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckDepartamento
{
    public function handle($request, Closure $next, $departamento)
    {
        $user = Auth::user();

        if ($user && $user->departamento->nombre === $departamento) {
            return $next($request);
        }

        return response()->json(['status' => false, 'message' => 'Acceso no autorizado'], 403);
    }
}
