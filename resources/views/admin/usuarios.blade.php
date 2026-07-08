<x-admin.layout titulo="Usuários" cabecalho="Usuários" subcabecalho="Gerencie o acesso das contas" nav="usuarios">
    @if (session('msg'))
        <div class="painel-alerta">{{ session('msg') }}</div>
    @endif
    @if ($errors->any())
        <div class="painel-alerta painel-alerta-erro">{{ $errors->first() }}</div>
    @endif

    <x-painel.bloco>
        <x-slot:acoes>
            <form method="GET" action="{{ route('admin.usuarios') }}" class="admin-busca">
                <input type="search" name="busca" value="{{ $busca }}"
                    placeholder="Buscar por nome ou e-mail" class="admin-busca-campo">
                <x-painel.botao type="submit" variante="sec">Buscar</x-painel.botao>
            </form>
        </x-slot:acoes>

        <x-painel.tabela :colunas="['Usuário', 'Situação', 'Acesso até', 'Ações sobre o acesso']">
            @forelse ($usuarios as $u)
                <tr>
                    <td>
                        <strong>{{ $u->nome }}</strong><br>
                        <small class="painel-texto-mut">{{ $u->email }}</small>
                        @if ($u->is_admin)
                            <span class="conta-status conta-status--ativa">admin</span>
                        @endif
                    </td>
                    <td>
                        <span class="conta-status conta-status--{{ $u->statusAssinatura() }}">
                            @switch($u->statusAssinatura())
                                @case('ativa') Ativa @break
                                @case('trial') Teste @break
                                @default Expirada
                            @endswitch
                        </span>
                    </td>
                    <td>
                        @if ($u->assinatura_ativa_ate && $u->assinatura_ativa_ate->isFuture())
                            {{ $u->assinatura_ativa_ate->format('d/m/Y') }}
                        @elseif ($u->trial_ends_at && $u->trial_ends_at->isFuture())
                            {{ $u->trial_ends_at->format('d/m/Y') }} <small class="painel-texto-mut">(teste)</small>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <div class="admin-acoes">
                            <form method="POST" action="{{ route('admin.usuarios.estender', $u->id) }}" class="admin-form-inline">
                                @csrf
                                <input type="number" name="dias" value="30" min="1" max="3650"
                                    class="admin-dias" aria-label="Dias de acesso">
                                <button type="submit" class="painel-btn-sec">Liberar dias</button>
                            </form>
                            <form method="POST" action="{{ route('admin.usuarios.bloquear', $u->id) }}">
                                @csrf
                                <button type="submit" class="painel-btn-perigo">Bloquear</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="painel-tabela-vazia">Nenhum usuário encontrado.</td>
                </tr>
            @endforelse
        </x-painel.tabela>

        <x-painel.paginacao :paginator="$usuarios" />
    </x-painel.bloco>
</x-admin.layout>
