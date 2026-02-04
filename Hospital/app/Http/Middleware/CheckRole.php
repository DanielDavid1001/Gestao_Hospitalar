<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Se o usuário não estiver autenticado, redireciona para login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Verifica se o role do usuário está na lista de roles permitidos
        if (in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }

        // Se não tem permissão, redireciona com erro
        return redirect()->route('home')->with('error', 'Você não tem permissão para acessar este recurso.');
    }
}
