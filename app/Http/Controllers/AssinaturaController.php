<?php

namespace App\Http\Controllers;

use App\Services\MercadoPago;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Tela de "assine para continuar" e início do checkout de assinatura.
 * Fica fora do middleware `assinatura` para não entrar em loop de
 * redirecionamento.
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
            'mpConfigurado' => app(MercadoPago::class)->configurado(),
        ]);
    }

    /**
     * Cria a assinatura no Mercado Pago e envia o usuário para o checkout
     * seguro (onde ele digita o cartão).
     */
    public function checkout(MercadoPago $mp)
    {
        $usuario = Auth::user();

        if ($usuario->assinaturaAtiva()) {
            return redirect()->route('dashboard');
        }

        try {
            $urlCheckout = $mp->criarAssinatura($usuario);
        } catch (Throwable $e) {
            Log::error('Falha no checkout do Mercado Pago', ['erro' => $e->getMessage()]);

            return redirect()->route('assinatura.expirada')
                ->with('msg', 'Não foi possível iniciar o pagamento agora. Tente novamente em instantes.');
        }

        return redirect()->away($urlCheckout);
    }

    /**
     * Página de retorno após o checkout do Mercado Pago. A ativação em si é
     * confirmada de forma assíncrona pelo webhook, então aqui apenas
     * informamos que o pagamento está sendo processado.
     */
    public function retorno()
    {
        $usuario = Auth::user();

        if ($usuario->assinaturaAtiva()) {
            return redirect()->route('dashboard')->with('msg', 'Assinatura ativada com sucesso. Bem-vindo(a) de volta!');
        }

        return redirect()->route('assinatura.expirada')
            ->with('msg', 'Recebemos seu pagamento e estamos confirmando. Isso pode levar alguns instantes.');
    }
}
