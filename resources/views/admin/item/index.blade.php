@extends('admin.layouts.master')
@section('title', 'Daftar Menu')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/compiled/css/table-datatable.css') }}">
<style>
    .dataTable-dropdown .dataTable-selector {
        width: auto !important;
        min-width: 4rem;
    }
</style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Daftar Menu</h3>
                <p class="text-subtitle text-muted">Berbagai menu pilihan</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active text-info font-bold" aria-current="page">DaftarMenu</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="card-title order-first">
                    <a href="{{ route('items.create') }}" class="btn btn-primary float-start float-lg-end">
                        <i class="bi bi-plus"></i>
                        Tambah Menu
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i>  {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama Item</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ asset('img_item_upload/'. $item->img) }}" class="img-fluid rounded-top" alt="" style="max-width: 100px; height: auto;" onerror="this.onerror=null;this.src='{{ $item->img }}';">
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>{{ Str::limit($item->description, 100) }}</td>
                            <td>{{ 'Rp'. number_format($item->price, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $categoryName = $item->category?->cat_name;
                                    $categoryBadgeClass = match ($categoryName) {
                                        'Makanan' => 'bg-warning text-dark',
                                        'Minuman' => 'bg-info text-dark',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $categoryBadgeClass }}">
                                    {{ $categoryName ?? 'Kategori tidak tersedia'}}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $item->is_active == 1 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $item->is_active == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <a href="{{ route('items.edit', $item->id) }}"
                                class="badge bg-warning text-dark text-decoration-none mb-1">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>       
                                @if ($item->is_active == 1)
                                    <form action="{{ route('items.updateStatus', $item->id) }}" method="POST" onsubmit="return confirm('Status item saat ini AKTIF. Lanjutkan untuk mengubahnya menjadi TIDAK AKTIF?')">
                                        @csrf
                                        <input type="hidden" name="is_active" value="0">
                                        <button type="submit" class="badge bg-danger border-0">
                                            <i class="bi bi-x-circle"></i> Nonaktifkan
                                        </button>
                                    </form> 
                                @else
                                    <form action="{{ route('items.updateStatus', $item->id) }}" method="POST" onsubmit="return confirm('Status item saat ini TIDAK AKTIF. Lanjutkan untuk mengubahnya menjadi AKTIF?')">
                                        @csrf
                                        <input type="hidden" name="is_active" value="1">
                                        <button type="submit" class="badge bg-success border-0">
                                            <i class="bi bi-check"></i> Aktifkan
                                        </button> 
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/admin/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/admin/static/js/pages/simple-datatables.js') }}"></script>
@endsection
