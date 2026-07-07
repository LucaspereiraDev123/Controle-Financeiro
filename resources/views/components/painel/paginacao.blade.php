@props(['paginator'])
@if($paginator->hasPages())
    <div class="painel-paginacao">
        @if($paginator->onFirstPage())
            <span class="painel-pag-item desabilitado">Anterior</span>
        @else
            <a class="painel-pag-item" href="{{ $paginator->previousPageUrl() }}">Anterior</a>
        @endif

        <span class="painel-pag-info">Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}</span>

        @if($paginator->hasMorePages())
            <a class="painel-pag-item" href="{{ $paginator->nextPageUrl() }}">Próxima</a>
        @else
            <span class="painel-pag-item desabilitado">Próxima</span>
        @endif
    </div>
@endif
