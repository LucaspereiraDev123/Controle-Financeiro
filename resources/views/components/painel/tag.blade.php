@props(['tipo'])
<span class="painel-tag {{ $tipo === 'Receitas' ? 'tag-receita' : 'tag-despesa' }}">{{ $tipo }}</span>
