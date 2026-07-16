<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Integração com o Mercado Pago para assinatura recorrente (preapproval).
 *
 * O cliente digita o cartão na página segura do Mercado Pago (init_point) —
 * nenhum dado de cartão passa por este servidor. A confirmação da cobrança
 * chega por webhook, tratado no MercadoPagoWebhookController.
 */
class MercadoPago
{
    private const BASE = 'https://api.mercadopago.com';

    public function configurado(): bool
    {
        return ! empty(config('services.mercadopago.access_token'));
    }

    /**
     * Cria uma assinatura (preapproval) para o usuário e devolve a URL de
     * checkout do Mercado Pago (init_point) para onde ele deve ser enviado.
     */
    public function criarAssinatura(Usuario $usuario): string
    {
        if (! $this->configurado()) {
            throw new RuntimeException('Mercado Pago não configurado (defina MP_ACCESS_TOKEN).');
        }

        $resposta = Http::withToken(config('services.mercadopago.access_token'))
            ->acceptJson()
            ->post(self::BASE.'/preapproval', [
                'reason' => config('services.mercadopago.plano_nome'),
                'external_reference' => (string) $usuario->id,
                'payer_email' => $usuario->email,
                'back_url' => route('assinatura.retorno'),
                'status' => 'pending',
                'auto_recurring' => [
                    'frequency' => 1,
                    'frequency_type' => 'months',
                    'transaction_amount' => (float) config('services.mercadopago.plano_valor'),
                    'currency_id' => 'BRL',
                ],
            ]);

        if ($resposta->failed()) {
            throw new RuntimeException('Falha ao criar assinatura no Mercado Pago: '.$resposta->body());
        }

        $usuario->update(['mp_preapproval_id' => $resposta->json('id')]);

        return $resposta->json('init_point');
    }

    /**
     * Cancela a assinatura no Mercado Pago, encerrando as cobranças futuras.
     * Não estorna o que já foi pago — o acesso segue até o fim do período,
     * conforme os Termos.
     */
    public function cancelarAssinatura(string $preapprovalId): void
    {
        $resposta = Http::withToken(config('services.mercadopago.access_token'))
            ->acceptJson()
            ->put(self::BASE.'/preapproval/'.$preapprovalId, [
                'status' => 'cancelled',
            ]);

        if ($resposta->failed()) {
            throw new RuntimeException('Falha ao cancelar assinatura no Mercado Pago: '.$resposta->body());
        }
    }

    /**
     * Consulta uma assinatura (preapproval) diretamente na API — usado para
     * confirmar o status a partir de um webhook, sem confiar no corpo recebido.
     *
     * @return array<string, mixed>
     */
    public function consultarAssinatura(string $preapprovalId): array
    {
        $resposta = Http::withToken(config('services.mercadopago.access_token'))
            ->acceptJson()
            ->get(self::BASE.'/preapproval/'.$preapprovalId);

        if ($resposta->failed()) {
            throw new RuntimeException('Falha ao consultar assinatura no Mercado Pago: '.$resposta->body());
        }

        return $resposta->json();
    }
}
