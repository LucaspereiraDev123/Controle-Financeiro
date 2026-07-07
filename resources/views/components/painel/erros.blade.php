@if($errors->any())
    <ul class="painel-erros">
        @foreach($errors->all() as $erro)
            <li>{{ $erro }}</li>
        @endforeach
    </ul>
@endif
