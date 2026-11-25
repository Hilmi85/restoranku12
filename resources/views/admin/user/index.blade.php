@extends('admin.layouts.master')
@section('title', 'Karyawan')

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
                <h3>Manajemen Karyawan</h3>
                <p class="text-subtitle text-muted">Informasi karyawan yang terdaftar</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dasbor</a></li>
                        <li class="breadcrumb-item active text-info font-bold" aria-current="page">Manajemen Karyawan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="card-title order-first">
                    <a href="{{ route('users.create') }}" class="btn btn-primary float-start float-lg-end">
                        <i class="bi bi-plus"></i>
                        Tambah pengguna
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i>  {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama pengguna</th>
                            <th>Nama lengkap</th>
                            <th>Email</th>
                            <th>Nomor telepon</th>
                            <th>Peran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->role?->role_name ?? '-' }}</td>

                            <td class="text-nowrap">
                                <a href="{{ route('users.edit', $user->id) }}"
                                class="badge bg-warning text-dark text-decoration-none">
                                    <i class="bi bi-pencil"></i> Ubah
                                </a>

                                <form action="{{ route('users.destroy', $user->id) }}"
                                    method="POST"
                                    class="d-inline m-0"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
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
