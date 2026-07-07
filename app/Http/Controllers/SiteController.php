<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

/**
 * Páginas públicas (site institucional): landing, planos e documentos legais.
 * Não exigem autenticação.
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

    public function planos()
    {
        return view('site.planos');
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
