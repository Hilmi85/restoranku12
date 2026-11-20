@extends('admin.layouts.master')
@section('title', 'Tambah Karyawan')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Tambah Karyawan</h3>
            <p class="text-subtitle text-muted">Tambahkan Karyawan Baru</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Daftar Karyawan</a></li>
                    <li class="breadcrumb-item active text-info font-bold" aria-current="page">Tambah Karyawan</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"> Submit Error!</h5>
                @foreach ($errors->all() as $error)
                    <li class="m-3">
                        <i class="bi bi-exclamation-triangle-fill"></i>  {{ $error }}
                    </li>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <form class="form" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="fullname">Nama Karyawan</label>
                            <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Input Nama Karyawan" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="Input Username" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">No. Telepon</label>
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="Input No. Telepon" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role_id" required>
                                <option value="" disabled selected>Pilih Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" name="email" id="email" placeholder="Input Email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Input Password" required>
                            <small><a href="#" class="toggle-password" data-target="password">Lihat Password</a></small>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Input Konfirmasi Password" required>
                            <small><a href="#" class="toggle-password" data-target="password_confirmation">Lihat Password</a></small>
                        </div>
                        
                        <div class="form-group d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                            <a href="{{ route('users.index') }}" class="btn btn-danger mb-1">Batal</a>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(el => {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            let input = document.getElementById(this.dataset.target);
            let isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            document.querySelector('a[data-target="{this.dataset.target}"]').textContent = isHidden ? 'Sembunyikan Password' : 'Lihat Password';
        })
    })
</script>
@endsection
