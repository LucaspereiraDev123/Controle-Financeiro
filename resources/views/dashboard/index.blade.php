@extends('layouts.painel')

@section('titulo', 'Início')
@section('nav_dashboard', 'ativo')
@section('cabecalho', 'Bem-vindo(a), ' . (auth()->user()->nome ?? ''))
@section('subcabecalho', 'Aqui está o resumo das suas finanças')

@section('topo_acoes')
    <a href="{{ route('transacoes.create') }}" class="painel-btn">+ Nova transação</a>
@endsection

@section('conteudo')
    @if (session('msg'))
        <div class="painel-alerta">{{ session('msg') }}</div>
    @endif

    {{-- Cards de resumo --}}
    <section class="painel-cards">
        <div class="painel-card painel-card-destaque">
            <span class="painel-card-rotulo">Saldo total</span>
            <strong class="painel-card-valor {{ ($saldo ?? 0) < 0 ? 'negativo' : 'positivo' }}">
                R$ {{ number_format($saldo ?? 0, 2, ',', '.') }}
            </strong>
        </div>
        <div class="painel-card">
            <span class="painel-card-rotulo">Receitas</span>
            <strong class="painel-card-valor positivo">R$ {{ number_format($receitas ?? 0, 2, ',', '.') }}</strong>
        </div>
        <div class="painel-card">
            <span class="painel-card-rotulo">Despesas</span>
            <strong class="painel-card-valor negativo">R$ {{ number_format($despesas ?? 0, 2, ',', '.') }}</strong>
        </div>
    </section>

    {{-- Gráfico --}}
    <section class="painel-bloco">
        <div class="painel-bloco-cabecalho">
            <h2>Evolução (últimos 6 meses)</h2>
        </div>
        <div class="painel-grafico">
            <canvas id="graficoFinancas"></canvas>
        </div>
    </section>

    {{-- Filtros --}}
    <section class="painel-bloco">
        <form method="GET" action="{{ route('filtro') }}" class="painel-filtros">
            <div class="painel-filtro">
                <label for="periodo">Período</label>
                <input type="month" name="periodo" id="periodo" value="{{ request('periodo') }}">
            </div>
            <div class="painel-filtro">
                <label for="entrada">Tipo</label>
                <select name="entrada" id="entrada">
                    <option value="">Todos</option>
                    <option value="Receitas" @selected(request('entrada') === 'Receitas')>Receitas</option>
                    <option value="Despesas" @selected(request('entrada') === 'Despesas')>Despesas</option>
                </select>
            </div>
            <div class="painel-filtro">
                <label for="categoria">Categoria</label>
                <select name="categoria" id="categoria">
                    <option value="">Todas</option>
                    @foreach($categorias as $c)
                        <option value="{{ $c->id }}" @selected((string) request('categoria') === (string) $c->id)>{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="painel-filtro">
                <label for="busca">Busca</label>
                <input type="text" name="busca" id="busca" placeholder="Ex: Netflix" value="{{ request('busca') }}">
            </div>
            <div class="painel-filtro">
                <button type="submit" class="painel-btn">Filtrar</button>
            </div>
        </form>
    </section>

    {{-- Tabela de transações --}}
    <section class="painel-bloco">
        <div class="painel-bloco-cabecalho">
            <h2>Transações</h2>
        </div>
        <div class="painel-tabela-wrap">
            <table class="painel-tabela">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th>Data</th>
                        <th>Opções</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transacoes as $t)
                        <tr>
                            <td>
                                <span class="painel-tag {{ $t->tipo === 'Receitas' ? 'tag-receita' : 'tag-despesa' }}">
                                    {{ $t->tipo }}
                                </span>
                            </td>
                            <td class="{{ $t->tipo === 'Receitas' ? 'positivo' : 'negativo' }}">
                                {{ $t->tipo === 'Receitas' ? '+' : '−' }} R$ {{ number_format($t->valor, 2, ',', '.') }}
                            </td>
                            <td>{{ $t->descricao }}</td>
                            <td>{{ $t->categoria->nome }}</td>
                            <td>{{ $t->created_at->format('d/m/Y') }}</td>
                            <td class="painel-tabela-acoes">
                                <a href="{{ route('transacoes.show', $t->id) }}">Exibir</a>
                                <a href="{{ route('transacoes.edit', $t->id) }}">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="painel-tabela-vazia">Nenhuma transação encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transacoes->hasPages())
            <div class="painel-paginacao">
                @if ($transacoes->onFirstPage())
                    <span class="painel-pag-item desabilitado">Anterior</span>
                @else
                    <a class="painel-pag-item" href="{{ $transacoes->previousPageUrl() }}">Anterior</a>
                @endif

                <span class="painel-pag-info">Página {{ $transacoes->currentPage() }} de {{ $transacoes->lastPage() }}</span>

                @if ($transacoes->hasMorePages())
                    <a class="painel-pag-item" href="{{ $transacoes->nextPageUrl() }}">Próxima</a>
                @else
                    <span class="painel-pag-item desabilitado">Próxima</span>
                @endif
            </div>
        @endif
    </section>
@endsection

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@endpush

@push('scripts')
    <script>
        (function () {
            const ctx = document.getElementById('graficoFinancas');
            if (!ctx || typeof Chart === 'undefined') return;

            const dados = @json($grafico);

            const areaReceitas = ctx.getContext('2d').createLinearGradient(0, 0, 0, 260);
            areaReceitas.addColorStop(0, 'rgba(34, 197, 94, 0.35)');
            areaReceitas.addColorStop(1, 'rgba(34, 197, 94, 0)');

            const areaDespesas = ctx.getContext('2d').createLinearGradient(0, 0, 0, 260);
            areaDespesas.addColorStop(0, 'rgba(239, 68, 68, 0.35)');
            areaDespesas.addColorStop(1, 'rgba(239, 68, 68, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dados.labels,
                    datasets: [
                        {
                            label: 'Receitas',
                            data: dados.receitas,
                            borderColor: '#22c55e',
                            backgroundColor: areaReceitas,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#22c55e',
                        },
                        {
                            label: 'Despesas',
                            data: dados.despesas,
                            borderColor: '#ef4444',
                            backgroundColor: areaDespesas,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#ef4444',
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: {
                            labels: { color: '#c3ccde', usePointStyle: true, boxWidth: 8 },
                        },
                        tooltip: {
                            callbacks: {
                                label: (c) => c.dataset.label + ': R$ ' +
                                    c.parsed.y.toLocaleString('pt-BR', { minimumFractionDigits: 2 }),
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255,255,255,0.05)' },
                            ticks: { color: '#8b97ad' },
                        },
                        y: {
                            grid: { color: 'rgba(255,255,255,0.05)' },
                            ticks: {
                                color: '#8b97ad',
                                callback: (v) => 'R$ ' + v.toLocaleString('pt-BR'),
                            },
                        },
                    },
                },
            });
        })();
    </script>
@endpush
