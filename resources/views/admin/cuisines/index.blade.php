@extends('layouts.app')

@section('title', 'Jenis Makanan — Marketplace Katering')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h1 class="h4 fw-bold mb-1"><i class="bi bi-tags me-1 text-brand"></i>Jenis Makanan</h1>
            <p class="text-muted small mb-0">
                Daftar ini dipakai di form registrasi katering, edit profil, dan filter pencarian customer.
            </p>
        </div>
    </div>

    <div class="row g-3">
        {{-- Form tambah jenis --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Tambah Jenis</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.cuisines.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label small text-muted">Nama jenis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}"
                                   placeholder="mis. Korea" maxlength="50" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-brand w-100">
                            <i class="bi bi-plus-lg me-1"></i>Tambah
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Daftar jenis --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th class="text-center">Dipakai Katering</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cuisines as $cuisine)
                                <tr class="align-middle">
                                    <td>
                                        <form method="POST" action="{{ route('admin.cuisines.update', $cuisine) }}"
                                              class="d-flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="name" class="form-control form-control-sm"
                                                   value="{{ $cuisine->name }}" maxlength="50" required
                                                   aria-label="Ubah nama {{ $cuisine->name }}">
                                            <button type="submit" class="btn btn-sm btn-outline-brand text-nowrap">Simpan</button>
                                        </form>
                                    </td>
                                    <td class="small text-center">{{ $cuisine->merchants_count }}</td>
                                    <td>
                                        @if ($cuisine->is_active)
                                            <span class="badge text-bg-success">Aktif</span>
                                        @else
                                            <span class="badge text-bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <form method="POST" action="{{ route('admin.cuisines.toggle', $cuisine) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                {{ $cuisine->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        @if ($cuisine->merchants_count === 0)
                                            <form method="POST" action="{{ route('admin.cuisines.destroy', $cuisine) }}" class="d-inline"
                                                  onsubmit="return confirm('Hapus jenis {{ $cuisine->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted small p-3">Belum ada jenis makanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
