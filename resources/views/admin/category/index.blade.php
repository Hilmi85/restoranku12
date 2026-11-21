@extends('admin.layouts.master')
@section('title', 'Kategori')

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
                <h3>Kategori</h3>
                <p class="text-subtitle text-muted">Berbagai kategori pilihan</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active text-info font-bold" aria-current="page">DaftarKategori</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="card-title order-first">
                    <a href="{{ route('categories.create') }}" class="btn btn-primary float-start float-lg-end">
                        <i class="bi bi-plus"></i>
                        Tambah Kategori
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
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->cat_name }}</td>
                            <td>{{ Str::limit($category->description, 100) }}</td>
                            
                            <td class="text-nowrap">
                                <a href="{{ route('categories.edit', $category->id) }}"
                                class="badge bg-warning text-dark text-decoration-none">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <form action="{{ route('categories.destroy', $category->id) }}"
                                    method="POST"
                                    class="d-inline m-0"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="badge bg-danger border-0" style="cursor: pointer">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
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
