<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/Logo 2.png') }}" type="image/png" sizes="64x64">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Editar</title>
</head>
<body>
<div class="fundo">
        <div class="fundo-caixa">
            <form class="fundo-caixa-formulario" method="POST" action="{{ route('transacoes.update', $transacao->id) }}">
                @csrf
                @method('PUT')
            
                    <h2>Editar Transação</h2>

                    @if ($errors->any())
                        <ul class="erros">
                            @foreach ($errors->all() as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <label for="tipo">Tipo:</label>
                    <select name="tipo">
                        <option value="Receitas" {{ $transacao->tipo == 'Receitas' ? 'selected' : '' }}>Receitas</option>
                        <option value="Despesas" {{ $transacao->tipo == 'Despesas' ? 'selected' : '' }}>Despesas</option>
                    </select>

                    <label for="descricao">Descrição:</label>
                    <input type="text" name="descricao" value="{{ $transacao->descricao }}">

                    <label for="valor">Valor:</label>
                    <input type="number" step="0.01" min="0" name="valor" value="{{ $transacao->valor }}">

                    <div class="fundo-caixa-formulario-selecoes">
                        <select name="categoria_id">
                            @foreach ($categorias as $c)
                                <option
                                value="{{ $c->id }}"
                                {{ $c->id == $transacao->categoria_id ? 'selected' : '' }}>
                                {{ $c->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fundo-caixa-formulario-botoes">
                        <button type="submit" value="Salvar">Salvar</button>
                        <button form="deletar" type="submit" value="Excluir">Excluir</button>
                    </div>
                    <a href="{{ route('dashboard') }}">Voltar</a>
            </form>

            <form method="POST" id="deletar" action="{{ route('transacoes.destroy', $transacao->id) }}">
                @csrf
                @method('DELETE')
            </form>
            
        </div>
</div>
</body>
</html>

    
