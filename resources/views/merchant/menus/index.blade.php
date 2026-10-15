@extends('layouts.app')

@section('title', 'Menu Saya — Marketplace Katering')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h4 fw-bold mb-0"><i class="bi bi-egg-fried me-1 text-brand"></i>Menu Saya</h1>
        <a href="{{ route('merchant.menus.create') }}" class="btn btn-brand">
            <i class="bi bi-plus-lg me-1"></i>Tambah Menu
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 90px;">Foto</th>
                        <th>Menu</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga</th>
                        <th class="text-center">Dipesan</th>
                        <th class="text-center">Status</th>
                        <th class="text-end" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menus as $menu)
                        <tr>
                            <td>
                                @if ($menu->photo_path)
                                    {{-- photo_path sudah termasuk prefix folder "menus/"
                                         hasil Storage::store() — jangan ditambah prefix lagi. --}}
                                    <img src="{{ asset('storage/' . $menu->photo_path) }}" alt="Foto {{ $menu->name }}"
                                         class="menu-thumb border">
                                @else
                                    <div class="menu-thumb bg-light border d-flex align-items-center justify-content-center text-muted">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $menu->name }}</div>
                                <div class="small text-muted text-truncate" style="max-width: 280px;">{{ $menu->description }}</div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $menu->category }}</span></td>
                            <td class="text-end fw-semibold">Rp{{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td class="text-center small text-muted">{{ $menu->order_items_count }}x</td>
                            <td class="text-center">
                                {{-- Toggle ketersediaan: PATCH dalam form (bukan link GET) --}}
                                <form method="POST" action="{{ route('merchant.menus.toggle', $menu) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Klik untuk mengubah"
                                            class="btn btn-sm {{ $menu->is_available ? 'btn-outline-brand' : 'btn-outline-secondary' }}">
                                        <i class="bi {{ $menu->is_available ? 'bi-check-circle' : 'bi-pause-circle' }} me-1"></i>
                                        {{ $menu->is_available ? 'Tersedia' : 'Kosong' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('merchant.menus.edit', $menu) }}" class="btn btn-sm btn-outline-primary"
                                   title="Edit menu">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if ($menu->order_items_count > 0)
                                    {{-- Menu yang sudah pernah dipesan dikunci: menjaga riwayat invoice. --}}
                                    <button class="btn btn-sm btn-outline-secondary" disabled
                                            title="Tidak bisa dihapus: sudah pernah dipesan ({{ $menu->order_items_count }}x)">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                @else
                                    <form method="POST" action="{{ route('merchant.menus.destroy', $menu) }}" class="d-inline"
                                          onsubmit="return confirm('Hapus menu &quot;{{ $menu->name }}&quot;? Tindakan ini tidak bisa dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus menu">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-basket2 d-block mb-2" style="font-size: 2rem;"></i>
                                Belum ada menu. <a href="{{ route('merchant.menus.create') }}">Tambahkan menu pertama</a> agar
                                katering Anda bisa dipesan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($menus->hasPages())
            <div class="card-footer bg-white">
                {{ $menus->links() }}
            </div>
        @endif
    </div>
@endsection
