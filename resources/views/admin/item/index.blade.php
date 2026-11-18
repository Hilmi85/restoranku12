@extends('admin.layouts.master')
@section('title', 'Category')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/compiled/css/table-datatables.css') }}">
<style>
    .item-actions {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .item-actions .btn {
        width: 4.5rem;
        padding: 0.25rem 0.4rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
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
                                <span class="badge {{ $item->category->cat_name == 'Makanan' ? 'bg-warning' : 'bg-info' }}">
                                    {{ $item->category->cat_name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $item->is_active == 1 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $item->is_active == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="item-actions">
                                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
                                            <i class="bi bi-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
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
