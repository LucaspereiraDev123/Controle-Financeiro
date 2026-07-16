<?php

namespace App\Http\Controllers;

use App\Services\MercadoPago;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Início do checkout de assinatura e retorno do Mercado Pago. O cliente chega
 * aqui pela página de planos (`SiteController::planos`).
 */
class AssinaturaController extends Controller
{
    /**
     * Cria a assinatura no Mercado Pago e envia o usuário para o checkout
     * seguro (onde ele digita o cartão).
     */
    public function checkout(MercadoPago $mp)
    {
        $usuario = Auth::user();

        // Só quem já paga é barrado, para não criar assinatura duplicada. Quem
        // está em trial pode assinar antes do prazo acabar.
        if ($usuario->statusAssinatura() === 'ativa') {
            return redirect()->route('dashboard')->with('msg', 'Sua assinatura já está ativa.');
        }

        try {
            $urlCheckout = $mp->criarAssinatura($usuario);
        } catch (Throwable $e) {
            Log::error('Falha no checkout do Mercado Pago', ['erro' => $e->getMessage()]);

            return redirect()->route('planos')
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

        return redirect()->route('planos')
            ->with('msg', 'Recebemos seu pagamento e estamos confirmando. Isso pode levar alguns instantes.');
    }
}
