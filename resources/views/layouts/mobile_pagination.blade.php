@if ($paginator->hasPages())
    <ul class="pagination pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link">‹‹</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹‹</a>
            </li>
        @endif

        @if ($paginator->currentPage() > 0)
            {{-- <li class="page-item mobile-offcanvas"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li> --}}
            @if ($paginator->currentPage() == 1 || $paginator->currentPage() == 2)
                <li class="page-item full-screen-off"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
            @endif
        @endif
        @if ($paginator->currentPage() > 0)
            @if ($paginator->currentPage() == 1 || $paginator->currentPage() == 2)
                <li class="page-item full-screen-off"><span class="page-link">...</span></li>
            @else
                <li class="page-item"><span class="page-link">...</span></li>
            @endif
        @endif
        @foreach (range(1, $paginator->lastPage()) as $i)
            @if ($i >= $paginator->currentPage() - 1 && $i <= $paginator->currentPage() + 10)
                @if ($i == $paginator->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                @else
                    @if ($i >= $paginator->currentPage() - 0 && $i <= $paginator->currentPage() + 0)
                        <li class="page-item"><a class="page-link"
                                href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                    @elseif ($i >= $paginator->currentPage() - 1 && $i <= $paginator->currentPage() + 10)
                        <li class="page-item   mobile-offcanvas"><a class="page-link"
                                href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endif
            @endif
        @endforeach
        @if ($paginator->currentPage() <= $paginator->lastPage())
            @if ($paginator->currentPage() == $paginator->lastPage() || $paginator->lastPage() - $paginator->currentPage() == 2)
                <li class="page-item full-screen-off"><span class="page-link">...</span></li>
            @else
                <li class="page-item"><span class="page-link">...</span></li>
            @endif
        @endif
        @if ($paginator->currentPage() <= $paginator->lastPage())
            @if ($paginator->currentPage() == $paginator->lastPage() || $paginator->lastPage() - $paginator->currentPage() == 2)
                <li class="page-item full-screen-off"><a class="page-link"
                        href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
            @else
                <li class="page-item"><a class="page-link"
                        href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
            @endif
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">››</a>
            </li>
        @else
            <li class="page-item disabled"><span class="page-link">››</span></li>
        @endif
    </ul>
@endif
