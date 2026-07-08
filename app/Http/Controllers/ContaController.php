<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

/**
 * Página "Minha conta": mostra os dados do cliente e a situação do plano.
 * Fica fora do middleware `assinatura` para que o usuário consiga ver o
 * status e renovar mesmo quando a assinatura está expirada.
 */
class ContaController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        return view('conta.index', [
            'usuario' => $usuario,
            'status' => $usuario->statusAssinatura(),
            'planoNome' => config('services.mercadopago.plano_nome'),
            'planoValor' => (float) config('services.mercadopago.plano_valor'),
        ]);
    }
}
