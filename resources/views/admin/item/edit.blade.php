@extends('admin.layouts.master')
@section('title', 'Edit Menu')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Edit Menu</h3>
            <p class="text-subtitle text-muted">Edit menu yang ada</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('items.index') }}">Daftar Menu</a></li>
                    <li class="breadcrumb-item active text-info font-bold" aria-current="page">Edit Menu</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form class="form" action="{{ route('items.update', $item->id) }}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('PUT')
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Nama Menu</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Input Nama Menu" required value="{{ $item->name }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" name="description" id="description" placeholder="Input Deskripsi" required>{{ $item->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="number" class="form-control" name="price" id="price" placeholder="Input Harga" required value="{{ $item->price }}">
                        </div>
                        <div class="form-group">
                            <label for="category">Kategori</label>
                            <select class="form-control" id="category" name="category_id" required>
                                <option value="" disabled>Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>{{ $category->cat_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image">Gambar</label>
                            @if ($item->img)
                                <div class="mb-2">
                                    <img src="{{ asset('img_item_upload/' . $item->img) }}" alt="{{ $item->name }}" class="img-thumbnail" style="max-width: 100px;" onerror="this.onerror=null;this.src='{{ $item->img }}';">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="image" name="img" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="is_active">Status</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" class="form-check-input" id="flexSwitchCheckChecked" name="is_active" value="1" {{ $item->is_active == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexSwitchCheckChecked">Aktif</label>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                            <a href="{{ route('items.index') }}" class="btn btn-danger mb-1">Batal</a>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
