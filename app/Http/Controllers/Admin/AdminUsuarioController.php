<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Gestão de usuários no painel de administração: listagem, busca e ações
 * manuais sobre o acesso (conceder/estender ou bloquear a assinatura).
 */
class AdminUsuarioController extends Controller
{
    public function index(Request $request)
    {
        $busca = trim((string) $request->query('busca', ''));

        $usuarios = Usuario::query()
            ->when($busca !== '', function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                    ->orWhere('email', 'like', "%{$busca}%");
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.usuarios', [
            'usuarios' => $usuarios,
            'busca' => $busca,
        ]);
    }

    /**
     * Concede ou estende o acesso do usuário por uma quantidade de dias,
     * a partir do maior valor entre "agora" e a validade atual.
     */
    public function estenderAcesso(Request $request, Usuario $usuario)
    {
        $dados = $request->validate([
            'dias' => ['required', 'integer', 'min:1', 'max:3650'],
        ]);

        $base = $usuario->assinatura_ativa_ate && $usuario->assinatura_ativa_ate->isFuture()
            ? $usuario->assinatura_ativa_ate
            : Carbon::now();

        $usuario->assinatura_ativa_ate = $base->copy()->addDays((int) $dados['dias']);
        $usuario->save();

        return back()->with('msg', "Acesso de {$usuario->email} liberado até "
            . $usuario->assinatura_ativa_ate->format('d/m/Y') . '.');
    }

    /**
     * Bloqueia o acesso do usuário encerrando tanto o teste quanto a
     * assinatura paga (ambos passam a estar no passado).
     */
    public function bloquearAcesso(Usuario $usuario)
    {
        $ontem = Carbon::now()->subDay();

        $usuario->assinatura_ativa_ate = $ontem;
        if ($usuario->trial_ends_at && $usuario->trial_ends_at->isFuture()) {
            $usuario->trial_ends_at = $ontem;
        }
        $usuario->save();

        return back()->with('msg', "Acesso de {$usuario->email} bloqueado.");
    }
}
