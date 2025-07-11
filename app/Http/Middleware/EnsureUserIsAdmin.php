<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // verifica se o usuário está logado e se o role é admin
        // se não estiver logado, retorna acesso negado
        
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado');
        }
        return $next($request);
    }
}
