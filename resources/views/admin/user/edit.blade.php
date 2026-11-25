@extends('admin.layouts.master')
@section('title', 'Ubah Data Karyawan')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Ubah Data Karyawan</h3>
            <p class="text-subtitle text-muted">Ubah Data Karyawan</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dasbor</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Daftar Karyawan</a></li>
                    <li class="breadcrumb-item active text-info font-bold" aria-current="page">Ubah Data Karyawan</li>
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
        <form class="form" method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="fullname">Nama lengkap</label>
                            <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Masukkan nama lengkap" value="{{ $user->fullname }}" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Nama pengguna</label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan nama pengguna" value="{{ $user->username }}" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Nomor telepon</label>
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="Masukkan nomor telepon" value="{{ $user->phone }}" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Peran</label>
                            <select class="form-control" id="role" name="role_id" required>
                                <option value="" disabled selected>Pilih Peran</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Alamat email</label>
                            <input type="text" class="form-control" name="email" id="email" placeholder="Masukkan alamat email" value="{{ $user->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Kata sandi</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan kata sandi">
                            <small><a href="#" class="toggle-password" data-target="password">Lihat kata sandi</a></small>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi kata sandi</label>
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi kata sandi">
                            <small><a href="#" class="toggle-password" data-target="password_confirmation">Lihat kata sandi</a></small>
                        </div>
                        
                        <div class="form-group d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Atur ulang</button>
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
            document.querySelector(`a[data-target="${this.dataset.target}"]`).textContent = isHidden ? 'Sembunyikan kata sandi' : 'Lihat kata sandi';
        })
    })
</script>
@endsection
