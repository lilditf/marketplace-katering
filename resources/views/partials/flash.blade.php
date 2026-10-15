{{-- Pesan flash dari session: success (hijau) / error (merah) / info (biru).
     Semua output di-escape otomatis oleh {{ }} (anti XSS). --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show no-print" role="alert">
        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show no-print" role="alert">
        <i class="bi bi-exclamation-triangle me-1"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show no-print" role="alert">
        <i class="bi bi-info-circle me-1"></i>{{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif
