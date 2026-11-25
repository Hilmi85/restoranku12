@extends('admin.layouts.master')
@section('title', 'Ubah Kategori')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Ubah Kategori</h3>
            <p class="text-subtitle text-muted">Ubah kategori yang ada</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dasbor</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Daftar Kategori</a></li>
                    <li class="breadcrumb-item active text-info font-bold" aria-current="page">Ubah Kategori</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"> Gagal memperbarui data</h5>
                @foreach ($errors->all() as $error)
                    <li class="m-3">
                        <i class="bi bi-exclamation-triangle-fill"></i>  {{ $error }}
                    </li>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif
        <form class="form" action="{{ route('categories.update', $category->id) }}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('PUT')
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="cat_name">Nama Kategori</label>
                            <input type="text" class="form-control" name="cat_name" id="cat_name" placeholder="Masukkan nama kategori" required value="{{ $category->cat_name }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" name="description" id="description" placeholder="Masukkan deskripsi" required>{{ $category->description }}</textarea>
                        </div>
                        <div class="form-group d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Atur ulang</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-danger mb-1">Batal</a>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
