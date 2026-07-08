<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Support\Carbon;

/**
 * Painel de administração — visão geral com as métricas do negócio.
 */
class AdminController extends Controller
{
    public function dashboard()
    {
        $agora = Carbon::now();

        $total = Usuario::count();

        $ativos = Usuario::where('assinatura_ativa_ate', '>', $agora)->count();

        $emTeste = Usuario::where(function ($q) use ($agora) {
            $q->whereNull('assinatura_ativa_ate')->orWhere('assinatura_ativa_ate', '<=', $agora);
        })->where('trial_ends_at', '>', $agora)->count();

        $expirados = max(0, $total - $ativos - $emTeste);

        $planoValor = (float) config('services.mercadopago.plano_valor');

        return view('admin.dashboard', [
            'total' => $total,
            'ativos' => $ativos,
            'emTeste' => $emTeste,
            'expirados' => $expirados,
            'mrr' => $ativos * $planoValor,
            'novos30dias' => Usuario::where('created_at', '>=', $agora->copy()->subDays(30))->count(),
            'recentes' => Usuario::latest()->take(8)->get(),
        ]);
    }
}
