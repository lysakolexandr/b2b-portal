@if ($paginator->hasPages())
    <nav aria-label="Page navigation sample">
        <ul class="pagination">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><a class="page-link" href="#">{!! __('l.previous') !!}</a></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">{!! __('l.previous') !!}</a></li>
            @endif
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <a class="page-link" href="#">{{ $element }}</a>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><a class="page-link"
                                    href="{{ $url }}">{{ $page }}</a></li>
                        @else
                            <li class="page-item"><a class="page-link"
                                    href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link"
                        href="{{ $paginator->nextPageUrl() }}">{!! __('l.next') !!}</a></li>
            @else
                <li class="page-item disabled"><a class="page-link disable"
                        href="{{ $paginator->nextPageUrl() }}">{!! __('l.next') !!}</a></li>
            @endif
        </ul>
    </nav>
@endif
