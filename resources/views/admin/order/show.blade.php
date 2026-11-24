@extends('admin.layouts.master')
@section('title', 'Detail Pesanan')

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
                <h3>Detail Pesanan</h3>
                <p class="text-subtitle text-muted">Informasi Lengkap Pesanan yang Masuk</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Kelola Pesanan</a></li>
                        <li class="breadcrumb-item active text-info font-bold" aria-current="page">Detail Pesanan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4>Kode Pesanan: {{ $order->order_code }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Dibuat Pada      :</strong> {{ $order->created_at?->format('d-m-Y H:i') ?? '-' }}</p>
                        <p><strong>Nama Pelanggan   :</strong> {{ $order->user?->fullname ?? '-' }}</p>
                        <p><strong>Status           :</strong>
                            @php
                                $status = strtolower($order->status ?? '');
                                $statusLabel = match ($status) {
                                    'settlement' => 'Sudah Dibayar',
                                    'pending' => 'Belum Dibayar',
                                    'cooked', 'coocked' => 'Cooked',
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
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>No. Meja         :</strong> {{ $order->table_number ?? '-' }}</p>
                        <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
                        <p><strong>Catatan          :</strong> {{ $order->note ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4>Daftar Item Pesanan</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama Menu</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderItems as $menu)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ asset('img_item_upload/'. $menu->item->img) }}" class="img-fluid rounded-top" alt="" style="max-width: 100px; height: auto;" onerror="this.onerror=null;this.src='{{ $menu->item->img }}';">
                            </td>
                            <td>{{ $menu->item->name ?? '-' }}</td>
                            <td>{{ $menu->quantity }}</td>
                            <td>Rp {{ number_format((float) $menu->item->price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total:</strong></td>
                            <td><strong>Rp {{ number_format((float) $order->subtotal, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Pajak:</strong></td>
                            <td><strong>Rp {{ number_format((float)$order->tax, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                            <td><strong>Rp {{ number_format((float) $order->grand_total, 0, ',', '.') }}</strong></td>
                        </tr>
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

