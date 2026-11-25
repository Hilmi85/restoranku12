@extends('customer.layouts.master')

@section('title', 'Checkout Pesanan')

@section('content')
    <section class="customer-page-intro">
        <p class="customer-eyebrow">Langkah terakhir</p>
        <h1>Konfirmasi pesanan</h1>
        <p>Lengkapi data pemesan dan pilih metode pembayaran yang paling nyaman.</p>
    </section>

    @php
        $subTotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['qty']);
        $tax = $subTotal * 0.1;
        $total = $subTotal + $tax;
    @endphp

    <main class="container customer-main-section">
        @if ($errors->any())
            <div class="customer-alert is-error" role="alert">{{ $errors->first() }}</div>
        @endif

        <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="customer-checkout-grid">
                <div class="customer-panel">
                    <div class="customer-panel-heading">
                        <h2>Informasi pemesan</h2>
                        <p>Data ini digunakan untuk menghubungkan pesanan Anda.</p>
                    </div>

                    <div class="customer-checkout-fields">
                        <div class="customer-form-field">
                            <label for="fullname">Nama lengkap <span>*</span></label>
                            <input
                                id="fullname"
                                type="text"
                                name="fullname"
                                class="customer-form-control {{ $errors->has('fullname') ? 'is-invalid' : '' }}"
                                placeholder="Masukkan nama lengkap"
                                value="{{ old('fullname') }}"
                                autocomplete="name"
                                required
                            >
                            @error('fullname') <p class="customer-field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="customer-form-field">
                            <label for="phone">Nomor WhatsApp <span>*</span></label>
                            <input
                                id="phone"
                                type="tel"
                                name="phone"
                                class="customer-form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                placeholder="Contoh: 081234567890"
                                value="{{ old('phone') }}"
                                autocomplete="tel"
                                required
                            >
                            @error('phone') <p class="customer-field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="customer-form-field">
                            <label for="table-number">Nomor meja</label>
                            <input id="table-number" type="text" class="customer-form-control" value="{{ session('tableNumber') ? 'Meja ' . session('tableNumber') : 'Belum dipilih' }}" disabled>
                        </div>

                        <div class="customer-form-field is-full">
                            <label for="note">Catatan pesanan <span class="text-muted fw-normal">(opsional)</span></label>
                            <textarea id="note" name="note" class="customer-form-control customer-note" placeholder="Contoh: tidak pedas, saus dipisah">{{ old('note') }}</textarea>
                        </div>
                    </div>

                    <div class="customer-checkout-items">
                        <h3>Menu yang dipesan</h3>
                        <div class="customer-table-wrap">
                            <table class="table customer-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Menu</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart as $item)
                                        <tr>
                                            <td>
                                                <div class="customer-product-cell">
                                                    <img
                                                        src="{{ asset('img_item_upload/' . $item['image']) }}"
                                                        alt="{{ $item['name'] }}"
                                                        loading="lazy"
                                                        onerror="this.onerror=null;this.src='{{ $item['image'] }}';"
                                                    >
                                                    <div><strong>{{ $item['name'] }}</strong></div>
                                                </div>
                                            </td>
                                            <td>Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                                            <td>{{ $item['qty'] }}</td>
                                            <td><strong>Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <aside class="customer-summary customer-checkout-summary">
                    <h2>Ringkasan pembayaran</h2>
                    <div class="customer-summary-row"><span>Subtotal</span><strong>Rp{{ number_format($subTotal, 0, ',', '.') }}</strong></div>
                    <div class="customer-summary-row"><span>Pajak (10%)</span><strong>Rp{{ number_format($tax, 0, ',', '.') }}</strong></div>
                    <div class="customer-summary-total"><span>Total</span><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></div>

                    <div class="customer-payment-methods">
                        <p class="customer-form-field mb-2"><label>Metode pembayaran <span>*</span></label></p>
                        <label class="customer-payment-choice" for="qris">
                            <input type="radio" id="qris" name="payment_method" value="qris" @checked(old('payment_method') === 'qris') required>
                            <span><strong>QRIS</strong><small>Bayar melalui aplikasi pembayaran digital</small></span>
                        </label>
                        <label class="customer-payment-choice" for="cash">
                            <input type="radio" id="cash" name="payment_method" value="tunai" @checked(old('payment_method') === 'tunai')>
                            <span><strong>Tunai di kasir</strong><small>Tunjukkan kode pesanan setelah konfirmasi</small></span>
                        </label>
                    </div>

                    <p id="checkout-feedback" class="customer-feedback" role="alert"></p>
                    <button type="button" id="pay-button" class="customer-primary-button w-100">
                        <span>Konfirmasi pesanan</span>
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </button>
                    <p class="small text-muted text-center mt-3 mb-0">Pesanan Anda aman dan tersimpan setelah dikonfirmasi.</p>
                </aside>
            </div>
        </form>
    </main>
@endsection

@section('script')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const payButton = document.getElementById('pay-button');
            const form = document.getElementById('checkout-form');
            const feedback = document.getElementById('checkout-feedback');
            const buttonMarkup = payButton.innerHTML;

            payButton.addEventListener('click', async function () {
                feedback.textContent = '';

                if (!form.reportValidity()) return;

                const paymentMethod = form.querySelector('input[name="payment_method"]:checked');
                if (!paymentMethod) {
                    feedback.textContent = 'Pilih metode pembayaran terlebih dahulu.';
                    return;
                }

                if (paymentMethod.value === 'tunai') {
                    form.submit();
                    return;
                }

                payButton.disabled = true;
                payButton.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Memproses pembayaran</span>';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });
                    const data = await response.json();

                    if (!response.ok || !data.snap_token) {
                        const validationMessage = data.errors ? Object.values(data.errors).flat()[0] : null;
                        throw new Error(validationMessage || data.message || 'Pembayaran belum dapat diproses. Silakan coba lagi.');
                    }

                    const successUrl = @json(route('checkout.success', ['orderId' => '__ORDER_CODE__']));
                    window.snap.pay(data.snap_token, {
                        gopayMode: 'qr',
                        onSuccess: function () {
                            window.location.href = successUrl.replace('__ORDER_CODE__', encodeURIComponent(data.order_code));
                        },
                        onPending: function () {
                            window.showCustomerToast('Pembayaran menunggu konfirmasi.', 'error');
                            payButton.disabled = false;
                            payButton.innerHTML = buttonMarkup;
                        },
                        onError: function () {
                            window.showCustomerToast('Pembayaran belum berhasil. Silakan coba lagi.', 'error');
                            payButton.disabled = false;
                            payButton.innerHTML = buttonMarkup;
                        },
                        onClose: function () {
                            payButton.disabled = false;
                            payButton.innerHTML = buttonMarkup;
                        },
                    });
                } catch (error) {
                    feedback.textContent = error.message || 'Terjadi kendala. Silakan coba lagi.';
                    payButton.disabled = false;
                    payButton.innerHTML = buttonMarkup;
                }
            });
        });
    </script>
@endsection
