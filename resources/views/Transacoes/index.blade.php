@extends('base')
@section('principal')
    <h2>Transações Cadastradas</h2>

    @if ($transacoes->isEmpty())
        <h3>Nenhuma Transação encontrada! :/</h3>
    @else
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Descrição</th>
                <th>Valor R$</th>
                <th>Categoria</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transacoes as $t)
            <tr>
                <td>{{ $t->tipo }}</td>
                <td>{{ $t->descricao }}</td>
                <td>{{ number_format($t->valor, 2, ',', '.') }}</td>
                <td>{{ $t->categoria->nome }}</td>
                <td>
                    <a href="{{ route('transacoes.show', $t->id) }}">Mostrar</a> |
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
    @endif
@endsection
