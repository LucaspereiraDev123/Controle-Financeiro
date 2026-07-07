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
                <!-- estou usando o verbo put porque estou pegando os dados -->
                <form class="fundo-caixa-formulario" id="formularioId" method="POST" 
                action="{{ route('categorias.update', $categoria->id) }}"> 
                        @csrf
                        @method('PUT')
                                <h1>Atualizar Categoria</h1>

                                @if ($errors->any())
                                    <ul class="erros">
                                        @foreach ($errors->all() as $erro)
                                            <li>{{ $erro }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                <label for="nome">Nome:</label>
                                <input type="text" name="nome" value="{{ $categoria->nome }}">

                                <label for="tipo">Tipo:</label>
                                <select name="tipo">
                                    <option value="Receitas" {{ $categoria->tipo == 'Receitas' ? 'selected' : '' }}>Receitas</option>
                                    <option value="Despesas" {{ $categoria->tipo == 'Despesas' ? 'selected' : '' }}>Despesas</option>
                                </select>

                                <!-- quando eu colocar um button fora do form tenho que me atentar se estou chamando o id do formulario com a ação correta -->
                                <div class="fundo-caixa-formulario-botoes">
                                        <button form="formularioId" type="submit">Salvar</button>
                                        <button form="deletarFormulario" type="submit" value="Excluir">Excluir</button>
                                </div>
                                <a href="{{ route('categorias.index') }}">Voltar</a>
                </form>

                <form class="formulario" id="deletarFormulario" method="POST"
                        action=" {{ route('categorias.destroy', $categoria->id) }}">
                        @csrf
                        <!-- se eu entrar nesse form ele vai atribuir o metodo delete -->
                        @method('DELETE')
                </form>
        </div>
</div>        
</body>
</html>