<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsMod
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // o admin ta aqui para entrar em rotas que o mod também está autorizado
        if (!$user || !in_array($user->role, ['ADMIN', 'MODERATOR'])) {
            abort(403, 'Acesso negado');
        }

        return $next($request);
    }

}
