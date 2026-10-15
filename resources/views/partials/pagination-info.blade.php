{{-- Info rentang data + navigasi halaman. Variabel: $rows (LengthAwarePaginator). --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
    <span class="dt-info">
        Menampilkan {{ $rows->firstItem() ?? 0 }}&ndash;{{ $rows->lastItem() ?? 0 }} dari {{ $rows->total() }} data
    </span>
    {{ $rows->links() }}
</div>
