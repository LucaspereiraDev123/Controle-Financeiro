<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('images/Logo 2.png') }}" type="image/png" sizes="64x64">
    <title>Dashboard</title>
</head>
<body>
    <div class="fundo">
        <div class="fundo-cabecalho">
            <nav>
                <div class="fundo-cabecalho-navegacao">
                    <div class="fundo-cabecalho-navegacao-opcoes">
                        <ul>
                            {{-- Links para o cadastro --}}
                            <li>
                                <a href="{{ route('dashboard') }}">Início</a>
                            </li>
                            <li>
                                <a href="{{ route('categorias.index') }}">Categorias</a>
                            </li>
                            <li>
                                <a href="{{ route('transacoes.create') }}">+Nova Transação</a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}">Sair</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <form method="GET" action=" {{ route('filtro') }}">
            <div class="fundo-filtro">
                <div class="fundo-filtro-grupo">
                    <label for="periodo">Período</label>
                    <input type="month" name="periodo">
                </div>

                <div class="fundo-filtro-grupo">
                    <label for="entrada">Entrada</label>
                    <select name="entrada">
                        <option value="">Todos</option>
                        <option value="Receitas">Receitas</option>
                        <option value="Despesas">Despesas</option>
                    </select>
                </div>

                <div class="fundo-filtro-grupo">
                    <label for="categoria">Categoria(Nome da categoria)</label>
                    <select name="categoria">
                        <option value="">Todos</option>
                        @foreach($categorias as $c)
                            <option value="{{ $c->id }}">{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="fundo-filtro-grupo">
                    <label for="busca">Busca por descrição</label>
                    <input type="text" name="busca" placeholder="Ex: Netflix">
                </div>

                <div class="fundo-filtro-grupo">
                    <label>&nbsp;</label>
                    <button>Filtrar</button>
                </div>
            </div>
        </form>

        @if (session('msg'))
            <div class="fundo-mensagem">{{ session('msg') }}</div>
        @endif

        <div class="fundo-soma">
            <div class="fundo-soma-total">
                <h3>TOTAL</h3>
                <p>R$ {{ number_format($saldo ?? 0, 2, ',', '.') }}</p>
            </div>
            <div class="fundo-soma-receitas">
                <h3>RECEITAS</h3>
                <p>R$ {{ number_format($receitas ?? 0, 2, ',', '.') }}</p>
            </div>
            <div class="fundo-soma-despesas">
                <h3>DESPESAS</h3>
                <p>R$ {{ number_format($despesas ?? 0, 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="fundo-principal">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Tipo</th>
                            <th scope="col">Valor R$</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Data</th>
                            <th scope="col">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transacoes as $t)
                            <tr>
                                <th scope="row">{{ $t->tipo }}</th>
                                <td>{{ $t->valor }}</td>
                                <td>{{ $t->descricao }}</td>
                                <td>{{ $t->categoria->nome }}</td>
                                <td>{{ $t->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('transacoes.show', $t->id) }}">Exibir</a> |
                                    <a href="{{ route('transacoes.edit', $t->id) }}">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($transacoes->hasPages())
                    <div class="paginacao">
                        @if ($transacoes->onFirstPage())
                            <span class="paginacao-item paginacao-desabilitado">Anterior</span>
                        @else
                            <a class="paginacao-item" href="{{ $transacoes->previousPageUrl() }}">Anterior</a>
                        @endif

                        <span class="paginacao-info">
                            Página {{ $transacoes->currentPage() }} de {{ $transacoes->lastPage() }}
                        </span>

                        @if ($transacoes->hasMorePages())
                            <a class="paginacao-item" href="{{ $transacoes->nextPageUrl() }}">Próxima</a>
                        @else
                            <span class="paginacao-item paginacao-desabilitado">Próxima</span>
                        @endif
                    </div>
                @endif
        </div>
    </div>
</body>
</html>