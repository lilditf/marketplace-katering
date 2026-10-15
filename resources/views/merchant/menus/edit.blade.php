@extends('layouts.portal')

@section('title', 'Edit Menu — Marketplace Katering')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('merchant.menus.index') }}">Menu Saya</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit: {{ $menu->name }}</li>
                </ol>
            </nav>

            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-pencil-square me-1 text-brand"></i>Edit Menu
                </div>
                <div class="card-body p-4">
                    {{-- PUT disimulasikan via _method karena form HTML hanya mengenal GET/POST --}}
                    <form method="POST" action="{{ route('merchant.menus.update', $menu) }}" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')
                        @include('merchant.menus._form', ['menu' => $menu])

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-brand px-4">
                                <i class="bi bi-save me-1"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('merchant.menus.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
