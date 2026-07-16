<?php

namespace App\Http\Controllers;

use App\Services\MercadoPago;
use Illuminate\Support\Facades\Auth;

/**
 * Páginas públicas (site institucional): landing, planos e documentos legais.
 * Nenhuma exige autenticação, mas `planos` se adapta a quem já é cliente.
 */
class SiteController extends Controller
{
    /**
     * Landing page. Usuário já autenticado é levado direto ao dashboard.
     */
    public function home()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('site.home');
    }

    /**
     * Visitante vê a página de preços do site; cliente logado vê o plano dentro
     * do painel, com o botão de assinar. É a mesma rota porque a sidebar e o
     * rodapé já apontam para cá.
     */
    public function planos(MercadoPago $mp)
    {
        $usuario = Auth::user();

        if (! $usuario) {
            return view('site.planos');
        }

        return view('planos.painel', [
            'usuario' => $usuario,
            'status' => $usuario->statusAssinatura(),
            'mpConfigurado' => $mp->configurado(),
        ]);
    }

    public function privacidade()
    {
        return view('site.privacidade');
    }

    public function termos()
    {
        return view('site.termos');
    }
}
