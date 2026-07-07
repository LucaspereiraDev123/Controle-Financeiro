<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloqueia o acesso às áreas do app quando o usuário não tem assinatura
 * vigente nem período de teste ativo, direcionando-o para a tela de
 * "assine para continuar". Deve rodar depois de 'auth' e 'verified'.
 */
class AssinaturaAtiva
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();

        if ($usuario && ! $usuario->assinaturaAtiva()) {
            return redirect()->route('assinatura.expirada');
        }

        return $next($request);
    }
}
