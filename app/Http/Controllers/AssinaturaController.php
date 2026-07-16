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
     * Tela de confirmação do cancelamento. Explica o que acontece antes de o
     * cliente confirmar: sem novas cobranças, acesso até o fim do período pago.
     */
    public function cancelar()
    {
        $usuario = Auth::user();

        if (! $usuario->podeCancelar()) {
            return redirect()->route('conta');
        }

        return view('assinatura.cancelar', ['usuario' => $usuario]);
    }

    /**
     * Encerra a assinatura no Mercado Pago. O acesso NÃO é revogado aqui: os
     * Termos garantem o uso até o fim do período já pago, e é o vencimento de
     * assinatura_ativa_ate que cuida disso.
     */
    public function cancelarConfirmado(MercadoPago $mp)
    {
        $usuario = Auth::user();

        if (! $usuario->podeCancelar()) {
            return redirect()->route('conta');
        }

        try {
            $mp->cancelarAssinatura($usuario->mp_preapproval_id);
        } catch (Throwable $e) {
            Log::error('Falha ao cancelar assinatura no Mercado Pago', [
                'usuario' => $usuario->id,
                'erro' => $e->getMessage(),
            ]);

            return redirect()->route('conta')
                ->with('msg', 'Não foi possível cancelar agora. Tente novamente em instantes ou fale com contato@economizacerto.com.br.');
        }

        $usuario->forceFill(['assinatura_cancelada_em' => now()])->save();

        return redirect()->route('conta')->with('msg', sprintf(
            'Assinatura cancelada. Não haverá novas cobranças e seu acesso continua até %s.',
            $usuario->assinatura_ativa_ate->format('d/m/Y'),
        ));
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
