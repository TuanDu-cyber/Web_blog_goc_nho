@if ($paginator->hasPages())
<nav class="blog-pagination" role="navigation" aria-label="Phân trang">

    @if ($paginator->onFirstPage())
    <span class="page-item disabled">&laquo; Trước</span>
    @else
    <a class="page-item" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Trước</a>
    @endif

    @foreach ($elements as $element)
    @if (is_string($element))
    <span class="page-item disabled">{{ $element }}</span>
    @endif

    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <span class="page-item active">{{ $page }}</span>
    @else
    <a class="page-item" href="{{ $url }}">{{ $page }}</a>
    @endif
    @endforeach
    @endif
    @endforeach

    @if ($paginator->hasMorePages())
    <a class="page-item" href="{{ $paginator->nextPageUrl() }}" rel="next">Sau &raquo;</a>
    @else
    <span class="page-item disabled">Sau &raquo;</span>
    @endif

</nav>
@endif