@extends('customer.layouts.master')

@section('title', 'Keranjang Belanja')

@section('content')
    <section class="customer-page-intro">
        <p class="customer-eyebrow">Satu langkah menuju hidangan pilihan</p>
        <h1>Keranjang Anda</h1>
        <p>Periksa menu dan jumlah pesanan sebelum melanjutkan ke pembayaran.</p>
    </section>

    <main class="container customer-main-section">
        @if (session('success'))
            <div class="customer-alert" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="customer-alert is-error" role="alert">{{ session('error') }}</div>
        @endif

        @if (empty($cart))
            <div class="customer-empty-state">
                <span class="customer-empty-icon"><i class="fas fa-shopping-bag" aria-hidden="true"></i></span>
                <h2>Keranjang masih kosong</h2>
                <p>Silakan pilih hidangan favorit Anda dan tambahkan ke keranjang.</p>
                <a href="{{ route('menu') }}" class="customer-primary-button">
                    Jelajahi menu <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        @else
            @php
                $subTotal = 0;
            @endphp
            <div class="row g-4 align-items-start">
                <div class="col-lg-8">
                    <div class="customer-panel">
                        <div class="customer-panel-heading d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <h2>Menu dalam keranjang</h2>
                                <p>Atur jumlah atau hapus menu yang tidak diperlukan.</p>
                            </div>
                            <a href="{{ route('menu') }}" class="customer-secondary-button d-none d-sm-inline-flex">
                                <i class="fas fa-plus" aria-hidden="true"></i> Tambah menu
                            </a>
                        </div>
                        <div class="customer-table-wrap">
                            <table class="table customer-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Menu</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">Total</th>
                                        <th scope="col"><span class="visually-hidden">Aksi</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart as $item)
                                        @php
                                            $itemTotal = $item['price'] * $item['qty'];
                                            $subTotal += $itemTotal;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="customer-product-cell">
                                                    <img
                                                        src="{{ asset('img_item_upload/' . $item['image']) }}"
                                                        alt="{{ $item['name'] }}"
                                                        loading="lazy"
                                                        onerror="this.onerror=null;this.src='{{ $item['image'] }}';"
                                                    >
                                                    <div>
                                                        <strong>{{ $item['name'] }}</strong>
                                                        <small>Menu pilihan Restoranku</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                                            <td>
                                                <div class="customer-quantity-control" aria-label="Jumlah {{ $item['name'] }}">
                                                    <button type="button" aria-label="Kurangi jumlah" onclick="updateQuantity({{ $item['id'] }}, -1)"><i class="fas fa-minus" aria-hidden="true"></i></button>
                                                    <input id="qty-{{ $item['id'] }}" type="text" value="{{ $item['qty'] }}" readonly aria-label="Jumlah item">
                                                    <button type="button" aria-label="Tambah jumlah" onclick="updateQuantity({{ $item['id'] }}, 1)"><i class="fas fa-plus" aria-hidden="true"></i></button>
                                                </div>
                                            </td>
                                            <td><strong>Rp{{ number_format($itemTotal, 0, ',', '.') }}</strong></td>
                                            <td>
                                                <button
                                                    type="button"
                                                    class="customer-remove-button"
                                                    aria-label="Hapus {{ $item['name'] }} dari keranjang"
                                                    onclick="if (confirm('Hapus menu ini dari keranjang?')) removeItemFromCart({{ $item['id'] }})"
                                                >
                                                    <i class="fas fa-times" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 p-3 p-md-4">
                            <a href="{{ route('menu') }}" class="customer-secondary-button">
                                <i class="fas fa-arrow-left" aria-hidden="true"></i> Lanjut belanja
                            </a>
                            <a href="{{ route('cart.clear') }}" class="customer-secondary-button text-danger" onclick="return confirm('Kosongkan seluruh keranjang?')">
                                <i class="fas fa-trash-alt" aria-hidden="true"></i> Kosongkan keranjang
                            </a>
                        </div>
                    </div>
                </div>

                @php
                    $tax = $subTotal * 0.1;
                    $total = $subTotal + $tax;
                @endphp
                <div class="col-lg-4">
                    <aside class="customer-summary">
                        <h2>Ringkasan pesanan</h2>
                        <div class="customer-summary-row"><span>Subtotal</span><strong>Rp{{ number_format($subTotal, 0, ',', '.') }}</strong></div>
                        <div class="customer-summary-row"><span>Pajak (10%)</span><strong>Rp{{ number_format($tax, 0, ',', '.') }}</strong></div>
                        <div class="customer-summary-total"><span>Total</span><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></div>
                        <p class="small text-muted mt-3 mb-3">Total sudah termasuk pajak.</p>
                        <a href="{{ route('checkout') }}" class="customer-primary-button w-100">
                            Lanjut ke pembayaran <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </aside>
                </div>
            </div>
        @endif
    </main>
@endsection

@section('script')
    <script>
        async function updateQuantity(itemId, change) {
            const qtyInput = document.getElementById('qty-' + itemId);
            const newQty = parseInt(qtyInput.value, 10) + change;

            if (newQty <= 0) {
                if (confirm('Hapus menu ini dari keranjang?')) await removeItemFromCart(itemId);
                return;
            }

            try {
                const response = await fetch("{{ route('cart.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ id: itemId, qty: newQty }),
                });
                const data = await response.json();

                if (!response.ok || !data.success) throw new Error(data.message || 'Jumlah belum berhasil diperbarui.');
                location.reload();
            } catch (error) {
                window.showCustomerToast(error.message || 'Terjadi kendala. Silakan coba lagi.', 'error');
            }
        }

        async function removeItemFromCart(itemId) {
            try {
                const response = await fetch("{{ route('cart.remove') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ id: itemId }),
                });
                const data = await response.json();

                if (!response.ok || !data.success) throw new Error('Menu belum berhasil dihapus.');
                location.reload();
            } catch (error) {
                window.showCustomerToast(error.message || 'Terjadi kendala. Silakan coba lagi.', 'error');
            }
        }
    </script>
@endsection
