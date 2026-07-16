<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guarda as rotas que ALTERAM dados (criar, editar, excluir) quando o usuário
 * não tem assinatura vigente nem período de teste ativo. As rotas de leitura
 * ficam livres: sem assinatura o app entra em modo leitura, e não em bloqueio.
 *
 * Deve rodar depois de 'auth' e 'verified'. A rota `planos` fica fora deste
 * middleware, senão o redirecionamento entraria em loop.
 */
class AssinaturaAtiva
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();

        if ($usuario && ! $usuario->podeEditar()) {
            return redirect()->route('planos')
                ->with('msg', 'Seu período de teste terminou. Assine o plano para voltar a lançar transações.');
        }

        return $next($request);
    }
}
