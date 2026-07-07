@props(['colunas' => []])
<div class="painel-tabela-wrap">
    <table class="painel-tabela">
        <thead>
            <tr>
                @foreach($colunas as $coluna)
                    <th>{{ $coluna }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
