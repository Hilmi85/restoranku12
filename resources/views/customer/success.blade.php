@extends('customer.layouts.master')

@section('title', 'Pesanan Berhasil')

@section('content')
    @php
        $isPaid = in_array(strtolower($order->status ?? ''), ['settlement', 'cooked', 'coocked'], true);
    @endphp

    <main class="container customer-success-wrap">
        <article class="customer-receipt">
            <header class="customer-receipt-head">
                <span class="customer-receipt-icon" aria-hidden="true">
                    <i class="fas {{ $isPaid ? 'fa-check' : 'fa-receipt' }}"></i>
                </span>
                <p>Restoranku · Rincian pesanan</p>
                <h1>{{ $isPaid ? 'Pembayaran diterima' : 'Pesanan berhasil dibuat' }}</h1>
                @if ($isPaid)
                    <span class="customer-status-pill is-paid"><i class="fas fa-check-circle" aria-hidden="true"></i> Pembayaran berhasil</span>
                @elseif ($order->payment_method === 'tunai')
                    <span class="customer-status-pill"><i class="fas fa-wallet" aria-hidden="true"></i> Bayar di kasir</span>
                @else
                    <span class="customer-status-pill"><i class="fas fa-clock" aria-hidden="true"></i> Menunggu konfirmasi pembayaran</span>
                @endif
            </header>

            <div class="customer-receipt-body">
                <div class="customer-order-code">
                    <span>Kode pesanan</span>
                    <strong>{{ $order->order_code }}</strong>
                </div>

                <div class="d-flex justify-content-between gap-3 mb-3 small text-muted">
                    <span>Meja {{ $order->table_number }}</span>
                    <span>{{ $order->payment_method === 'qris' ? 'QRIS' : 'Tunai' }}</span>
                </div>

                <table class="customer-receipt-lines">
                    <tbody>
                        @foreach ($orderItems as $orderItem)
                            <tr>
                                <td>{{ $orderItem->item?->name ?? 'Menu' }} <span class="text-muted">× {{ $orderItem->quantity }}</span></td>
                                <td>Rp{{ number_format($orderItem->price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="customer-receipt-lines">
                    <tbody>
                        <tr><td>Subtotal</td><td>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td></tr>
                        <tr><td>Pajak (10%)</td><td>Rp{{ number_format($order->tax, 0, ',', '.') }}</td></tr>
                        <tr class="customer-line-total"><td>Total pembayaran</td><td>Rp{{ number_format($order->grand_total, 0, ',', '.') }}</td></tr>
                    </tbody>
                </table>

                @if ($order->payment_method === 'tunai' && !$isPaid)
                    <p class="customer-receipt-note">Tunjukkan kode pesanan ini kepada kasir untuk menyelesaikan pembayaran.</p>
                @elseif (!$isPaid)
                    <p class="customer-receipt-note">Pembayaran QRIS Anda sedang menunggu konfirmasi. Simpan kode pesanan ini.</p>
                @endif

                <a href="{{ route('menu') }}" class="customer-primary-button w-100">
                    Kembali ke menu <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </article>
    </main>
@endsection
