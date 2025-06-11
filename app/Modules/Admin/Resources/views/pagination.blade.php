@if ($paginator->hasPages())
    <ul class="pagination pagination-rounded align-self-center px-2 py-2">
       
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link">← Prev</span></li>
        @else
            <li class="page-item"><a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page-link">← Prev</a></li>
        @endif


        @foreach ($elements as $element)
           
            @if (is_string($element))
                <li class="page-item disabled"><span>{{ $element }}</span></li>
            @endif
           
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a href="{{ $url }}" class="page-link">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        
        @if ($paginator->hasMorePages())
            <li class="page-item"><a href="{{ $paginator->nextPageUrl() }}" rel="next" class="page-link">Next →</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">Next →</span></li>
        @endif
        
    </ul>
@endif 