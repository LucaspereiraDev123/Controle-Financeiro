<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

/**
 * Tela de "assine para continuar", exibida quando o período de teste acaba e
 * não há assinatura ativa. Fica fora do middleware `assinatura` para não
 * entrar em loop de redirecionamento.
 */
class AssinaturaController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        // Quem ainda tem acesso não precisa desta tela.
        if ($usuario->assinaturaAtiva()) {
            return redirect()->route('dashboard');
        }

        return view('assinatura.expirada', [
            'status' => $usuario->statusAssinatura(),
        ]);
    }
}
