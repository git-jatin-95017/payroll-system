@if ($paginator->hasPages())
    <nav aria-label="Page navigation" class="custom-pagination">
        <ul class="pagination justify-content-end">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link page-prev" aria-hidden="true">
                        <x-bx-chevron-left class="w-20 h-20" />
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link page-prev" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                        <x-bx-chevron-left class="w-20 h-20" />
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><a class="page-link">{{ $element }}</a></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><a class="page-link">{{ $page }}</a></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link page-next" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                        <x-bx-chevron-right class="w-20 h-20" />
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link page-next" aria-hidden="true">
                        <x-bx-chevron-right class="w-20 h-20" />
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
