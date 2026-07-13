<x-painel.layout
    titulo="Início"
    cabecalho="Bem-vindo(a), {{ auth()->user()->nome ?? '' }}"
    subcabecalho="Aqui está o resumo das suas finanças"
    nav="dashboard"
>
    <x-slot:acoes>
        <x-painel.botao :href="route('transacoes.create')">+ Nova transação</x-painel.botao>
    </x-slot:acoes>

    @if (session('msg'))
        <div class="painel-alerta">{{ session('msg') }}</div>
    @endif

    {{-- Cards de resumo --}}
    <section class="painel-cards">
        <x-painel.card
            rotulo="Saldo total"
            valor="R$ {{ number_format($saldo ?? 0, 2, ',', '.') }}"
            :tom="($saldo ?? 0) < 0 ? 'negativo' : 'positivo'"
            destaque
        />
        <x-painel.card rotulo="Receitas" valor="R$ {{ number_format($receitas ?? 0, 2, ',', '.') }}" tom="positivo" />
        <x-painel.card rotulo="Despesas" valor="R$ {{ number_format($despesas ?? 0, 2, ',', '.') }}" tom="negativo" />
    </section>

    {{-- Gráfico --}}
    <x-painel.bloco titulo="Evolução (últimos 6 meses)">
        <div class="painel-grafico">
            <canvas id="graficoFinancas"></canvas>
        </div>
    </x-painel.bloco>

    {{-- Filtros --}}
    <x-painel.bloco>
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
                <x-painel.botao type="submit">Filtrar</x-painel.botao>
            </div>
        </form>
    </x-painel.bloco>

    {{-- Tabela de transações --}}
    <x-painel.bloco titulo="Transações">
        <x-painel.tabela :colunas="['Tipo', 'Valor', 'Descrição', 'Categoria', 'Data', 'Opções']">
            @forelse($transacoes as $t)
                <tr>
                    <td><x-painel.tag :tipo="$t->tipo" /></td>
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
        </x-painel.tabela>

        <x-painel.paginacao :paginator="$transacoes" />
    </x-painel.bloco>

    <x-slot:head>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    </x-slot:head>

    <x-slot:scripts>
        <script>
            (function () {
                const ctx = document.getElementById('graficoFinancas');
                if (!ctx || typeof Chart === 'undefined') return;

                const dados = @json($grafico);

                const areaReceitas = ctx.getContext('2d').createLinearGradient(0, 0, 0, 260);
                areaReceitas.addColorStop(0, 'rgba(59, 109, 17, 0.28)');
                areaReceitas.addColorStop(1, 'rgba(59, 109, 17, 0)');

                const areaDespesas = ctx.getContext('2d').createLinearGradient(0, 0, 0, 260);
                areaDespesas.addColorStop(0, 'rgba(12, 68, 124, 0.28)');
                areaDespesas.addColorStop(1, 'rgba(12, 68, 124, 0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: dados.labels,
                        datasets: [
                            {
                                label: 'Receitas',
                                data: dados.receitas,
                                borderColor: '#3B6D11',
                                backgroundColor: areaReceitas,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 3,
                                pointBackgroundColor: '#3B6D11',
                            },
                            {
                                label: 'Despesas',
                                data: dados.despesas,
                                borderColor: '#0C447C',
                                backgroundColor: areaDespesas,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 3,
                                pointBackgroundColor: '#0C447C',
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: {
                                labels: { color: '#5F5E5A', usePointStyle: true, boxWidth: 8 },
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
                                grid: { color: 'rgba(23,52,4,0.06)' },
                                ticks: { color: '#5F5E5A' },
                            },
                            y: {
                                grid: { color: 'rgba(23,52,4,0.06)' },
                                ticks: {
                                    color: '#5F5E5A',
                                    callback: (v) => 'R$ ' + v.toLocaleString('pt-BR'),
                                },
                            },
                        },
                    },
                });
            })();
        </script>
    </x-slot:scripts>
</x-painel.layout>
