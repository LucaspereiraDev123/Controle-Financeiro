<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Services\MercadoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Recebe as notificações (webhooks) do Mercado Pago sobre a assinatura.
 *
 * Nunca confiamos no corpo recebido: usamos apenas o id enviado para
 * consultar o status real na API do MP. Se a assinatura estiver "authorized",
 * ativamos/estendemos o acesso do usuário por mais um ciclo.
 */
class MercadoPagoWebhookController extends Controller
{
    public function __invoke(Request $request, MercadoPago $mp)
    {
        // O MP envia o id do preapproval em diferentes formatos conforme o evento.
        $preapprovalId = $request->input('data.id')
            ?? $request->input('id')
            ?? $request->query('id');

        $tipo = $request->input('type') ?? $request->input('topic');

        // Só tratamos eventos de assinatura (preapproval).
        if (! $preapprovalId || ! str_contains((string) $tipo, 'preapproval')) {
            return response()->json(['ignorado' => true]);
        }

        try {
            $assinatura = $mp->consultarAssinatura($preapprovalId);
        } catch (Throwable $e) {
            Log::error('Webhook MP: falha ao consultar assinatura', ['erro' => $e->getMessage()]);

            // 200 evita reentrega infinita; o próximo webhook/consulta reprocessa.
            return response()->json(['erro' => 'consulta_falhou']);
        }

        $usuario = Usuario::where('mp_preapproval_id', $preapprovalId)->first()
            ?? Usuario::find($assinatura['external_reference'] ?? null);

        if (! $usuario) {
            Log::warning('Webhook MP: usuário não encontrado', ['preapproval' => $preapprovalId]);

            return response()->json(['usuario' => 'nao_encontrado']);
        }

        $usuario->mp_preapproval_id = $preapprovalId;

        if (($assinatura['status'] ?? null) === 'authorized') {
            // Estende o acesso por um ciclo a partir de agora.
            $usuario->assinatura_ativa_ate = now()->addMonth();
        }

        $usuario->save();

        return response()->json(['ok' => true]);
    }
}
