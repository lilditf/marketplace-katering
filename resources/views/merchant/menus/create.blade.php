@extends('layouts.app')

@section('title', 'Tambah Menu — Marketplace Katering')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('merchant.menus.index') }}">Menu Saya</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Menu</li>
                </ol>
            </nav>

            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-plus-circle me-1 text-success"></i>Tambah Menu Baru
                </div>
                <div class="card-body p-4">
                    {{-- enctype multipart wajib untuk upload foto --}}
                    <form method="POST" action="{{ route('merchant.menus.store') }}" enctype="multipart/form-data" novalidate>
                        @csrf
                        @include('merchant.menus._form', ['menu' => null])

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-save me-1"></i>Simpan Menu
                            </button>
                            <a href="{{ route('merchant.menus.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
