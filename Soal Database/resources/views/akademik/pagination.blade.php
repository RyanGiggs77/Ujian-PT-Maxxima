<div class="pagination">
    <span>{{ $paginator->total() }} data · Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>
    <div class="actions">
        @if($paginator->previousPageUrl())<a href="{{ $paginator->previousPageUrl() }}" rel="prev">← Sebelumnya</a>@endif
        @if($paginator->nextPageUrl())<a href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya →</a>@endif
    </div>
</div>
