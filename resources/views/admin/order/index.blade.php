@extends('admin.layouts.master')
@section('title', 'Daftar Pesanan')

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
                <h3>Daftar Pesanan</h3>
                <p class="text-subtitle text-muted">Berbagai pesanan yang masuk</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dasbor</a></li>
                        <li class="breadcrumb-item active text-info font-bold" aria-current="page">Kelola Pesanan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                {{-- <div class="card-title order-first">
                    <a href="{{ route('items.create') }}" class="btn btn-primary float-start float-lg-end">
                        <i class="bi bi-plus"></i>
                        Tambah Menu
                    </a>
                </div> --}}
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pesanan</th>
                            <th>Nama pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Nomor meja</th>
                            <th>Metode Pembayaran</th>
                            <th>Catatan</th>
                            <th>Dibuat pada</th>
                            @if (in_array(auth()->user()->role?->role_name, ['admin', 'cashier'], true))
                                <th>Ubah Status</th>
                            @endif
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->order_code ?? $order->order_number ?? '-' }}</td>
                            <td>{{ $order->user?->fullname ?? '-' }}</td>
                            <td>Rp {{ number_format((float) $order->grand_total, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $status = strtolower($order->status ?? '');
                                    $statusLabel = match ($status) {
                                        'settlement' => 'Lunas',
                                        'pending' => 'Menunggu pembayaran',
                                        'cooked', 'coocked' => 'Selesai dimasak',
                                        default => ucfirst($status ?: '-'),
                                    };
                                    $statusClass = match ($status) {
                                        'settlement' => 'bg-success',
                                        'pending' => 'bg-warning text-dark',
                                        'cooked', 'coocked' => 'bg-info text-dark',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td>{{ $order->table_number ?? '-' }}</td>
                            <td>{{ $order->payment_method === 'qris' ? 'QRIS' : 'Tunai' }}</td>
                            <td>{{ $order->note ?? $order->note ?? '-' }}</td>
                            <td>{{ $order->created_at?->format('d/m/Y H:i') ?? '-' }}</td>

                            @php($roleName = auth()->user()->role?->role_name)
                            @if (in_array($roleName, ['admin', 'cashier'], true))
                            <td>
                                <form action="{{ route('orders.update', $order->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm" aria-label="Status pesanan">
                                        <option value="pending" @selected($order->status === 'pending')>Menunggu pembayaran</option>
                                        <option value="settlement" @selected($order->status === 'settlement')>Lunas</option>
                                        <option value="cooked" @selected(in_array($order->status, ['coocked', 'cooked'], true))>Selesai dimasak</option>
                                    </select>
                                    <button type="submit" class="badge bg-primary border-0" style="cursor: pointer;">Simpan</button>
                                </form>
                            </td>
                            @endif
                            <td>
                                <span class="badge bg-primary">
                                    <a href="{{ route('orders.show', $order->id) }}" class="text-white">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </span>
                                @if (in_array(Auth::user()->role?->role_name, ['admin', 'cashier'], true)
                                    && $order->status === 'pending'
                                    && in_array($order->payment_method, ['tunai', 'qris'], true)
                                    )
                                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="badge bg-success border-0 mt-1">
                                            <i class="bi bi-check-circle"></i> Terima Pembayaran
                                        </button>
                                    </form>
                                @elseif(Auth::user()->role?->role_name === 'chef' && $order->status === 'settlement')
                                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="badge bg-success border-0 mt-1">
                                            <i class="bi bi-check-circle"></i> Pesanan Siap
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
