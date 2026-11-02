@if ($paginator->hasPages())
    <div class="pagination-bar">
        <span>
            Affichage de <strong>{{ $paginator->firstItem() }}</strong> à <strong>{{ $paginator->lastItem() }}</strong>
            sur <strong>{{ $paginator->total() }}</strong>
        </span>
        <ul class="pagination">
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true"><span><x-icon name="chevron-left" class="icon-sm" /></span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Page précédente"><x-icon name="chevron-left" class="icon-sm" /></a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Page suivante"><x-icon name="chevron-right" class="icon-sm" /></a></li>
            @else
                <li class="disabled" aria-disabled="true"><span><x-icon name="chevron-right" class="icon-sm" /></span></li>
            @endif
        </ul>
    </div>
@endif
